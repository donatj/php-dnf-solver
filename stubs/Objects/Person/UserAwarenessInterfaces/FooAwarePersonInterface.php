<?php

namespace Objects\Person\UserAwarenessInterfaces;

use Interfaces\FooAwareInterface;
use Objects\Person\PersonInterface;

interface FooAwarePersonInterface extends FooAwareInterface, PersonInterface {

	public function setFooIdsCallable( callable $fooIdsCallable ) : void;

}
