<?php
/**
 * Humbug
 *
 * @category   Humbug
 * @package    Humbug
 * @copyright  Copyright (c) 2015 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    https://github.com/padraic/humbug/blob/master/LICENSE New BSD License
 */

namespace Humbug\Adapter\Phpunit;

class Job
{
    /**
     * Generate a new Job script to be executed under a separate PHP process
     *
     * @param null|string   $mutantFile
     * @param array         $args
     * @param string        $bootstrap
     * @param null|string   $replacingFile
     * @return string
     */
    public static function generate($mutantFile = null, array $args = [], $bootstrap = '', $replacingFile = null)
    {
        if ('phar:' === substr(__FILE__, 0, 5)) {
            $humbugBootstrap = \Phar::running() . '/bootstrap.php';
        } else {
            $humbugBootstrap = realpath(__DIR__ . '/../../../bootstrap.php');
        }
        $file = sys_get_temp_dir() . '/humbug.phpunit.bootstrap.php';

        if (!is_null($mutantFile)) {
            $mutantFile = addslashes($mutantFile);
            $replacingFile = addslashes($replacingFile);
            $prepend = <<<PREPEND
<?php
require_once '{$humbugBootstrap}';
use Humbug\StreamWrapper\IncludeInterceptor;
IncludeInterceptor::intercept('{$replacingFile}', '{$mutantFile}');
IncludeInterceptor::enable();
PREPEND;
            if (!empty($bootstrap)) {
                $buffer = $prepend . "\nrequire_once '{$bootstrap}';";
            } else {
                $buffer = $prepend;
            }
            file_put_contents($file, $buffer);
        } else {
            if (!empty($bootstrap)) {
                $buffer = "<?php\nrequire_once '{$bootstrap}';";
            } else {
                $buffer = "<?php\n";
            }
            file_put_contents($file, $buffer);
        }

        $cliopts = serialize(isset($args['cliopts']) ? $args['cliopts'] : []);

        $args = base64_encode(serialize($args));

        $script = <<<SCRIPT
<?php
namespace Humbug\\Env;
\$_SERVER['argv'] = unserialize('{$cliopts}');
require_once '{$humbugBootstrap}';
use Humbug\Adapter\Phpunit;
Phpunit::main('{$args}');
SCRIPT;
        return $script;
    }
}
