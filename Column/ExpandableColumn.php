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
 * Class ExpandableColumnColumn
 *
 */
class ExpandableColumn extends BaseColumn
{
    
    private $template;
    
    /**
     * Constructor.
     *
     * @param null|string $property An entity's property
     *
     * @throws Exception
     */
    public function __construct($property = null)
    {
        
        if (null != $property) {
            throw new Exception("The entity's property should be null.");
        }
        parent::__construct($property);
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'expandable';
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        if (array_key_exists('template', $options)) {
            $this->setTemplate($options['template']);
        }
        $options['class'] = 'expandable';
        parent::setOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        parent::setDefaults();
        
        $this->setSortable(false);
        $this->setSearchable(false);
        $this->setFilterable(false);
        $this->setTemplate('return "";');
        
    }
    
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
    
    public function getTemplate()
    {
        return $this->template;
    }
}
