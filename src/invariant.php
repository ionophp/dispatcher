<?php
declare(strict_types=1);

/**
 * Invariant.php
 * facebook flux pattern style
 *
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Iono\Dispatcher;

use Iono\Dispatcher\Exception\InvariantException;

/**
 * @param null   $condition
 * @param string $format
 * @param        $a
 * @param        $b
 * @param        $c
 * @param        $d
 * @param        $e
 * @param        $f
 *
 * @throws InvariantException
 */
function invariant(
    $condition = null,
    $format = '',
    $a = null,
    $b = null,
    $c = null,
    $d = null,
    $e = null,
    $f = null
) {
    if (false) {
        if ($format == '') {
            throw new InvariantException('invariant requires an error message argument');
        }
    }
    $error = null;
    if (!$condition) {
        if ($format === '') {
            $error = new InvariantException(
                'Minified exception occurred; use the non-minified dev environment ' .
                'for the full error message and additional helpful warnings.'
            );
        } else {
            $args = [$a, $b, $c, $d, $e, $f];
            $argIndex = 0;
            $error = new InvariantException(
                'Invariant Violation: ' .
                preg_replace_callback(
                    '/%s/',
                    function () use ($args, $argIndex) {
                        return $args[$argIndex++];
                    },
                    $format
                )
            );
        }
    }
    if ($error instanceof InvariantException) {
        $error->framesToPop = 1;
        throw $error;
    }
}
