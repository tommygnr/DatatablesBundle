<?php

/**
 * This file is part of the TommyGNRDatatablesBundle package.
 *
 * (c) Tom Corrigan <https://github.com/tommygnr/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TommyGNR\DatatablesBundle\Datatable\View;

/**
 * Interface DatatableViewInterface\View
 */
interface DatatableViewInterface
{
    /**
     * Builds the datatable view.
     */
    public function buildDatatableView();

    /**
     * Renders the datatable view.
     *
     * @return string
     */
    public function renderDatatableView();

    /**
     * Returns the name of this datatable view.
     * Is used as jQuery datatable id selector.
     *
     * @return string
     */
    public function getName();
}
