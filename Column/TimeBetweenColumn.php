<?php

/**
 * This file is part of the TommyGNRDatatablesBundle package.
 *
 * (c) Tom Corrigan <https://github.com/tommygnr/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TommyGNR\DatatablesBundle\Column;

use TommyGNR\DatatablesBundle\Column\AbstractColumn as BaseColumn;
use Exception;
use TommyGNR\DatatablesBundle\Datatable\DatatableQuery;
use TommyGNR\DatatablesBundle\Datatable\DatatableData;

/**
 * Class TimeagoColumn
 *
 */
class TimeBetweenColumn extends BaseColumn
{
    private $timeFromColumn;

    /**
     * Constructor.
     *
     * @param null|string $property An entity's property
     *
     * @throws Exception
     */
    public function __construct($property = null)
    {
        if (null == $property) {
            throw new Exception("The entity's property can not be null.");
        }

        parent::__construct($property);
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'timebetween';
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        if (!array_key_exists('time_from', $options) || null == $options['time_from']) {
            throw new Exception('The time_from option can not be null.');
        }

        $this->setTimeFromColumn($options['time_from']);
        if (!isset($options['extra_data'])) $options['extra_data'] = [];
        $options['extra_data']['match'] = $options['time_from'];

        parent::setOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function isSearchable()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isFilterable()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        parent::setDefaults();
    }

    /**
     * Sets the alias for the time difference column
     */
    public function setTimeFromColumn($timeFromColumn)
    {
        $this->timeFromColumn = $timeFromColumn;
        return $this;
    }

    /**
     * Gets the alias for the time difference column
     */
    public function getTimeFromColumn()
    {
        return $this->timeFromColumn;
    }

    /**
     * {@inheritdoc}
     */
    public function customQuerySettings(DatatableQuery $query, DatatableData $data)
    {
        $qb = $query->getQb();
        $fieldName = str_replace(".", "_", $this->getProperty());
        $endTime = $data->getTableAlias($this->getProperty());
        $startTime = $data->getTableAlias($this->getTimeFromColumn());
        $qb->addSelect("DATE_DIFF($startTime, $endTime) AS " . $fieldName);

        // Add column to allColumns
        $columns = $query->getAllColumns();
        foreach ($columns as $key => $column) {
            if ($column == $endTime) {
                $columns[$key] = $fieldName;
            }
        }
        $query->setAllColumns($columns);
    }
}
