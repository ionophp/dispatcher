<?php
namespace Iono\Dispatcher\Exception;

/**
 * Class InvariantException
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class InvariantException extends \Exception
{
    /** @var int */
    public $framesToPop = 0;
}
