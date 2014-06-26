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
 * Class ArrayColumn
 *
 */
class ArrayColumn extends BaseColumn
{
    /**
     * Constructor.
     *
     * @param null|string $property An entity's property
     *
     * @throws Exception
     */
    public function __construct($property = null)
    {
        if (null == $property) {
            throw new Exception("The entity's property can not be null.");
        }

        parent::__construct($property);
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'array';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        parent::setDefaults();

        $property = $this->getProperty();

        // association delimiter found?
        if (strstr($property, '.') !== false) {
            $fieldsArray = explode('.', $property);
            $prev = array_slice($fieldsArray, count($fieldsArray) - 2, 1);
            $last = array_slice($fieldsArray, count($fieldsArray) - 1, 1);
            $this->setData($prev[0]);
            $this->setRender('"[, ].' . $last[0].'"');
        } else {
            throw new Exception('An association is expected.');
        }
    }
}