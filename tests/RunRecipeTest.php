<?php
declare(strict_types=1);

namespace Peon\PhpRecipes\Tests;

use Nette\Utils\FileSystem;
use Peon\PhpRecipes\Recipe\Recipe;
use Peon\PhpRecipes\Rector\Rector;
use Peon\PhpRecipes\RunRecipe;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class RunRecipeTest extends TestCase
{
    /**
     * @dataProvider provideRecipeNames
     */
    public function testCodeIsChangedAsExpected(Recipe $recipe): void
    {
        $testingApplication = TestingApplication::init();
        $testingApplication->dumpComposerAutoload();

        $runRecipe = new RunRecipe(
            new Rector(),
        );

        $runRecipe->run($recipe->value, $testingApplication->directory, ['src'], 10);

        $expectationFileContent = FileSystem::read(__DIR__ . '/RecipesExpectedChanges/' . $recipe->value . '.xml');

        $xml = new \SimpleXMLElement($expectationFileContent);
        self::assertNotEmpty($xml);
        foreach ($xml->expectation as $expectation) {
            $process = Process::fromShellCommandline((string) $expectation->command, $testingApplication->directory);
            $process->mustRun();
            self::assertSame((string) $expectation->output, rtrim($process->getOutput()));
        }
    }


    /**
     * @return \Generator<array{\Peon\PhpRecipes\Recipe\Recipe}>
     */
    public static function provideRecipeNames(): \Generator
    {
        foreach (Recipe::cases() as $recipeName) {
            yield $recipeName->value => [
                $recipeName
            ];
        }
    }
}
