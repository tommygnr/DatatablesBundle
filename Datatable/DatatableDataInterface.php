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

/**
 * Class DatatableDataInterface
 */
interface DatatableDataInterface
{
    /**
     * Get results.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse();
}