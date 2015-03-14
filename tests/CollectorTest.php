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

namespace Humbug\Test;

use Humbug\Collector;

class CollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldCollectShadows()
    {
        $collector = new Collector();

        $collector->collectShadow();

        $this->assertEquals(1, $collector->getTotalCount());
        $this->assertEquals(1, $collector->getShadowCount());
    }

    public function testShouldCollectEscaped()
    {
        $mutant = $this->createMutant();

        $collector = new Collector();

        $collector->collectEscaped($mutant);

        $this->assertEquals(1, $collector->getTotalCount());
        $this->assertEquals(1, $collector->getEscapeCount());
        $this->assertContains($mutant, $collector->getEscaped());
    }

    public function testShouldCollectKilled()
    {
        $mutant = $this->createMutant();

        $collector = new Collector();

        $collector->collectKilled($mutant);

        $this->assertEquals(1, $collector->getTotalCount());
        $this->assertEquals(1, $collector->getKilledCount());
        $this->assertContains($mutant, $collector->getKilled());
    }

    public function testShouldCollectError()
    {
        $mutant = $this->createMutant();

        $collector = new Collector();

        $collector->collectError($mutant);

        $this->assertEquals(1, $collector->getTotalCount());
        $this->assertEquals(1, $collector->getErrorCount());
        $this->assertContains($mutant, $collector->getErrors());
    }

    public function testShouldCollectDifferentMutants()
    {
        $collector = new Collector();

        $collector->collectShadow();
        $collector->collectError($this->createMutant());
        $collector->collectEscaped($this->createMutant());
        $collector->collectTimeout($this->createMutant());
        $collector->collectKilled($this->createMutant());

        $this->assertEquals(5, $collector->getTotalCount());
        $this->assertEquals(3, $collector->getVanquishedTotal());
        $this->assertEquals(4, $collector->getMeasurableTotal());

        $this->assertEquals(1, $collector->getErrorCount());
        $this->assertEquals(1, $collector->getTimeoutCount());
        $this->assertEquals(1, $collector->getKilledCount());
        $this->assertEquals(1, $collector->getShadowCount());
        $this->assertEquals(1, $collector->getEscapeCount());
    }

    public function testShouldCollectTimeout()
    {
        $mutant = $this->createMutant();

        $collector = new Collector();

        $collector->collectTimeout($mutant);

        $this->assertEquals(1, $collector->getTotalCount());
        $this->assertEquals(1, $collector->getTimeoutCount());
        $this->assertContains($mutant, $collector->getTimeouts());
    }

    private function createMutant()
    {
        return
            $this->getMockBuilder('Humbug\Mutant')
                ->disableOriginalConstructor()
                ->getMock();
    }
}
 