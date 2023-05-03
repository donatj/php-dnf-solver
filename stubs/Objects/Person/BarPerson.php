<?php

namespace Objects\Person;

use Traits\BazAwareTrait;
use Traits\FooAwareTrait;

class BarPerson extends Decorator implements BarPersonInterface {

	use BazAwareTrait;
	use FooAwareTrait;

}
