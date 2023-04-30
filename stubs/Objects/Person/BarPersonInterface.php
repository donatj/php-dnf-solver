<?php

namespace Objects\Person;

use Objects\Person\UserAwarenessInterfaces\BazAwarePersonInterface;
use Objects\Person\UserAwarenessInterfaces\FooAwarePersonInterface;

interface BarPersonInterface extends BazAwarePersonInterface, FooAwarePersonInterface {

}
