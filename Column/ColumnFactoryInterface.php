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
 * Interface ColumnFactoryInterface
 *
 */
interface ColumnFactoryInterface
{
    /**
     * Returns a column.
     *
     * @param string $property An entity's property
     * @param string $name     The name of the column class
     *
     * @throws \Exception
     * @return ColumnInterface
     */
    public function createColumnByName($property, $name);
}