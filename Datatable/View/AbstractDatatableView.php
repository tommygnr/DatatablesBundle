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

use TommyGNR\DatatablesBundle\Column\ColumnBuilder;
use TommyGNR\DatatablesBundle\Datatable\Theme\DatatableThemeInterface;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Translation\Translator;
use Exception;

/**
 * Class AbstractDatatableView\View
 */
abstract class AbstractDatatableView implements DatatableViewInterface
{

    private $entity;

    /**
     * The templating service.
     *
     * @var TwigEngine
     */
    private $templating;

    /**
     * The translator service.
     *
     * @var Translator
     */
    private $translator;

    /**
     * The router service.
     *
     * @var Router
     */
    private $router;

    /**
     * A theme instance.
     *
     * @var DatatableThemeInterface
     */
    private $theme;

    /**
     * Configure DataTables to use server-side processing.
     *
     * @var boolean
     */
    private $serverSide;

    /**
     * An array of data to use for the table.
     *
     * @var mixed
     */
    private $data;

    /**
     * Enable or disable the display of a 'processing' indicator.
     *
     * @var boolean
     */
    private $processing;

    /**
     * Number of rows to display on a single page when using pagination.
     *
     * @var integer
     */
    private $displayLength;

    /**
     * A ColumnBuilder instance.
     *
     * @var ColumnBuilder
     */
    protected $columnBuilder;

    /**
     * The ajaxSource path.
     *
     * @var string
     */
    private $ajaxSource;

    /**
     * Array for custom options.
     *
     * @var array
     */
    private $customizeOptions;

    /**
     * Array of columns for default ordering
     *
     * @var array
     */
    private $defaultOrder;

    /**
     * Array of columns default searches
     *
     * @var array
     */
    private $defaultColumnSearches;

    /**
     * Enable or disable datatable state saving
     *
     * @var boolean
     */
    private $stateSaving;

    /**
     * Set stateDuration
     *
     * @var int
     */
    private $stateDuration;

    /**
     * Set clearStateEnabled
     *
     * @var int
     */
    private $clearStateEnabled = false;

    /**
     * Constructor.
     *
     * @param TwigEngine $templating           The templating service
     * @param Translator $translator           The translator service
     * @param Router     $router               The router service
     * @param array      $defaultLayoutOptions The default layout options
     */
    public function __construct(TwigEngine $templating, Translator $translator, Router $router, array $defaultLayoutOptions)
    {
        $this->templating = $templating;
        $this->translator = $translator;
        $this->router = $router;
        $this->setServerSide($defaultLayoutOptions['server_side']);
        $this->setProcessing($defaultLayoutOptions['processing']);
        $this->setDisplayLength($defaultLayoutOptions['display_length']);
        $this->columnBuilder = new ColumnBuilder();
        $this->ajaxSource = '';
        $this->customizeOptions = array();
    }

    /**
     * {@inheritdoc}
     */
    abstract public function buildDatatableView();

    /**
     * {@inheritdoc}
     */
    public function renderDatatableView()
    {
        $options = $this->getResolvedOptions();

        return $this->templating->render($this->theme->getTemplate(), $options);
    }

