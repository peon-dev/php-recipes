<?php

declare(strict_types=1);

namespace Peon\PhpRecipes\Rector\Value;

final class RectorProcessCommandConfiguration
{
    /**
     * @param non-empty-array<string> $paths
     */
    public function __construct(
        public readonly array $paths,
        public readonly string|null $autoloadFile = null,
        public readonly string|null $config = null,
    ) {}
}
