<?php

namespace Stubs\Objects\Person;

use Stubs\Objects\Person\PersonAwarenessInterfaces\BazAwarePersonInterface;
use Stubs\Objects\Person\PersonAwarenessInterfaces\FooAwarePersonInterface;

interface BarPersonInterface extends BazAwarePersonInterface, FooAwarePersonInterface {

}
