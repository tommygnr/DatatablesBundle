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
 * Class AbstractColumn
 *
 */
abstract class AbstractColumn implements ColumnInterface, \JsonSerializable
{
    /**
     * An entity's property.
     *
     * @var null|string
     */
    private $property;

    /**
     * Used to read data from any JSON data source property.
     *
     * @var mixed
     */
    private $data;

    /**
     * Enable or disable global searching on the data in this column.
     *
     * @var boolean
     */
    private $searchable;

    /**
     * Enable or disable filtering on the data in this column.
     *
     * @var boolean
     */
    private $filterable;

    /**
     * Should filter options be pre seeded
     *
     * @var boolean
     */
    private $filterSeeded;

    /**
     * Should filter options be pre seeded
     *
     * @var array
     */
    private $filterOptions;

    /**
     * Enable or disable sorting on this column.
     *
     * @var boolean
     */
    private $sortable;

    /**
     * Enable or disable the display of this column.
     *
     * @var boolean
     */
    private $visible;

    /**
     * The title of this column.
     *
     * @var null|string
     */
    private $title;

    /**
     * This property is the rendering partner to data
     * and it is suggested that when you want to manipulate data for display.
     *
     * @var null|mixed
     */
    private $render;

    /**
     * Class to give to each cell in this column.
     *
     * @var string
     */
    private $class;

    /**
     * Allows a default value to be given for a column's data,
     * and will be used whenever a null data source is encountered.
     * This can be because data is set to null, or because the data
     * source itself is null.
     *
     * @var null|string
     */
    private $defaultContent;

    /**
     * Defining the width of the column.
     * This parameter may take any CSS value (em, px etc).
     *
     * @var null|string
     */
    private $width;

    /**
     * Custom mapping function for display a value in the filterSeeded dropdown
     *
     */
     private $seedMapFunction;

    /**
     * Constructor.
     *
     * @param null|string $property An entity's property
     */
    public function __construct($property = null)
    {
        $this->property = $property;
    }

    public function jsonSerialize()
    {
        return [
            'data' => $this->getData(),
            'className' => $this->getClassName(),
            'searchable' => $this->isSearchable(),
            'visible' => $this->isVisible(),
            'title' => $this->getTitle(),
            'class' => $this->getClass(),
            'defaultContent' => $this->getDefaultContent(),
            'width' => $this->getWidth(),
            'render' => $this->getRender(),
            'seedMapFn' => $this->getSeedMapFunction(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isSearchable()
    {
        return $this->searchable;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilterable($filterable)
    {
        $this->filterable = $filterable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isFilterable()
    {
        return $this->filterable;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilterSeeded($filterSeeded)
    {
        $this->filterSeeded = $filterSeeded;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isFilterSeeded()
    {
        return $this->filterable && $this->filterSeeded;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilterOptions(array $filterOptions)
    {
        $this->filterOptions = $filterOptions;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterOptions()
    {
        return $this->filterOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function setSortable($sortable)
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isSortable()
    {
        return $this->sortable;
    }

    /**
     * {@inheritdoc}
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setRender($render)
    {
        $this->render = $render;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRender()
    {
        return $this->render;
    }

    /**
     * {@inheritdoc}
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultContent($defaultContent)
    {
        $this->defaultContent = $defaultContent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultContent()
    {
        return $this->defaultContent;
    }

    /**
     * {@inheritdoc}
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * {@inheritdoc}
     */
    public function setSeedMapFunction($seedMapFunction)
    {
        $this->seedMapFunction = $seedMapFunction;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSeedMapFunction()
    {
        return $this->seedMapFunction;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        if (array_key_exists('searchable', $options)) {
            $this->setSearchable($options['searchable']);
        }
        if (array_key_exists('sortable', $options)) {
            $this->setSortable($options['sortable']);
        }
        if (array_key_exists('filterable', $options)) {
            $this->setFilterable($options['filterable']);
        }
        if (array_key_exists('filterSeeded', $options)) {
            $this->setFilterSeeded($options['filterSeeded']);
        }
        if (array_key_exists('filterOptions', $options)) {
            $this->setFilterOptions($options['filterOptions']);
        }
        if (array_key_exists('visible', $options)) {
            $this->setVisible($options['visible']);
        }
        if (array_key_exists('title', $options)) {
            $this->setTitle($options['title']);
        }
        if (array_key_exists('render', $options)) {
            $this->setRender($options['render']);
        }
        if (array_key_exists('class', $options)) {
            $this->setClass($options['class']);
        }
        if (array_key_exists('default', $options)) {
            $this->setDefaultContent($options['default']);
        }
        if (array_key_exists('width', $options)) {
            $this->setWidth($options['width']);
        }
        if (array_key_exists('seedMapFunction', $options)) {
            $this->setSeedMapFunction($options['seedMapFunction']);
        }

        return $this;
    }

    public function filterProcess($qb, $field, $alias, $value) {
      $qb->setParameter($alias, "%".$value."%");
      return $qb->expr()->like($field, "?$alias");
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        $this->setData($this->property);
        $this->setSearchable(true);
        $this->setFilterable(false);
        $this->setSortable(true);
        $this->setVisible(true);
        $this->setTitle(null);
        $this->setRender(null);
        $this->setClass('');
        $this->setDefaultContent(null);
        $this->setWidth(null);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getClassName();
}
