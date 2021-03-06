<?php

/**
 * Humbug
 *
 * @category   Humbug
 * @package    Humbug
 * @copyright  Copyright (c) 2015 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    https://github.com/padraic/humbug/blob/master/LICENSE New BSD License
 *
 * @author     rafal.wartalski@gmail.com
 */

namespace Humbug;

use Humbug\Adapter\AdapterAbstract;
use Symfony\Component\Process\PhpProcess;
use Symfony\Component\Process\Process;

class ProcessRunner
{
    /**
     * @param PhpProcess $process
     * @param AdapterAbstract $testFrameworkAdapter
     * @param callable $onProgressCallback
     *
     * @return bool
     */
    public function run(
        PhpProcess $process,
        AdapterAbstract $testFrameworkAdapter,
        \Closure $onProgressCallback = null
    ) {
        $hasFailure = false;

        $process->run(function ($out, $data) use (
                $process,
                $testFrameworkAdapter,
                $onProgressCallback,
                &$hasFailure
        ) {
            if ($out == Process::ERR) {
                $hasFailure= true;
                $process->stop();
                return;
            }

            if ($hasFailure) {
                return;
            }

            if (!$testFrameworkAdapter->ok($data)) {
                $hasFailure = true;
                $process->stop();
                return;
            }

            $oksCount = $testFrameworkAdapter->hasOks($data);

            if ($oksCount !== false && $onProgressCallback !== null) {
                $onProgressCallback($oksCount);
            }
        });

        $process->stop();

        return $hasFailure;
    }
}
