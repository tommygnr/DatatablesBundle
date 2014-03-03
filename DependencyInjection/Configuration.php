<?php

/**
 * This file is part of the TommyGNRDatatablesBundle package.
 *
 * (c) Tom Corrigan <https://github.com/tommygnr/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TommyGNR\DatatablesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package TommyGNR\DatatablesBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('tommygnr_datatables');

        $rootNode
            ->children()
                ->arrayNode('default_layout')->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('display_length')
                            ->defaultValue(10)
                            ->min(1)
                        ->end()
                        ->booleanNode('server_side')->defaultTrue()->end()
                        ->booleanNode('processing')->defaultTrue()->end()
                        ->booleanNode('multiselect')->defaultFalse()->end()
                        ->booleanNode('individual_filtering')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
