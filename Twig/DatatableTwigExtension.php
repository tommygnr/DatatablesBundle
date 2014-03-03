<?php

/**
 * This file is part of the TommyGNRDatatablesBundle package.
 *
 * (c) Tom Corrigan <https://github.com/tommygnr/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TommyGNR\DatatablesBundle\Twig;

use TommyGNR\DatatablesBundle\Datatable\View\AbstractDatatableView;

use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Class DatatableTwigExtension
 *
 * @package TommyGNR\DatatablesBundle\Twig
 */
class DatatableTwigExtension extends Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'tommygnr_datatables_twig_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('datatable_render', array($this, 'datatableRender'), array('is_safe' => array('all')))
        );
    }

    /**
     * Renders the template.
     *
     * @param AbstractDatatableView $datatable
     *
     * @return string
     */
    public function datatableRender(AbstractDatatableView $datatable)
    {
        return $datatable->renderDatatableView();
    }
}
