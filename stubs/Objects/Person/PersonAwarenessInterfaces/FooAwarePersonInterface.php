<?php

namespace Objects\Person\PersonAwarenessInterfaces;

use Interfaces\FooAwareInterface;
use Objects\Person\PersonInterface;

interface FooAwarePersonInterface extends FooAwareInterface, PersonInterface {

	public function setFooIdsCallable( callable $fooIdsCallable ) : void;

}
