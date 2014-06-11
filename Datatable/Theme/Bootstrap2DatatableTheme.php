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
 * Class BootstrapDatatableTheme
 */
class Bootstrap2DatatableTheme extends AbstractDatatableTheme
{
    /**
     * Default icon.
     *
     * @var string
     */
    const DEFAULT_ICON = 'glyphicon glyphicon-th';

    /**
     * Default show icon.
     *
     * @var string
     */
    const DEFAULT_SHOW_ICON = 'glyphicon glyphicon-eye-open';

    /**
     * Default edit icon.
     *
     * @var string
     */
    const DEFAULT_EDIT_ICON = 'glyphicon glyphicon-edit';

    /**
     * Default delete icon.
     *
     * @var string
     */
    const DEFAULT_DELETE_ICON = 'glyphicon glyphicon-trash';

    /**
     * Default true icon.
     *
     * @var string
     */
    const DEFAULT_TRUE_ICON = 'glyphicon glyphicon-ok';

    /**
     * Default false icon.
     *
     * @var string
     */
    const DEFAULT_FALSE_ICON = 'glyphicon glyphicon-remove';

    /**
     * Bootstrap2 table style.
     *
     * .table:          basic styling
     * .table-striped:  zebra-striping
     * .table-bordered: borders on all sides of the table and cells
     * .table-hover:    enable a hover state
     * .table-condensed make tables more compact by cutting cell padding in half
     *
     * @var string
     */
    protected $tableClasses = 'table table-striped table-bordered table-hover table-condensed';
    // protected $tableClasses = 'table table-striped table-bordered table-hover  ';
    /**
     * Bootstrap3 form styling.
     *
     * .form-control: default form styling
     * .input-sm:     height sizing
     *
     * @var string
     */
    // protected $formClasses = 'form-control input-sm';
    protected $formClasses = '';

    /**
     * Bootstrap3 form submit button styling.
     *
     * .btn:         base class
     * .btn-primary: identifies the primary action
     * .btn-sm:      small button
     *
     * @var string
     */
    protected $formSubmitButtonClasses = 'btn btn-primary btn-small';

    /**
     * The pagination type.
     *
     * @var string
     */
    // protected $pagination = 'bootstrap';
    protected $pagination = 'full_numbers';

    /**
     * Position of the feature elements (filter input etc).
     *
     * @var string
     */
    protected $dom = "<f<'dt_cb'>l>t<'F'p>";

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bootstrap2_datatable_theme';
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'TommyGNRDatatablesBundle:Datatable:datatable.html.twig';
    }

    /**
     * Put your datatable in a box.
     *
     * @return $this
     */
    public function setPanel()
    {
        $dom =
            "<'row'" .
                "<'col-sm-12 col-md-12'" .
                    "<'panel panel-default'" .
                        "<'panel-heading'" .
                            "<'row'" .
                                "<'col-xs-6 col-md-6'l>" .
                                "<'dt_cb'>" .
                                "<'col-xs-6 col-md-6'f>" .
                            ">" .
                        ">" .
                        "<'panel-body'" .
                            "<'table-responsive't>" .
                        ">" .
                        "<'panel-footer'" .
                            "<'row'" .
                                "<'col-xs-6 col-md-6'i>" .
                                "<'col-xs-6 col-md-6'p>" .
                            ">" .
                        ">" .
                    ">" .
                ">" .
            ">";

        $this->setDom($dom);

        return $this;
    }
}