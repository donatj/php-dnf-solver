<?php

namespace Stubs\Objects\Person;

use Stubs\Traits\BazAwareTrait;
use Stubs\Traits\FooAwareTrait;

class BarPerson extends Decorator implements BarPersonInterface {

	use BazAwareTrait;
	use FooAwareTrait;

}
