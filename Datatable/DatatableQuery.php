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
    protected $selectColumns;

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
     * Constructor.
     *
     * @param array         $requestParams All GET params
     * @param ClassMetadata $metadata      A ClassMetadata instance
     * @param EntityManager $em            A EntityManager instance
     */
    public function __construct(array $requestParams, ClassMetadata $metadata, EntityManager $em)
    {
        $this->requestParams = $requestParams;
        $this->metadata = $metadata;
        $this->em = $em;

        $this->qb = $this->em->createQueryBuilder();
        $this->selectColumns = array();
        $this->allColumns = array();
        $this->joins = array();
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
     * Set selectColumns.
     *
     * @param array $selectColumns
     *
     * @return $this
     */
    public function setSelectColumns(array $selectColumns)
    {
        $this->selectColumns = $selectColumns;

        return $this;
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

        $this->setWhereCallbacks($qb);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Query results after filtering.
     *
     * @param integer $rootEntityIdentifier
     *
     * @return int
     */
    public function getCountFilteredResults($rootEntityIdentifier)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(distinct ' . $this->metadata->getTableName() . '.' . $rootEntityIdentifier . ')');
        $qb->from($this->metadata->getName(), $this->metadata->getTableName());

        $this->setLeftJoins($qb);
        $this->setWhere($qb);
        $this->setWhereCallbacks($qb);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Set select from.
     *
     * @return $this
     */
    public function setSelectFrom()
    {
        foreach ($this->selectColumns as $key => $value) {
            // $qb->select('partial comment.{id, title}, partial post.{id, title}');
            $this->qb->addSelect('partial ' . $key . '.{' . implode(',', $this->selectColumns[$key]) . '}');
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
        if ($globalSearchString != '') {

            $orExpr = $qb->expr()->orX();

            foreach ($this->requestParams['columns'] as $column) {
                //TODO This should be read from server side(PHP) config, not client side
                if (isset($column['searchable']) && $column['searchable'] === 'true') {
                    $searchField = $column['data'];
                    $orExpr->add($qb->expr()->like($searchField, "?$i"));
                }
            }
            $qb->setParameter($i, "%" . $globalSearchString . "%");
            $i++;

            $qb->andWhere($orExpr);
        }

        // individual filtering
        $andExpr = $qb->expr()->andX();

        foreach ($this->requestParams['columns'] as $column) {
            if (isset($column['searchable']) && $column['searchable'] === 'true' && $column['search']['value'] != '') {
                //TODO This should be read from server side(PHP) config, not client side
                $searchField = $column['data'];
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
        if (isset($this->requestParams['iSortCol_0'])) {
            for ($i = 0; $i < intval($this->requestParams['iSortingCols']); $i++) {
                if ($this->requestParams['bSortable_'.intval($this->requestParams['iSortCol_' . $i])] === 'true') {
                    $this->qb->addOrderBy(
                        $this->allColumns[$this->requestParams['iSortCol_' . $i]],
                        $this->requestParams['sSortDir_' . $i]
                    );
                }
            }
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
}