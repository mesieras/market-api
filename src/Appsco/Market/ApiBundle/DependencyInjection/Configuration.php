<?php

namespace Appsco\Market\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('appsco_accounts_api');

        $root->children()
            ->arrayNode('private_key')
                ->children()
                    ->scalarNode('id')->end()
                    ->scalarNode('file')->end()
                ->end()
            ->end()
            ->scalarNode('client_id')->defaultValue('')->cannotBeEmpty()->end()
            ->scalarNode('market_url')
                ->defaultValue('https://market.dev.appsco.com/customer/order/receive')
                ->cannotBeEmpty()
            ->end()
            ->arrayNode('notification')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('appsco')->defaultValue(false)->end()
                    ->arrayNode('validators')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }

}
    {

} 