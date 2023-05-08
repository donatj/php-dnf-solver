<?php

namespace Objects\Person\PersonAwarenessInterfaces;

use Interfaces\BazAwareInterface;
use Objects\Person\PersonInterface;

interface BazAwarePersonInterface extends PersonInterface, BazAwareInterface {

	public function setBazIdsCallable( callable $courseIdsCallable ) : void;

}
