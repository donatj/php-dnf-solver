<?php

namespace Objects\Person;

use Objects\Person\PersonAwarenessInterfaces\BazAwarePersonInterface;
use Objects\Person\PersonAwarenessInterfaces\FooAwarePersonInterface;

interface BarPersonInterface extends BazAwarePersonInterface, FooAwarePersonInterface {

}
