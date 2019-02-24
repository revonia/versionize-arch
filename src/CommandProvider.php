<?php

namespace Revonia\VersionizeArch;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Revonia\VersionizeArch\Command\BuildVaRepoCommand;

class CommandProvider implements CommandProviderCapability
{

    /**
     * Retrieves an array of commands
     *
     * @return \Composer\Command\BaseCommand[]
     */
    public function getCommands()
    {
        return array(new BuildVaRepoCommand());
    }
}
