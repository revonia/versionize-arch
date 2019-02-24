<?php

namespace Revonia\VersionizeArch\Command;

use Composer\Command\BaseCommand;
use Composer\Package\Archiver\ArchiveManager;
use Composer\Package\CompletePackage;
use Composer\Package\PackageInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildVaRepoCommand extends BaseCommand
{
    /** @var ArchiveManager */
    protected $archiveManager;

    protected function configure()
    {
        $this->setName('build-va-repo')
            ->setDescription('Build all required packages as versionize archive(version in archive filename).')
            ->setDefinition(array(
                new InputOption('dir', null, InputOption::VALUE_OPTIONAL, 'Write the archive to this directory', 'va-repo'),
                new InputOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Format of the resulting archive: tar or zip', 'zip'),
                new InputOption('ignore-filters', false, InputOption::VALUE_NONE, 'Ignore filters when saving package'),
            ));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $composer = $this->getComposer();

        $this->archiveManager = $composer->getArchiveManager();
        $output->writeln('Download and archiving...');

        $format = $input->getOption('format');
        $dir = $input->getOption('dir');
        $ignoreFilters = $input->getOption('ignore-filters');

        $packages = $composer->getRepositoryManager()->getLocalRepository()->getPackages();

        //archiving...
        $result = array();
        foreach ($packages as $package) {
            if ($package instanceof CompletePackage) {
                $targetPath = $this->archive($package, $dir, $format, $ignoreFilters);
                if ($targetPath) {
                    $result[] = $targetPath;
                }
            }
        }
        if (empty($result)) {
            $output->writeln('<warning>No package archived.</warning>');
        } else {
            $output->writeln('<info>' . count($result) . ' package(s) archived.</info>');
        }
    }

    /**
     * @param PackageInterface $package
     * @param $targetDir
     * @param $format
     * @param $ignoreFilters
     * @return string
     */
    public function archive(PackageInterface $package, $targetDir, $format, $ignoreFilters)
    {
        $name = $package->getName();
        $version = $package->getPrettyVersion();
        $filename = str_replace('/', '-', $name) . '$$' . $version;

        //skip exists archive
        if (file_exists(realpath($targetDir) . '/' . $filename . '.' . $format)) {
            return false;
        }

        return $this->archiveManager->archive($package, $format, $targetDir, $filename, $ignoreFilters);
    }
}
