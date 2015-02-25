<?php
/**
 * Humbug
 *
 * @category   Humbug
 * @package    Humbug
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2015 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    https://github.com/padraic/humbug/blob/master/LICENSE New BSD License
 */

namespace Humbug\Test\Adapter\Phpunit;

use Humbug\Adapter\Phpunit\Job;

class JobTest extends \PHPUnit_Framework_TestCase
{

    public function testGenerateReturnsPHPScriptRenderedWithCurrentRunnersSettingsAndSerialisedMutationArray()
    {
        $script = Job::generate('the_file.php', [], '/path/to/bootstrap.php');
        $bootstrap = realpath(__DIR__ . '/../../../bootstrap.php');
        $expected = <<<EXPECTED
<?php
namespace Humbug\\Env;
\$_SERVER['argv'] = unserialize('a:0:{}');
require_once '{$bootstrap}';
use Humbug\Adapter\Phpunit;
Phpunit::main('YTowOnt9');
EXPECTED;
        $this->assertEquals($expected, $script);
    }
}
