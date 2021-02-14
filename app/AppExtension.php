<?php

declare(strict_types = 1);

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class AppExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator());

        $regexIterator = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(__DIR__ . '/config')
            ),
            '/\.yaml/i'
        );

        /** @var SplFileInfo $file */
        foreach ($regexIterator as $file)
        {
            $loader->load((string) $file);
        }
    }
}
