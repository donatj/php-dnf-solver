<?php

namespace Stubs\Objects\Person\PersonAwarenessInterfaces;

use Stubs\Interfaces\FooAwareInterface;
use Stubs\Objects\Person\PersonInterface;

interface FooAwarePersonInterface extends FooAwareInterface, PersonInterface {

	public function setFooIdsCallable( callable $fooIdsCallable ) : void;

}
