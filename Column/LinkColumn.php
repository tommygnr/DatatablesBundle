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

use Exception;

/**
 * Class ActionColumn
 *
 */
class LinkColumn extends BaseColumn
{
    /**
     * The action route.
     *
     * @var null|string
     */
    private $route;

    /**
     * The action route parameters.
     *
     * @var array
     */
    private $routeParameters;

    /**
     * HTML attributes.
     *
     * @var array
     */
    private $attributes;

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'link';
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);

        if (array_key_exists('route', $options)) {
            $this->setRoute($options['route']);
        }
        if (array_key_exists('parameters', $options)) {
            $this->setRouteParameters($options['parameters']);
        }
        if (array_key_exists('attributes', $options)) {
            $this->setAttributes($options['attributes']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        parent::setDefaults();

        $this->setSearchable(false);
        $this->setSortable(false);

        $this->setRoute(null);
        $this->setRouteParameters(array());
        $this->setAttributes(array());
    }

    /**
     * Set route.
     *
     * @param null|string $route
     *
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route.
     *
     * @return null|string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set route parameters.
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function setRouteParameters(array $parameters)
    {
        $this->routeParameters = $parameters;

        return $this;
    }

    /**
     * Get route parameters.
     *
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * Set attributes.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}