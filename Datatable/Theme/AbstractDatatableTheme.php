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
 * Class AbstractDatatableTheme
 */
abstract class AbstractDatatableTheme implements DatatableThemeInterface
{
    /**
     * Table styling.
     *
     * @var string
     */
    protected $tableClasses = null;

    /**
     * Form styling.
     *
     * @var string
     */
    protected $formClasses = null;

    /**
     * Form submit button styling.
     *
     * @var string
     */
    protected $formSubmitButtonClasses = null;

    /**
     * The pagination type.
     *
     * @var string
     */
    protected $pagination = null;

    /**
     * Position of the feature elements (filter input etc).
     *
     * @var mixed
     */
    protected $dom = null;

    /**
     * Whether or not to set jQueryUi in datatable config
     *
     * @var boolean
     */
    protected $jQueryUi = false;

    //-------------------------------------------------
    // Singleton
    //-------------------------------------------------

    /**
     * @var DatatableThemeInterface
     */
    private static $instance;

    /**
     * Ctor.
     */
    protected function __construct()
    {
    }

    /**
     * Clone.
     */
    final private function __clone()
    {
    }

    /**
     * Get theme instance.
     *
     * @return null|DatatableThemeInterface
     */
    public static function getTheme()
    {
        $className = get_called_class();

        if (!isset(self::$instance)) {
            self::$instance = new $className();
        }

        return self::$instance;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getName();

    /**
     * {@inheritdoc}
     */
    abstract public function getTemplate();

    /**
     * {@inheritdoc}
     */
    public function getTableClasses()
    {
        return $this->tableClasses;
    }

    /**
     * {@inheritdoc}
     */
    public function setTableClasses($tableClasses)
    {
        $this->tableClasses = $tableClasses;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormClasses()
    {
        return $this->formClasses;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormClasses($formClasses)
    {
        $this->formClasses = $formClasses;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormSubmitButtonClasses($formSubmitButtonClasses)
    {
        $this->formSubmitButtonClasses = $formSubmitButtonClasses;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormSubmitButtonClasses()
    {
        return $this->formSubmitButtonClasses;
    }

    /**
     * {@inheritdoc}
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * {@inheritdoc}
     */
    public function setPagination($pagination)
    {
        $this->pagination = $pagination;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * {@inheritdoc}
     */
    public function setDom($dom)
    {
        $this->dom = $dom;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isJqueryUi()
    {
        return $this->jQueryUi;
    }

    /**
     * {@inheritdoc}
     */
    public function setJqueryUi($jqueryUi)
    {
        $this->jQueryUi = $jqueryUi;

        return $this;
    }
}
