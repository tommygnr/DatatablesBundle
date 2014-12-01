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
 * Class JqueryUiDatatableTheme
 */
class JqueryUiDatatableTheme extends AbstractDatatableTheme
{
    /**
     * Default icon.
     *
     * @var string
     */
    const DEFAULT_ICON = 'ui-icon ui-icon-folder-collapsed';

    /**
     * Default show icon.
     *
     * @var string
     */
    const DEFAULT_SHOW_ICON = 'ui-icon ui-icon-zoomin';

    /**
     * Default edit icon.
     *
     * @var string
     */
    const DEFAULT_EDIT_ICON = 'ui-icon ui-icon-pencil';

    /**
     * Default delete icon.
     *
     * @var string
     */
    const DEFAULT_DELETE_ICON = 'ui-icon ui-icon-trash';

    /**
     * Default true icon.
     *
     * @var string
     */
    const DEFAULT_TRUE_ICON = 'ui-icon ui-icon-circle-check';

    /**
     * Default false icon.
     *
     * @var string
     */
    const DEFAULT_FALSE_ICON = 'ui-icon ui-icon-circle-close';

    protected $jQueryUi = true;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jqueryui_datatable_theme';
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'TommyGNRDatatablesBundle:Datatable:datatable.html.twig';
    }
}
