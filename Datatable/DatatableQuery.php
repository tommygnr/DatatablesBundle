<?php

/**
 * This file is part of the TommyGNRDatatablesBundle package.
 *
 * (c) Tom Corrigan <https://github.com/tommygnr/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TommyGNR\DatatablesBundle\Datatable;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use TommyGNR\DatatablesBundle\Datatable\View\AbstractDatatableView;
use TommyGNR\DatatablesBundle\Column\ColumnInterface;

/**
 * Class DatatableQuery
 *
 * A thanks goes to the authors of the original implementation:
 *     Félix-Antoine Paradis (https://gist.github.com/reel/1638094) and
 *     Chad Sikorra (https://github.com/LanKit/DatatablesBundle)
 */
class DatatableQuery
{
    /**
     * @var array
     */
    protected $requestParams;

    /**
     * @var ClassMetadata
     */
    protected $metadata;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var array
     */
    protected $allColumns;

    /**
     * @var array
     */
    protected $joins;

    /**
     * @var array
     */
    protected $callbacks;

    /**
     * @var array
     */
    protected $resolvedTableAliases;

    /**
     * Constructor.
     *
     * @param array         $requestParams All GET params
     * @param ClassMetadata $metadata      A ClassMetadata instance
     * @param EntityManager $em            A EntityManager instance
     */
    public function __construct(array $requestParams, ClassMetadata $metadata, EntityManager $em, AbstractDatatableView $datatable)
    {
        $this->requestParams = $requestParams;
        $this->metadata = $metadata;
        $this->em = $em;
        $this->datatable = $datatable;

        $this->qb = $this->em->createQueryBuilder();
        $this->allColumns = array();
        $this->joins = array();
        $this->resolvedTableAliases = array();
        $this->callbacks = array(
            'WhereBuilder' => array(),
        );
    }

    /**
     * Get qb.
     *
     * @return QueryBuilder
     */
    public function getQb()
    {
        return $this->qb;
    }

    /**
     * Set allColumns.
     *
     * @param array $allColumns
     *
     * @return $this
     */
    public function setAllColumns(array $allColumns)
    {
        $this->allColumns = $allColumns;

        return $this;
    }

    /**
     * Set joins.
     *
     * @param array $joins
     *
     * @return $this
     */
    public function setJoins(array $joins)
    {
        $this->joins = $joins;

        return $this;
    }

    /**
     * Add resolved table alias
     *
     * @param array $joins
     *
     * @return $this
     */
    public function addResolvedTableAlias($propertyPath, $tableAlias, $column)
    {
        $this->resolvedTableAliases[$propertyPath] = ['alias' => $tableAlias, 'column' => $column];

        return $this;
    }

    /**
     * Add callback.
     *
     * @param string $callback
     *
     * @return $this
     */
    public function addCallback($callback)
    {
        $this->callbacks['WhereBuilder'][] = $callback;

        return $this;
    }

    /**
     * Query results before filtering.
     *
     * @param integer $rootEntityIdentifier
     *
     * @return int
     */
    public function getCountAllResults($rootEntityIdentifier)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(' . $this->metadata->getTableName() . '.' . $rootEntityIdentifier . ')');
        $qb->from($this->metadata->getName(), $this->metadata->getTableName());
        $this->setLeftJoins($qb);

        $this->setWhereCallbacks($qb);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Set select from.
     *
     * @return $this
     */
    public function setSelectFrom(array $selectColumns)
    {
        foreach ($selectColumns as $tableAlias => $selectColumns) {
            $this->qb->addSelect('partial ' . $tableAlias . '.{' . implode(',', $selectColumns) . '}');
        }

        $this->qb->from($this->metadata->getName(), $this->metadata->getTableName());

        return $this;
    }

    /**
     * Set leftJoins.
     *
     * @param QueryBuilder $qb A QueryBuilder instance
     *
     * @return $this
     */
    public function setLeftJoins(QueryBuilder $qb)
    {
        foreach ($this->joins as $join) {
            $qb->leftJoin($join['source'], $join['target']);
        }

        return $this;
    }

