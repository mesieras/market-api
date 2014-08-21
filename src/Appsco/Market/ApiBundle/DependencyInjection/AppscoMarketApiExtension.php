<?php

namespace Appsco\Market\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class AppscoMarketApiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->buildPrivateKeyProvider($config, $container);
        $this->buildNotification($config, $container);

        $container->setParameter('appsco_market_api.client_id', $config['client_id']);
        $container->setParameter('appsco_market_api.market_url', $config['market_url']);
    }


    protected function buildPrivateKeyProvider(array $config, ContainerBuilder $container)
    {
        if (@$config['private_key']['id']) {
            $id = $config['private_key']['id'];
        } else if (@$config['private_key']['file']) {
            $fileProvider = new DefinitionDecorator('appsco_market_api.private_key_provider.file.abstract');
            $fileProvider->addMethodCall('setFilename', array($config['private_key']['file']));
            $id = 'appsco_market_api.private_key_provider.file';
            $container->setDefinition($id, $fileProvider);
        } else {
            $id = 'appsco_market_api.private_key_provider.null';
        }

        $clientService = $container->getDefinition('appsco_market_api.client');
        $clientService->replaceArgument(1, new Reference($id));
    }


    protected function buildNotification(array $config, ContainerBuilder $container)
    {
        $validator = $container->getDefinition('appsco_market_api.notification.validator');

        if (@$config['notification']['appsco']) {
            $container->setDefinition(
                'appsco_market_api.notification.validator.certificate',
                new DefinitionDecorator('appsco_market_api.notification.validator.certificate.abstract')
            );
            $validator->addMethodCall(
                'addValidator',
                array(new Reference('appsco_market_api.notification.validator.certificate'))
            );
        }

        if (@$config['notification']['validators']) {
            foreach ($config['notification']['validators'] as $validatorServiceId) {
                $validator->addMethodCall(
                    'addValidator',
                    array(new Reference($validatorServiceId))
                );
            }
        }

        if ($container->hasDefinition('appsco_market_api.notification.validator.issuer')) {
            $issuerValidator = $container->getDefinition('appsco_market_api.notification.validator.issuer');
            if (@$config['notification']['issuers']) {
                foreach ($config['notification']['issuers'] as $issuer) {
                    $issuerValidator->addMethodCall('addValidIssuer', array($issuer));
                }
            }
        }
    }

}