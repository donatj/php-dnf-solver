<?php

namespace Tests;

trait TypeHelperTrait {

	protected function firstParamType( callable $reflection ) : \ReflectionType {
		return (new \ReflectionFunction($reflection))->getParameters()[0]->getType();
	}

	protected function returnType( callable $reflection ) : \ReflectionType {
		return (new \ReflectionFunction($reflection))->getReturnType();
	}

}
