<?php
declare(strict_types=1);

namespace Iono\Dispatcher;

/**
 * Interface Handleable
 */
interface Handleable
{
    /**
     * @param Dispatcher $dispatcher
     * @param Payload    $payload
     *
     * @return mixed
     */
    public function __invoke(Dispatcher $dispatcher, Payload $payload);
}
