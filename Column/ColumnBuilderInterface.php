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
 * Interface ColumnBuilderInterface
 *
 */
interface ColumnBuilderInterface
{
    /**
     * Add a Column.
     *
     * @param string $property An entity's property
     * @param string $name     The name of the column class
     * @param array  $options  The column options
     *
     * @return $this
     */
    public function add($property, $name, array $options = array());

    /**
     * Get all columns.
     *
     * @return array
     */
    public function getColumns();
}
