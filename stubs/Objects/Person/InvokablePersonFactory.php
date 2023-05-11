<?php

namespace Stubs\Objects\Person;

class InvokablePersonFactory {

	public function __invoke() : PersonInterface {
		return new Person;
	}

}