    /**
     * Set where statement.
     *
     * @param QueryBuilder $qb A QueryBuilder instance
     *
     * @return $this
     */
    public function setWhere(QueryBuilder $qb)
    {
        // global filtering
        $globalSearchString = $this->requestParams['search']['value'];
        $i = 0;
        $dtColumns = $this->datatable->getColumns();

        if ($globalSearchString != '') {
            $orExpr = $qb->expr()->orX();

            foreach ($this->requestParams['columns'] as $key => $column) {
                //TODO This should be read from server side(PHP) config, not client side
                $dtColumn = $dtColumns[$key];
                if ($dtColumn->isSearchable()) {
                    $searchField = $this->allColumns[$key];
                    $orExpr->add($qb->expr()->like($searchField, "?$i"));
                }
            }
            $qb->setParameter($i, "%" . $globalSearchString . "%");
            $i++;

            $qb->andWhere($orExpr);
        }

        // individual filtering
        $andExpr = $qb->expr()->andX();

        foreach ($this->requestParams['columns'] as $key => $column) {
            $dtColumn = $dtColumns[$key];
            if ($dtColumn->isFilterable() && $column['search']['value'] != '') {
                //TODO This should be read from server side(PHP) config, not client side
                $searchField = $this->allColumns[$key];
                $andExpr->add($qb->expr()->like($searchField, "?$i"));
                $qb->setParameter($i, "%" . $column['search']['value'] . "%");
                $i++;
            }
        }

        if ($andExpr->count() > 0) {
            $qb->andWhere($andExpr);
        }

        return $this;
    }

    /**
     * Set where callback functions.
     *
     * @param QueryBuilder $qb A QueryBuilder instance
     *
     * @return $this
     */
    public function setWhereCallbacks(QueryBuilder $qb)
    {
        if (!empty($this->callbacks['WhereBuilder'])) {
            foreach ($this->callbacks['WhereBuilder'] as $callback) {
                $callback($qb);
            }
        }

        return $this;
    }

    /**
     * Set orderBy.
     *
     * @return $this
     */
    public function setOrderBy()
    {
        foreach ($this->requestParams['order'] as $orderCol) {
            $this->qb->addOrderBy(
                $this->allColumns[(int) $orderCol['column']],
                $orderCol['dir']
            );
        }

        return $this;
    }

    /**
     * Set the scope of the resultset (Paging).
     *
     * @return $this
     */
    public function setLimit()
    {
        if (isset($this->requestParams['start']) && $this->requestParams['length'] != '-1') {
            $this->qb->setFirstResult($this->requestParams['start'])->setMaxResults($this->requestParams['length']);
        }

        return $this;
    }

    /**
     * Constructs a Query instance.
     *
     * @return Query
     */
    public function execute()
    {
        $query = $this->qb->getQuery();
        $query->setHydrationMode(Query::HYDRATE_ARRAY);

        return $query;
    }


    /**
     * Returns an array of column values, optionally filtered.
     *
     * @return array
     */
    public function getColumnValues(ColumnInterface $column, QueryBuilder $qb, $filter = false)
    {
        $values = $column->getFilterOptions();

        $key = $this->metadata->getTableName().'.'.$column->getProperty();

        if (isset($this->resolvedTableAliases[$column->getProperty()])){
            $fields = $this->resolvedTableAliases[$column->getProperty()];
            $key = $fields['alias'].'.'.$fields['column'];
        }

        $qb->select('DISTINCT('.$key.')');
        if ($values !== null) {
            $qb->andWhere($qb->expr()->in($key, ':filterOptions'));
            $qb->setParameter('filterOptions', $values);
        }
        $qb->andWhere($qb->expr()->isNotNull($key));
        $qb->andWhere($qb->expr()->neq($qb->expr()->trim($key), "''"));
        $qb->setFirstResult(null)->setMaxResults(null);
        $qb->orderBy($key, 'ASC');

        $this->setWhereCallbacks($qb);

        $values = [];
        foreach ($qb->getQuery()->getResult() as $row ) {
            $values[] = $row[1];
        }

        return $values;
    }
}