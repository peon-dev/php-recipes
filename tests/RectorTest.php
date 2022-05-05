<?php
declare(strict_types=1);

namespace Peon\PhpRecipes\Tests;

use Peon\PhpRecipes\Rector\Rector;
use Peon\PhpRecipes\Rector\Value\RectorProcessCommandConfiguration;
use PHPUnit\Framework\TestCase;

class RectorTest extends TestCase
{
    /**
     * @dataProvider provideTestGetProcessCommand
     */
    public function testGetProcessCommand(RectorProcessCommandConfiguration $commandConfiguration, string $expectedCommand): void
    {
        $rector = new Rector();

        $command = $rector->getProcessCommand($commandConfiguration);

        self::assertSame($expectedCommand, $command);
    }


    /**
     * @return \Generator<array{RectorProcessCommandConfiguration, string}>
     */
    public function provideTestGetProcessCommand(): \Generator
    {
        yield [
            new RectorProcessCommandConfiguration(['src']),
            realpath(Rector::BINARY_EXECUTABLE) . ' process src',
        ];

        yield [
            new RectorProcessCommandConfiguration(['src'], autoloadFile: 'autoload.php'),
            realpath(Rector::BINARY_EXECUTABLE) . ' process --autoload-file=autoload.php src',
        ];

        yield [
            new RectorProcessCommandConfiguration(['src'], config: 'config.php'),
            realpath(Rector::BINARY_EXECUTABLE) . ' process --config=config.php src',
        ];

        yield [
            new RectorProcessCommandConfiguration(paths: ['src', 'app']),
            realpath(Rector::BINARY_EXECUTABLE) . ' process src app',
        ];

        yield [
            new RectorProcessCommandConfiguration(['src', 'app'], 'autoload.php', 'project/config.php'),
            realpath(Rector::BINARY_EXECUTABLE) . ' process --autoload-file=autoload.php --config=project/config.php src app',
        ];
    }
}
