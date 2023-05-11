<?php

namespace Stubs\Objects\Person\PersonAwarenessInterfaces;

use Stubs\Interfaces\BazAwareInterface;
use Stubs\Objects\Person\PersonInterface;

interface BazAwarePersonInterface extends PersonInterface, BazAwareInterface {

	public function setBazIdsCallable( callable $courseIdsCallable ) : void;

}
