<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/services.yaml');

        if (\PHP_SAPI === 'cli') {
            $loader->load($confDir.'/services_cli.yaml');
        } else {
            $loader->load($confDir.'/services_http.yaml');
        }
    }
}
