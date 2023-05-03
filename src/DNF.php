<?php

namespace donatj\PhpDnfSolver;

use donatj\PhpDnfSolver\Exceptions\InvalidArgumentException;

class DNF {

	/**
	 * Helper to convert a ReflectionType into it's DNF representation
	 *
	 * Example sources include:
	 * - ReflectionFunctionAbstract::getParameters()[…]->getType()
	 * - ReflectionParameter::getType()
	 * - ReflectionMethod::getReturnType()
	 * - ReflectionProperty::getType()
	 */
	public static function getFromReflectionType( \ReflectionType $type ) : DnfTypeInterface {
		if( $type instanceof \ReflectionNamedType ) {
			if( $type->isBuiltin() ) {
				return new Types\BuiltInType($type->getName());
			}

			return new Types\UserDefinedType($type->getName());
		}

		if( $type instanceof \ReflectionIntersectionType ) {
			$types = array_map(
				fn( \ReflectionType $type ) => self::getFromReflectionType($type),
				$type->getTypes()
			);

			return new Types\AndClause(...$types);
		}

		if( $type instanceof \ReflectionUnionType ) {
			$types = array_map(
				fn( \ReflectionType $type ) => self::getFromReflectionType($type),
				$type->getTypes()
			);

			return new Types\OrClause(...$types);
		}

		throw new InvalidArgumentException('Unknown ReflectionType');
	}

	/**
	 * Helper to quickly check if a ReflectionType satisfies another ReflectionType
	 */
	public static function reflectionTypeSatisfiesReflectionType(
		\ReflectionType $satisfyingType,
		\ReflectionType $satisfiedType
	) : bool {
		$satisfyingDnf = self::getFromReflectionType($satisfyingType);
		$satisfiedDnf  = self::getFromReflectionType($satisfiedType);

		return $satisfyingDnf->isSatisfiedBy($satisfiedDnf);
	}

	/**
	 * Helper to quickly get a DNF representation of a (ReflectionParameter or ReflectionProperty)'s return type
	 */
	public static function getFromVarType( \ReflectionParameter|\ReflectionProperty $parameter ) : ?DnfTypeInterface {
		$type = $parameter->getType();

		return $type ? self::getFromReflectionType($type) : null;
	}

	/**
	 * Helper to quickly get a DNF representation of a ReflectionFunctionAbstract (ReflectionFunction /
	 * ReflectionMethod)'s return type
	 */
	public static function getFromReturnType( \ReflectionFunctionAbstract $func ) : ?DnfTypeInterface {
		$type = $func->getReturnType();

		return $type ? self::getFromReflectionType($type) : null;
	}

}
