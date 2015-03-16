<?php

namespace Humbug\Adapter\Phpunit;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class PhpunitExecutableFinder
{
    /**
     * @return string
     */
    public function find()
    {
        $process = new Process('composer config bin-dir');
        $process->run();

        $path = trim($process->getOutput());

        putenv('PATH=' . $path . PATH_SEPARATOR . getenv('PATH'));

        $execFinder = new ExecutableFinder();

        $found = $execFinder->find('phpunit');

        return $found;
    }
} 