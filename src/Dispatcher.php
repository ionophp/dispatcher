<?php
declare(strict_types=1);

namespace Iono\Dispatcher;

/**
 * Class Dispatcher
 * facebook flux pattern style
 *
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class Dispatcher
{
    /** @var int */
    protected $lastID = 1;

    /** @var string */
    protected $prefix = 'ID_';

    /** @var array */
    protected $callbacks = [];

    /** @var array */
    protected $isPending = [];

    /** @var array */
    protected $isHandled = [];

    /** @var bool */
    protected $isDispatching = false;

    /** @var null */
    protected $pendingPayload = null;

    /**
     * @param callable $callback
     *
     * @return string
     */
    public function register(callable $callback): string
    {
        $id = $this->prefix . $this->lastID++;
        $this->callbacks[$id] = $callback;

        return $id;
    }

    /**
     * @param string $id
     *
     * @throws Exception\InvariantException
     */
    public function unregister(string $id)
    {
        invariant(
            (isset($this->callbacks[$id])) ?? null,
            'class Dispatcher unregister(...): `%s` does not map to a registered callback.',
            $id
        );
        unset($this->callbacks[$id]);
    }

    /**
     * @param array $ids
     *
     * @throws Exception\InvariantException
     */
    public function waitFor(array $ids)
    {
        invariant(
            $this->isDispatching,
            'waitFor(...): Must be invoked while dispatching.'
        );
        for ($ii = 0; $ii < count($ids); $ii++) {
            $id = $ids[$ii];
            if ($this->isPending[$id]) {
                invariant(
                    $this->isHandled[$id],
                    'waitFor(...): Circular dependency detected while ' .
                    'waiting for `%s`.',
                    $id
                );
                continue;
            }
            invariant(
                $this->callbacks[$id],
                'waitFor(...): `%s` does not map to a registered callback.',
                $id
            );
            $this->invokeCallback($id);
        }
    }

    /**
     * @param Payload $payload
     *
     * @throws Exception\InvariantException
     */
    public function dispatch(Payload $payload)
    {
        invariant(
            !$this->isDispatching,
            'dispatch(...): Cannot dispatch in the middle of a dispatch.'
        );
        $this->startDispatching($payload);
        try {
            foreach ($this->callbacks as $id => $callback) {
                if ($this->isPending[$id]) {
                    continue;
                }
                $this->invokeCallback($id);
            }
        } finally {
            $this->stopDispatching();
        }
    }


    /**
     * @return bool
     */
    public function isDispatching(): bool
    {
        return $this->isDispatching;
    }

    /**
     * @param string $id
     */
    protected function invokeCallback(string $id)
    {
        $this->isPending[$id] = true;
        call_user_func($this->callbacks[$id], $this, $this->pendingPayload);
        $this->isHandled[$id] = true;
    }

    /**
     * @param Payload $payload
     */
    protected function startDispatching(Payload $payload)
    {
        foreach ($this->callbacks as $id => $value) {
            $this->isPending[$id] = false;
            $this->isHandled[$id] = false;
        }
        $this->pendingPayload = $payload;
        $this->isDispatching = true;
    }

    /**
     *
     */
    protected function stopDispatching()
    {
        $this->pendingPayload = null;
        $this->isDispatching = false;
    }

    /**
     * @param string $prefix
     *
     * @return $this
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }
}
