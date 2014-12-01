<?php

/**
 * This file is part of the TommyGNRDatatablesBundle package.
 *
 * (c) Tom Corrigan <https://github.com/tommygnr/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TommyGNR\DatatablesBundle\Datatable\Theme;

/**
 * Class BaseDatatableTheme
 */
class BaseDatatableTheme extends AbstractDatatableTheme
{
    /**
     * @var string
     */
    protected $tableClasses = 'display';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'base_datatable_theme';
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'TommyGNRDatatablesBundle:Datatable:datatable.html.twig';
    }
}
