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

/**
 * Class ColumnBuilder
 *
 */
class ColumnBuilder implements ColumnBuilderInterface
{
    /**
     * A ColumnFactoryInterface.
     *
     * @var ColumnFactory
     */
    private $columnFactory;

    /**
     * All generated columns.
     *
     * @var array
     */
    private $columns;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->columnFactory = new ColumnFactory();
        $this->columns = array();
    }

    /**
     * {@inheritdoc}
     */
    public function add($property, $name, array $options = array())
    {
        /**
         * @var ColumnInterface $column
         */
        $column = $this->columnFactory->createColumnByName($property, $name);
        $column->setOptions($options);

        $this->columns[] = $column;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return $this->columns;
    }
}