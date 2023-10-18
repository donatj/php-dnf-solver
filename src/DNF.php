<?php

namespace donatj\PhpDnfSolver;

use donatj\PhpDnfSolver\Exceptions\InvalidArgumentException;
use donatj\PhpDnfSolver\Exceptions\LogicException;
use donatj\PhpDnfSolver\Types\AndClause;
use donatj\PhpDnfSolver\Types\OrClause;

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
	public static function getFromReflectionType( \ReflectionType $type ) : SingularDnfTypeInterface|NestedDnfTypeInterface {
		if( $type instanceof \ReflectionNamedType ) {
			if( $type->isBuiltin() ) {
				if( $type->getName() === 'callable' ) {
					return new Types\CallableType;
				}

				$dnfType = new Types\BuiltInType($type->getName());
				if( $type->getName() === 'mixed' ) {
					return $dnfType;
				}
			} else {
				// @phpstan-ignore-next-line
				$dnfType = new Types\UserDefinedType($type->getName());
			}

			if( $type->allowsNull() && $type->getName() !== 'null' ) {
				return new OrClause($dnfType, new Types\BuiltInType('null'));
			}

			return $dnfType;
		}

		if( $type instanceof \ReflectionIntersectionType ) {
			$types = array_map(
				function ( \ReflectionType $type ) : SingularDnfTypeInterface {
					$reflectionType = self::getFromReflectionType($type);
					if( !$reflectionType instanceof SingularDnfTypeInterface ) {
						throw new LogicException('Intersection types must be singular');
					}

					return $reflectionType;
				},
				$type->getTypes()
			);

			return new Types\AndClause(...$types);
		}

		if( $type instanceof \ReflectionUnionType ) {
			$types = array_map(
				function ( \ReflectionType $type ) : SingularDnfTypeInterface|AndClause {
					$reflectionType = self::getFromReflectionType($type);
					if( (!$reflectionType instanceof SingularDnfTypeInterface) && (!$reflectionType instanceof AndClause) ) {
						throw new LogicException('Intersection types must be singular');
					}

					return $reflectionType;
				},
				$type->getTypes()
			);

			return new Types\OrClause(...$types);
		}

		throw new InvalidArgumentException('Unknown ReflectionType: ' . get_class($type));
	}

	/**
	 * Helper to quickly check if a ReflectionType satisfies another ReflectionType
	 *
	 * @param \ReflectionType $satisfyingType The type which must be satisfied (e.g. a parameter type)
	 * @param \ReflectionType $satisfiedType  The type which must satisfy the other (e.g. a return type)
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
	public static function getFromVarType(
		\ReflectionParameter|\ReflectionProperty $parameter
	) : SingularDnfTypeInterface|NestedDnfTypeInterface|null {
		$type = $parameter->getType();

		return $type ? self::getFromReflectionType($type) : null;
	}

	/**
	 * Helper to quickly get a DNF representation of a ReflectionFunctionAbstract (ReflectionFunction /
	 * ReflectionMethod)'s return type
	 */
	public static function getFromReturnType(
		\ReflectionFunctionAbstract $func
	) : SingularDnfTypeInterface|NestedDnfTypeInterface|null {
		$type = $func->getReturnType();

		return $type ? self::getFromReflectionType($type) : null;
	}

}
