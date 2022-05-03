<?php

declare(strict_types=1);

namespace Peon\Domain\Tools\Rector;

use Peon\Domain\Job\Job;
use Peon\Domain\Job\Value\JobId;
use Peon\Domain\Process\Exception\ProcessFailed;
use Peon\Domain\Process\ExecuteCommand;
use Peon\Domain\Tools\Rector\Value\RectorProcessCommandConfiguration;

class Rector
{
    public const BINARY_EXECUTABLE = '/peon/vendor-bin/rector/vendor/rector/rector/bin/rector'; // TODO must be dynamic, for non-standard installations

    public function getProcessCommand(RectorProcessCommandConfiguration $configuration): string
    {
        $command = sprintf('%s process', realpath(self::BINARY_EXECUTABLE));

        if ($configuration->autoloadFile !== null) {
            $command .= ' --autoload-file=' . (file_exists($configuration->autoloadFile) ? realpath($configuration->autoloadFile) : $configuration->autoloadFile);
        }

        if ($configuration->workingDirectory) {
            $command .= ' --working-dir=' . (file_exists($configuration->workingDirectory) ? realpath($configuration->workingDirectory) : $configuration->workingDirectory);
        }

        if ($configuration->config !== null) {
            $command .= ' --config=' . (file_exists($configuration->config) ? realpath($configuration->config) : $configuration->config);
        }

        if ($configuration->paths) {
            $command .= ' ' . implode(' ', $configuration->paths);
        }

        return $command;
    }
}
