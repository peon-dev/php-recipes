<?php

declare(strict_types=1);

namespace Peon\PhpRecipes\Rector;

use Peon\PhpRecipes\Rector\Value\RectorProcessCommandConfiguration;

class Rector
{
    public const BINARY_EXECUTABLE = __DIR__ . '/../../vendor/bin/rector';

    public function getProcessCommand(RectorProcessCommandConfiguration $configuration): string
    {
        $command = sprintf('%s process', realpath(self::BINARY_EXECUTABLE));

        if ($configuration->autoloadFile !== null) {
            $command .= ' --autoload-file=' . (file_exists($configuration->autoloadFile) ? realpath($configuration->autoloadFile) : $configuration->autoloadFile);
        }

        if ($configuration->config !== null) {
            $command .= ' --config=' . (file_exists($configuration->config) ? realpath($configuration->config) : $configuration->config);
        }

        $command .= ' ' . implode(' ', $configuration->paths);

        return $command;
    }
}
