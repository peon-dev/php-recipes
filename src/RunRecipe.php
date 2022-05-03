<?php

declare(strict_types=1);

namespace Peon\PhpRecipes;

use Peon\PhpRecipes\Rector\Rector;
use Peon\PhpRecipes\Rector\Value\RectorProcessCommandConfiguration;
use Symfony\Component\Process\Process;

final class RunRecipe
{
    public function __construct(
        private Rector $rector,
    ) {}


    public function run(string $recipe): void
    {
        $configuration = new RectorProcessCommandConfiguration();

        $command = $this->rector->getProcessCommand($configuration);

        $process = Process::fromShellCommandline($command);

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
