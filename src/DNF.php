<?php

namespace donatj\PhpDnfSolver;

use donatj\PhpDnfSolver\Exceptions\InvalidArgumentException;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;

class DNF {

	public static function getFromReflectionType( ReflectionType $type ) : DnfTypeInterface {
		if( $type instanceof ReflectionNamedType ) {
			if( $type->isBuiltin() ) {
				return new Types\BuiltInType($type->getName());
			}

			return new Types\UserDefinedType($type->getName());
		}

		if( $type instanceof ReflectionIntersectionType ) {
			$types = array_map(
				fn( ReflectionType $type ) => self::getFromReflectionType($type),
				$type->getTypes()
			);

			return new Types\AndClause(...$types);
		}

		if( $type instanceof ReflectionUnionType ) {
			$types = array_map(
				fn( ReflectionType $type ) => self::getFromReflectionType($type),
				$type->getTypes()
			);

			return new Types\OrClause(...$types);
		}

		throw new InvalidArgumentException('Unknown ReflectionType');
	}

}