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


    public function run(string $recipe, string $applicationDirectory): void
    {
        $configuration = new RectorProcessCommandConfiguration(
            config: __DIR__ . '/../rector-config/' . $recipe . '.php',
        );

        $command = $this->rector->getProcessCommand($configuration);

        $process = Process::fromShellCommandline($command, $applicationDirectory);

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
