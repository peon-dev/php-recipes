<?php

declare(strict_types=1);

namespace Peon\PhpRecipes\Tests;

use Nette\InvalidStateException;
use Nette\Utils\FileSystem;
use Nette\Utils\Random;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

final class TestingApplication
{
    public readonly string $directory;


    public function __construct()
    {
        $targetDirectory = __DIR__ . '/../tests/var/application_' . Random::generate();

        $this->directory = $targetDirectory;
    }


    /**
     * @throws ProcessFailedException
     * @throws InvalidStateException
     */
    public static function init(): self
    {
        $repository = new self();

        register_shutdown_function(static function() use ($repository) {
            FileSystem::delete($repository->directory);
        });

        FileSystem::copy(__DIR__ . '/ExampleApplication', $repository->directory);

        Process::fromShellCommandline(sprintf('git init --initial-branch "%s"', 'main'), $repository->directory)->mustRun();
        Process::fromShellCommandline(sprintf('git config user.name %s', 'Peon tester'), $repository->directory)->mustRun();
        Process::fromShellCommandline(sprintf('git config user.email %s', 'tester@peon.dev'), $repository->directory)->mustRun();

        Process::fromShellCommandline('git add .',  $repository->directory)->mustRun();
        Process::fromShellCommandline('git commit --all --message "Init"', $repository->directory)->mustRun();

        return $repository;
    }


    /**
     * @throws ProcessFailedException
     */
    public function dumpComposerAutoload(): void
    {
        Process::fromShellCommandline('composer dump-autoload', $this->directory)->mustRun();
    }
}
