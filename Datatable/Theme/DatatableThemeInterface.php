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
 * Interface DatatableThemeInterface
 */
interface DatatableThemeInterface
{
    /**
     * Get name of the theme.
     *
     * @return string
     */
    public function getName();

    /**
     * Get name of the twig template.
     *
     * @return string
     */
    public function getTemplate();

    /**
     * Get table classes.
     *
     * @return string
     */
    public function getTableClasses();

    /**
     * Set table classes.
     *
     * @param string $tableClasses
     *
     * @return self
     */
    public function setTableClasses($tableClasses);

    /**
     * Get form classes.
     *
     * @return string
     */
    public function getFormClasses();

    /**
     * Set form classes.
     *
     * @param string $formClasses
     *
     * @return self
     */
    public function setFormClasses($formClasses);

    /**
     * Set form submit button classes.
     *
     * @param string $formSubmitButtonClasses
     *
     * @return $this
     */
    public function setFormSubmitButtonClasses($formSubmitButtonClasses);

    /**
     * Get form submit button classes.
     *
     * @return string
     */
    public function getFormSubmitButtonClasses();

    /**
     * Get pagination type.
     *
     * @return string
     */
    public function getPagination();

    /**
     * Set pagination type.
     *
     * @param string $pagination
     *
     * @return self
     */
    public function setPagination($pagination);

    /**
     * Get Dom.
     *
     * @return mixed
     */
    public function getDom();

    /**
     * Set Dom.
     *
     * @param mixed $dom
     *
     * @return self
     */
    public function setDom($dom);
}