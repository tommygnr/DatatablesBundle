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

/**
 * Class Column
 *
 */
class Column extends BaseColumn
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'column';
    }
}
