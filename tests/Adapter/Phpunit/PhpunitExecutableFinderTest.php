<?php

namespace Humbug\Test\Adapter\Phpunit;

use Humbug\Adapter\Phpunit\PhpunitExecutableFinder;

class PhpunitExecutableFinderTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldFindPhpunitInComposerBinDir()
    {
        $finder = new PhpunitExecutableFinder();

        $pathToPhpunitExecutable = $finder->find();

        $this->assertEquals('vendor/bin/phpunit', $pathToPhpunitExecutable);
    }
} 