<?php

declare(strict_types=1);

namespace Peon\Tests;

use Nette\InvalidStateException;
use Nette\Utils\FileSystem;
use Nette\Utils\Random;
use Nyholm\Psr7\Uri;
use Psr\Http\Message\UriInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

final class TestingRemoteGitRepository
{
    public const MAIN_BRANCH = 'main';

    public readonly UriInterface $uri;
    public readonly string $directory;


    public function __construct()
    {
        $targetDirectory = __DIR__ . '/../var/git_repositories/integration_test_fork_' . Random::generate();

        $this->directory = $targetDirectory;
        $this->uri = new Uri($this->directory);
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

        FileSystem::copy(__DIR__ . '/GitRepository', $repository->directory);

        Process::fromShellCommandline(sprintf('git init --initial-branch "%s"', self::MAIN_BRANCH), $repository->directory)->mustRun();
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


    /**
     * @throws ProcessFailedException
     */
    public function makeBranchBehindMain(string $branch): void
    {
        Process::fromShellCommandline(sprintf('git checkout %s', self::MAIN_BRANCH), $this->directory)->mustRun();

        $fileName = $this->directory . '/' . 'random_file_' . Random::generate();
        FileSystem::write($fileName, 'Hi, im testing Peon!');

        Process::fromShellCommandline('git add .', $this->directory)->mustRun();
        Process::fromShellCommandline('git commit --all --message "Random file"', $this->directory)->mustRun();

        Process::fromShellCommandline(sprintf('git branch --force %s HEAD~1', $branch), $this->directory)->mustRun();
    }


    /**
     * @throws ProcessFailedException
     */
    public function makeBranchConflictAgainstMain(string $branch): void
    {
        Process::fromShellCommandline(sprintf('git checkout %s', self::MAIN_BRANCH), $this->directory)->mustRun();

        $fileName = $this->directory . '/' . 'random_file_' . Random::generate();
        FileSystem::write($fileName, 'Hi, im testing Peon! With random seed: ' . Random::generate());

        Process::fromShellCommandline('git add .', $this->directory)->mustRun();
        Process::fromShellCommandline('git commit --all --message "Random file"', $this->directory)->mustRun();

        Process::fromShellCommandline(sprintf('git branch --force %s HEAD~1', $branch), $this->directory)->mustRun();
        Process::fromShellCommandline(sprintf('git checkout %s', $branch), $this->directory)->mustRun();

        FileSystem::write($fileName, 'Hi, im testing Peon! With random seed: ' . Random::generate());

        Process::fromShellCommandline('git add .', $this->directory)->mustRun();
        Process::fromShellCommandline('git commit --all --message "Random file the other change"', $this->directory)->mustRun();

        Process::fromShellCommandline(sprintf('git checkout %s', self::MAIN_BRANCH), $this->directory)->mustRun();
    }
}
