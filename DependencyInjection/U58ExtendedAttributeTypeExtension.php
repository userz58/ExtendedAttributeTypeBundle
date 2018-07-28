<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class U58ExtendedAttributeTypeExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ .'/../Resources/config'));
        $loader->load('array_converters.yml');
        $loader->load('attribute_types.yml');
        $loader->load('completeness.yml');
        $loader->load('providers.yml');
        $loader->load('entities.yml');
        $loader->load('models.yml');
        $loader->load('updaters.yml');
        $loader->load('factories.yml');
        $loader->load('serializers_indexing.yml');
        $loader->load('serializers_standard.yml');
        $loader->load('serializers_storage.yml');
        $loader->load('serializers_flat.yml');
        $loader->load('comparators.yml');
        $loader->load('validators.yml');
        $loader->load('controllers.yml');
        //$loader->load('denormalizers.yml');
        //$loader->load('form_types.yml');
        //$loader->load('query_builders.yml');
        //$loader->load('datagrid/attribute_types.yml');
        //$loader->load('datagrid/filters.yml');

        //$yamlMappingFiles = $container->getParameter('validator.mapping.loader.yaml_files_loader.mapping_files');
        //$yamlMappingFiles[] = __DIR__.'/../Resources/config/validation.yml';
        //$container->setParameter('validator.mapping.loader.yaml_files_loader.mapping_files', $yamlMappingFiles);
    }
}
