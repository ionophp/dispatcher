<?php

use Iono\Dispatcher\Payload;
use Iono\Dispatcher\Dispatcher;
use Iono\Dispatcher\Handleable;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Iono\Dispatcher\Dispatcher */
    protected $dispatcher;

    /** @var string */
    protected $defaultId = "ID_1";

    /** @var array */
    protected $dispatch = [];

    protected function setUp()
    {
        $this->dispatcher = new Dispatcher;
    }

    public function testRegister()
    {
        $id = $this->dispatcher->register(
            function () {
                return "testing";
            }
        );
        $this->assertSame($this->defaultId, $id);
    }

    public function testPrefix()
    {
        $this->dispatcher->setPrefix("testing:");
        $id = $this->dispatcher->register(
            function () {
                return "testing";
            }
        );
        $this->assertSame("testing:1", $id);
    }

    /**
     * @expectedException \Iono\Dispatcher\Exception\InvariantException
     */
    public function testUnregister()
    {
        $id = $this->dispatcher->register(
            function () {
                return "testing";
            }
        );
        $this->dispatcher->unregister($id);
        $this->dispatcher->unregister($id);
    }

    public function testDispatch()
    {
        $call = null;
        $id = $this->dispatcher->register(function (Dispatcher $dispatcher, Payload $payload) {
            $this->assertSame('testing', $payload->actionName());
        });
        $this->assertNull($call);
        $this->dispatcher->dispatch(new class($id) implements Payload
        {
            public function actionName(): string
            {
                return 'testing';
            }
        });
    }

    /**
     * @expectedException \Iono\Dispatcher\Exception\InvariantException
     */
    public function testDispatchingException()
    {
        $dispatch = $this->dispatcher;
        $dispatch->register(function (Dispatcher $dispatch, Payload $payload) {
            if ($payload->actionName() == 'action') {
                $this->assertTrue($dispatch->isDispatching());
                $dispatch->waitFor(["ID_1"]);
            }
        });
        $dispatch->dispatch(new class() implements Payload {
            public function actionName(): string
            {
                return 'action';
            }
        });
    }

    public function testShouldBeDispatchWait()
    {
        $this->dispatch = [];
        $this->dispatcher->register(function () {
            $this->dispatch[] = 1;
        });
        $this->dispatcher->register(function (\Iono\Dispatcher\Dispatcher $dispatcher, $payload) {
            $dispatcher->waitFor(['ID_3']);
            $this->dispatch[] = 2;
        });
        $this->dispatcher->register(function () {
            $this->dispatch[] = 3;
        });
        $this->dispatcher->dispatch(new class() implements Payload {
            public function actionName(): string
            {
                return 'action';
            }
        });
        $this->assertSame('1,3,2', implode(',', $this->dispatch));
    }

    public function testShouldHandle()
    {
        $handle = new class implements Handleable{
            public function __invoke(Dispatcher $dispatcher, Payload $payload)
            {
                \PHPUnit_Framework_Assert::assertSame('invoker', $payload->actionName());
            }
        };
        $id = $this->dispatcher->register($handle);
        $this->dispatcher->dispatch(new class implements Payload {
            public function actionName(): string
            {
                return 'invoker';
            }
        });
    }

    public function testShouldBeDispatchWait2()
    {
        $this->dispatch = [];
        $this->dispatcher->register(function () {
            $this->dispatch[] = 1;
        });
        $this->dispatcher->register(function (\Iono\Dispatcher\Dispatcher $dispatcher, $payload) {
            $dispatcher->waitFor(['ID_3', 'ID_1']);
            $this->dispatch[] = 2;
        });
        $this->dispatcher->register(function () {
            $this->dispatch[] = 3;
        });
        $this->dispatcher->dispatch(new class() implements Payload {
            public function actionName(): string
            {
                return 'action';
            }
        });
        $this->assertSame('1,3,2', implode(',', $this->dispatch));
    }
}
