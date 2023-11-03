<?php

namespace Tests;

trait TypeHelperTrait {

	protected static function firstParamType( callable $reflection ) : \ReflectionType {
		return (new \ReflectionFunction($reflection))->getParameters()[0]->getType();
	}

	protected static function returnType( callable $reflection ) : \ReflectionType {
		return (new \ReflectionFunction($reflection))->getReturnType();
	}

}
