<?php

namespace RenokiCo\PhpK8s\Test;

use RenokiCo\PhpK8s\Instances\Container;
use RenokiCo\PhpK8s\K8s;
use RenokiCo\PhpK8s\Kinds\K8sPod;

class MacroTest extends TestCase
{
    public function test_instances_macro()
    {
        Container::macro('macroTest', function ($var1, $var2) {
            return $this->setMacroField([$var1, $var2]);
        });

        Container::macro('getMacroTest', function () {
            return $this->getMacroField([]);
        });

        $container = K8s::container()->macroTest('val1', 'val2');

        $this->assertEquals(['val1', 'val2'], $container->getMacroTest());
    }

    public function test_resource_macro()
    {
        K8sPod::macro('macroTest', function ($var1, $var2) {
            return $this->setMacroField([$var1, $var2]);
        });

        K8sPod::macro('getMacroTest', function () {
            return $this->getMacroField([]);
        });

        $pod = K8s::pod()->macroTest('val1', 'val2');

        $this->assertEquals(['val1', 'val2'], $pod->getMacroTest());
    }

    public function test_k8s_macro()
    {
        Kinds\NewResource::register();
        Kinds\NewResource::register('nr');

        $this->assertInstanceOf(Kinds\NewResource::class, K8s::newResource());
        $this->assertInstanceOf(Kinds\NewResource::class, (new K8s())->newResource());
        $this->assertInstanceOf(Kinds\NewResource::class, $this->cluster->newResource());

        $this->assertInstanceOf(Kinds\NewResource::class, K8s::nr());
        $this->assertInstanceOf(Kinds\NewResource::class, (new K8s())->nr());
        $this->assertInstanceOf(Kinds\NewResource::class, $this->cluster->nr());
    }
}
