<?php

namespace Revonia\VersionizeArch;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;
use Revonia\VersionizeArch\Repository\VaRepository;

class Plugin implements PluginInterface, Capable, EventSubscriberInterface
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @inheritdoc
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->io = $io;
        $this->composer = $composer;
    }

    /**
     * @inheritdoc
     */
    public function getCapabilities()
    {
        return array(
            CommandProviderCapability::class => CommandProvider::class,
        );
    }

    public function changeRepo()
    {
        $rm = $this->composer->getRepositoryManager();
        $rm->setRepositoryClass('va', 'Revonia\VersionizeArch\Repository\VaRepository');

        $pushed = [];

        foreach ($rm->getRepositories() as $repository) {
            if ($repository instanceof VaRepository) {
                $config = $repository->getRepoConfig();
                $pushed[$config['url']] = true;
            }
        }

        foreach ($this->getExtraRepositories() as $repository) {
            if (isset($repository['type']) && $repository['type'] === 'va') {
                if (isset($pushed[$repository['url']])) {
                    continue;
                }
                $repo = $rm->createRepository('va', $repository);
                $rm->addRepository($repo);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            PluginEvents::PRE_COMMAND_RUN => array(
                array('changeRepo', 0)
            ),
            PluginEvents::COMMAND => array(
                array('changeRepo', 0)
            ),
        );
    }

    protected function getExtraRepositories()
    {
        $extra = $this->composer->getPackage()->getExtra();
        if (!isset($extra['extra-repositories'])) {
            return [];
        }

        return $extra['extra-repositories'];
    }
}
