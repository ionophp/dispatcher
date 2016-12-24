<?php
require __DIR__ . "/../vendor/autoload.php";

$dispatcher = new Iono\Dispatcher\Dispatcher();
$dispatcher->setPrefix("demo.dispatch");

$dispatcher->register(function(\Iono\Dispatcher\Payload $payload) use ($dispatcher) {
    if($payload->actionName() === 'init') {
        echo "first\n";
    }
});
$dispatcher->register(function() {
    echo "second\n";
});
$dispatcher->unregister('demo.dispatch2');

$dispatcher->dispatch(new class implements \Iono\Dispatcher\Payload{
    public function actionName(): string
    {
        return 'init';
    }
});
