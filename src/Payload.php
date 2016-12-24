<?php
declare(strict_types=1);

namespace Iono\Dispatcher;

/**
 * Class Payload
 */
interface Payload
{
    /**
     * @return string
     */
    public function actionName(): string;
}
