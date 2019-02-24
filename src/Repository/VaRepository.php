<?php

namespace Revonia\VersionizeArch\Repository;

use Composer\IO\IOInterface;
use Composer\Repository\ArtifactRepository;
use Revonia\VersionizeArch\Loader\VersionFromFilenameArrayLoader;

class VaRepository extends ArtifactRepository
{
    public function __construct(array $repoConfig, IOInterface $io)
    {
        parent::__construct($repoConfig, $io);
        $this->loader = new VersionFromFilenameArrayLoader();
    }
}
