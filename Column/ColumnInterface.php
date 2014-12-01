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
 * Class ColumnInterface
 *
 */
interface ColumnInterface
{
    /**
     * Get property.
     *
     * @return null|string
     */
    public function getProperty();

    /**
     * Set Data.
     *
     * @param string $data
     *
     * @return self
     */
    public function setData($data);

    /**
     * Get Data.
     *
     * @return string
     */
    public function getData();

    /**
     * Set Searchable.
     *
     * @param boolean $searchable
     *
     * @return self
     */
    public function setSearchable($searchable);

    /**
     * Is searchable.
     *
     * @return boolean
     */
    public function isSearchable();

    /**
     * Set Filterable.
     *
     * @param boolean $filterable
     *
     * @return self
     */
    public function setFilterable($filterable);

    /**
     * Is Filterable.
     *
     * @return boolean
     */
    public function isFilterable();

    /**
     * Set Sortable.
     *
     * @param boolean $sortable
     *
     * @return self
     */
    public function setSortable($sortable);

    /**
     * Is Sortable.
     *
     * @return boolean
     */
    public function isSortable();

    /**
     * Set Visible.
     *
     * @param boolean $visible
     *
     * @return self
     */
    public function setVisible($visible);

    /**
     * Is Visible.
     *
     * @return boolean
     */
    public function isVisible();

    /**
     * Set Title.
     *
     * @param null|string $title
     *
     * @return self
     */
    public function setTitle($title);

    /**
     * Get Title.
     *
     * @return null|string
     */
    public function getTitle();

    /**
     * Set Render.
     *
     * @param null|string $render
     *
     * @return self
     */
    public function setRender($render);

    /**
     * Get Render.
     *
     * @return null|string
     */
    public function getRender();

    /**
     * Set Class.
     *
     * @param string $class
     *
     * @return self
     */
    public function setClass($class);

    /**
     * Get sClass.
     *
     * @return string
     */
    public function getClass();

    /**
     * Set DefaultContent.
     *
     * @param null|string $defaultContent
     *
     * @return self
     */
    public function setDefaultContent($defaultContent);

    /**
     * Get DefaultContent.
     *
     * @return null|string
     */
    public function getDefaultContent();

    /**
     * Set Width.
     *
     * @param null|string $width
     *
     * @return self
     */
    public function setWidth($width);

    /**
     * Get Width.
     *
     * @return null|string
     */
    public function getWidth();

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return self
     */
    public function setOptions(array $options);

    /**
     * Set default values.
     *
     * @return self
     */
    public function setDefaults();

    /**
     * Returns the name of the column class.
     *
     * @return string
     */
    public function getClassName();
}
