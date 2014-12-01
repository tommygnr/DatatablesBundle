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

use Exception;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Class ColumnFactory
 *
 */
class ColumnFactory implements ColumnFactoryInterface
{
    /**
     * A column.
     *
     * @var ColumnInterface
     */
    private $column = null;

    /**
     * {@inheritdoc}
     */
    public function createColumnByName($property, $name)
    {
        if (!is_string($property)) {
            if (!is_null($property)) {
                throw new UnexpectedTypeException($property, 'A string or null expected.');
            }
        }

        if (!is_string($name)) {
            throw new UnexpectedTypeException($name, 'A string is expected.');
        }

        $name = strtolower($name);

        switch ($name) {
            case 'action':
                $this->column = new ActionColumn($property);
                break;
            case 'array':
                $this->column = new ArrayColumn($property);
                break;
            case 'boolean':
                $this->column = new BooleanColumn($property);
                break;
            case 'column':
                $this->column = new Column($property);
                break;
            case 'datetime':
                $this->column = new DateTimeColumn($property);
                break;
            case 'timeago':
                $this->column = new TimeagoColumn($property);
                break;
            case 'link':
                $this->column = new LinkColumn($property);
                break;
            case 'multiselect':
                $this->column = new MultiSelectColumn($property);
                break;
            default:
                throw new Exception("The {$name} column is not supported.");
        }

        $this->column->setDefaults();

        return $this->column;
    }
}
