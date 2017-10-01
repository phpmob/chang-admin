<?php

/*
 * This file is part of the PhpMob package.
 *
 * (c) Ishmael Doss <nukboon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpMob\SettingsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @author Ishmael Doss <nukboon@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('phpmob_settings');

        $rootNode
            ->children()
                ->scalarNode('template')
                    ->defaultValue('@PhpMobSettings/default.html.twig')
                ->end()
                ->arrayNode('cache')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('service')->defaultNull()->end()
                        ->integerNode('lifetime')->defaultValue(3600)->end()
                    ->end()
                ->end()
                ->arrayNode('schemas')
                    ->useAttributeAsKey('name')
                    // section
                    ->prototype('array')
                        ->children()
                            ->scalarNode('enabled')->defaultTrue()->end()
                            ->scalarNode('ownered')->defaultFalse()->end()
                            ->scalarNode('label')->defaultNull()->end()
                            ->arrayNode('settings')
                                // setting key
                                ->requiresAtLeastOneElement()
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->isRequired()
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('enabled')->defaultTrue()->end()
                                        ->scalarNode('type')->defaultValue('default')->end()
                                        ->scalarNode('value')->defaultNull()->end()
                                        ->scalarNode('label')->defaultNull()->end()
                                        ->arrayNode('blueprint')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('type')->defaultValue(TextType::class)->end()
                                                ->variableNode('options')
                                                    ->info('The options given to form builder.')
                                                    ->defaultValue([])
                                                    ->validate()
                                                        ->always(function ($v) {
                                                            if (!is_array($v)) {
                                                                throw new InvalidTypeException();
                                                            }

                                                            return $v;
                                                        })
                                                    ->end()
                                                ->end()
                                                ->variableNode('constraints')
                                                    ->info('Use constraits found in Symfony\Component\Validator\Constraints')
                                                    ->defaultValue([])
                                                    ->validate()
                                                        ->always(function ($v) {
                                                            if (!is_array($v)) {
                                                                throw new InvalidTypeException();
                                                            }

                                                            return $v;
                                                        })
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
