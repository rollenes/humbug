<?php

namespace Humbug;

/**
 * Class collecting all mutants and their results.
 *
 * @category   Humbug
 * @package    Humbug
 * @copyright  Copyright (c) 2015 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    https://github.com/padraic/humbug/blob/master/LICENSE New BSD License
 * @author     Thibaud Fabre
 */
class Collector
{
    /**
     * @var int Count of mutants not covered by a test case.
     */
    private $shadowCount = 0;

    /**
     * @var Mutant[] Mutants killed by a test case.
     */
    private $killed = [];

    /**
     * @var Mutant[] Mutants that resulted in a timeout.
     */
    private $timeouts = [];

    /**
     * @var Mutant[] Mutants that triggered an error.
     */
    private $errors = [];

    /**
     * @var Mutant[] Mutants that escaped tests.
     */
    private $escaped = [];

    /**
     * Collects a shadow mutant.
     */
    public function collectShadow()
    {
        $this->shadowCount++;
    }

    /**
     * @param Mutant $mutant
     */
    public function collectEscaped(Mutant $mutant)
    {
        $this->escaped[] = $mutant;
    }

    /**
     * @param Mutant $mutant
     */
    public function collectKilled(Mutant $mutant)
    {
        $this->killed[] = $mutant;
    }

    /**
     * @param Mutant $mutant
     */
    public function collectError(Mutant $mutant)
    {
        $this->errors[] = $mutant;
    }

    /**
     * @param Mutant $mutant
     */
    public function collectTimeout(Mutant $mutant)
    {
        $this->timeouts[] = $mutant;
    }

    /**
     * @return int Total count of collected mutants.
     */
    public function getTotalCount()
    {
        return
            $this->getErrorCount() +
            $this->getEscapeCount() +
            $this->getKilledCount() +
            $this->getShadowCount() +
            $this->getTimeoutCount();
    }

    /**
     * @return int Measurable count of mutants.
     */
    public function getMeasurableTotal()
    {
        return $this->getTotalCount() - $this->getShadowCount();
    }

    /**
     * @return int Count of mutants that were covered by a test.
     */
    public function getVanquishedTotal()
    {
        return
            $this->getKilledCount() +
            $this->getTimeoutCount() +
            $this->getErrorCount();
    }

    /**
     * @return int Count of mutants that were not covered by a test
     */
    public function getShadowCount()
    {
        return $this->shadowCount;
    }

    /**
     * @return int Count of mutants successfully killed by tests.
     */
    public function getKilledCount()
    {
        return count($this->killed);
    }

    /**
     * @return Mutant[] List of mutants successfully killed by tests.
     */
    public function getKilled()
    {
        return $this->killed;
    }

    /**
     * @return int Count of mutants that resulted in a timeout.
     */
    public function getTimeoutCount()
    {
        return count($this->timeouts);
    }

    /**
     * @return Mutant[] List of mutants that resulted in a timeout.
     */
    public function getTimeouts()
    {
        return $this->timeouts;
    }

    /**
     * @return int Count of mutants that resulted in an error.
     */
    public function getErrorCount()
    {
        return count($this->errors);
    }

    /**
     * @return Mutant[] List of mutants that triggered an error.
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return int Count of mutants that escaped test cases.
     */
    public function getEscapeCount()
    {
        return count($this->escaped);
    }

    /**
     * @return Mutant[] List of mutants that escaped test cases.
     */
    public function getEscaped()
    {
        return $this->escaped;
    }

    /**
     * Returns all collected mutants as arrays, grouped by their result status.
     *
     * @todo Move it from here to better place
     *
     * @return array
     */
    public function toGroupedMutantArray()
    {
        return [
            'escaped' => $this->createGroup($this->escaped),
            'errored' => $this->createGroup($this->errors),
            'timeouts' => $this->createGroup($this->timeouts),
            'killed' => $this->createGroup($this->killed)
        ];
    }

    private function createGroup(array $mutants)
    {
        $group = [];

        foreach ($mutants as $mutant) {
            $mutantData = $mutant->toArray();

            $stderr = explode(PHP_EOL, $mutantData['stderr'], 2);
            $mutantData['stderr'] = $stderr[0];

            $group[] = $mutantData;
        }

        return $group;
    }
}
