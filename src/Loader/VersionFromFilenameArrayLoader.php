<?php

namespace Revonia\VersionizeArch\Loader;


use Composer\Package\Loader\ArrayLoader;

class VersionFromFilenameArrayLoader extends ArrayLoader
{
    public function load(array $config, $class = 'Composer\Package\CompletePackage')
    {
        if (!isset($config['version'])) {
            $pathParts = pathinfo($config['dist']['url']);
            $parts = explode('$$', $pathParts['filename']);

            if (isset($parts[1])) {
                $config['version'] = $parts[1];
            }
        }

        return parent::load($config, $class);
    }
}