    public function getResolvedOptions()
    {
        $options = array();

        $options['dt_serverSide'] = $this->isServerSide();
        $options['dt_ajaxSource'] = $this->getAjaxSource();

        if (true === $options['dt_serverSide']) {
            if ('' === $options['dt_ajaxSource']) {
                throw new Exception('The ajaxSource parameter must be given.');
            }
        } else {
            $options['dt_ajaxSource'] = '';
            $options['dt_data'] = $this->getData();
        }

        $options['dt_processing'] = $this->isProcessing();
        $options['dt_displayLength'] = $this->getDisplayLength();
        $options['dt_tableId'] = $this->getName();
        $options['dt_columns'] = $this->columnBuilder->getColumns();
        $options['dt_customizeOptions'] = $this->getCustomizeOptions();
        $options['dt_defaultOrder'] = $this->getDefaultOrder();
        $options['dt_defaultColumnSearches'] = $this->getDefaultColumnSearches();
        $options['dt_stateSaving'] = $this->isStateSaving();
        $options['dt_clearStateEnabled'] = $this->isClearStateEnabled();

        $stateDuration = $this->getStateDuration();
        if (false !== $stateDuration) {
            $options['dt_stateDuration'] = $stateDuration;
        }

        // DatatableThemeInterface Twig variables

        if (null === $this->theme) {
            throw new Exception('The datatable needs a theme.');
        }

        $options['theme_name'] = $this->theme->getName();
        $options['theme_dom'] = $this->theme->getDom();
        $options['theme_jqueryUi'] = $this->theme->isJqueryUi();
        $options['theme_tableClasses'] = $this->theme->getTableClasses();
        $options['theme_formClasses'] = $this->theme->getFormClasses();
        $options['theme_formSubmitButtonClasses'] = $this->theme->getFormSubmitButtonClasses();
        $options['theme_pagination'] = $this->theme->getPagination();

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getName();

    /**
     * Get translator.
     *
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Get router.
     *
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Set theme.
     *
     * @param DatatableThemeInterface $theme
     *
     * @return $this
     */
    public function setTheme(DatatableThemeInterface $theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme.
     *
     * @return null|DatatableThemeInterface
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set ServerSide.
     *
     * @param boolean $serverSide
     *
     * @return $this
     */
    public function setServerSide($serverSide)
    {
        $this->serverSide = (boolean) $serverSide;

        return $this;
    }

    /**
     * Is ServerSide.
     *
     * @return boolean
     */
    public function isServerSide()
    {
        return $this->serverSide;
    }

    /**
     * Set data.
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set processing.
     *
     * @param boolean $processing
     *
     * @return $this
     */
    public function setProcessing($processing)
    {
        $this->processing = (boolean) $processing;

        return $this;
    }

    /**
     * Is processing.
     *
     * @return boolean
     */
    public function isProcessing()
    {
        return $this->processing;
    }

    /**
     * Set displayLength.
     *
     * @param int $displayLength
     *
     * @return $this
     */
    public function setDisplayLength($displayLength)
    {
        $this->displayLength = (int) $displayLength;

        return $this;
    }

    /**
     * Get displayLength.
     *
     * @return int
     */
    public function getDisplayLength()
    {
        return (int) $this->displayLength;
    }

    /**
     * Set ajaxSource.
     *
     * @param string $ajaxSource
     *
     * @return $this
     */
    public function setAjaxSource($ajaxSource)
    {
        $this->ajaxSource = $ajaxSource;

        return $this;
    }

    /**
     * Get ajaxSource.
     *
     * @return string
     */
    public function getAjaxSource()
    {
        return $this->ajaxSource;
    }

    /**
     * Set entity.
     *
     * @param string $entity
     *
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity.
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set customizeOptions.
     *
     * @param array $customizeOptions
     *
     * @return $this
     */
    public function setCustomizeOptions(array $customizeOptions)
    {
        $this->customizeOptions = $customizeOptions;

        return $this;
    }

    /**
     * Get customizeOptions.
     *
     * @return array
     */
    public function getCustomizeOptions()
    {
        return $this->customizeOptions;
    }

    public function getColumns()
    {
        return $this->columnBuilder->getColumns();
    }

    /**
     * Set the default column order
     * Order format is a 2D array of column number and direction
     * e.g. array(2 => 'desc', 3 => 'asc')
     *
     * @param array $order
     *
     * @return $this
     */
    public function setDefaultOrder(array $order)
    {
        $this->defaultOrder = $order;

        return $this;
    }

    /**
     * Get defaultOrder
     *
     * @return array (false when not set)
     */
    public function getDefaultOrder()
    {
        return $this->defaultOrder ?: false;
    }

    /**
     * Set the default column search values
     * Order format is a 2D array of column number / entity property and search term
     * e.g. array(2 => 'hello', 'name' => 'world')
     *
     * @param array $colSearch
     *
     * @return $this
     */
    public function setDefaultColumnSearches(array $searches)
    {
        $this->defaultColumnSearches = $searches;

        return $this;
    }

    /**
     * Get defaultColumnSearches
     *
     * @return array (false when not set)
     */
    public function getDefaultColumnSearches()
    {
        if (!is_array($this->defaultColumnSearches)) {
            return $this->defaultColumnSearches;
        }

        $colSearches = array();
        $columns = $this->columnBuilder->getColumns();

        for($i = 0; $i < count($columns); $i++) {
            if (array_key_exists($columns[$i]->getProperty(), $this->defaultColumnSearches)) {
                $colSearches[$i] = $this->defaultColumnSearches[$columns[$i]->getProperty()];
            } elseif (array_key_exists($i, $this->defaultColumnSearches)) {
                $colSearches[$i] = $this->defaultColumnSearches[$i];
            } else {
                $colSearches[$i] = false;
            }
        }

        return $colSearches;
    }

    /**
     * Set stateSaving.
     *
     * @param boolean $stateSaving
     *
     * @return $this
     */
    public function setStateSaving($stateSaving)
    {
        $this->stateSaving = (boolean) $stateSaving;

        return $this;
    }

    /**
     * Is stateSaving.
     *
     * @return boolean
     */
    public function isStateSaving()
    {
        return $this->stateSaving ?: false;
    }

    /**
     * Set stateDuration.
     *
     * @param $stateDuration
     *
     * @return $this
     */
    public function setStateDuration($stateDuration)
    {
        $this->stateDuration = (int) $stateDuration;

        return $this;
    }

    /**
     * Get stateDuration
     *
     * @return int, false if not set
     */
    public function getStateDuration()
    {
        return isset($this->stateDuration) ? $this->stateDuration : false;
    }

    /**
     * Set clearStateEnabled.
     *
     * @param boolean $clearStateEnabled
     *
     * @return $this
     */
    public function setClearStateEnabled($clearStateEnabled)
    {
        $this->clearStateEnabled = (boolean) $clearStateEnabled;

        return $this;
    }

    /**
     * Is clearStateEnabled.
     *
     * @return boolean
     */
    public function isClearStateEnabled()
    {
        return $this->clearStateEnabled;
    }

}
