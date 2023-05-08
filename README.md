# PHP DNF Solver

[![Latest Stable Version](https://poser.pugx.org/donatj/php-dnf-solver/version)](https://packagist.org/packages/donatj/php-dnf-solver)
[![License](https://poser.pugx.org/donatj/php-dnf-solver/license)](https://packagist.org/packages/donatj/php-dnf-solver)
[![ci.yml](https://github.com/donatj/php-dnf-solver/actions/workflows/ci.yml/badge.svg?)](https://github.com/donatj/php-dnf-solver/actions/workflows/ci.yml)


PHP DNF (Disjunctive Normal Form) Signature Compatibility Solver - see: https://wiki.php.net/rfc/dnf_types

## Requirements

- **php**: ^8.1

## Installing

Install the latest version with:

```bash
composer require 'donatj/php-dnf-solver'
```

## Examples

### Example Parameter Satisfaction Check

```php
<?php

namespace Examples;

use donatj\PhpDnfSolver\DNF;
use donatj\PhpDnfSolver\Types\UserDefinedType;

require __DIR__ . '/../vendor/autoload.php';

interface A {}
interface B {}
interface C {}
interface D {}

class Foo implements A, B {}
class Bar implements B, C {}
class Baz implements C, D {}

$qux = function ( A|(B&C) $aOrB ) : void {};

$quxParamType = (new \ReflectionFunction($qux))->getParameters()[0]->getType();

$quxDnf = DNF::getFromReflectionType($quxParamType);

var_dump($quxDnf->isSatisfiedBy(
	new UserDefinedType(Foo::class)
)); // true

var_dump($quxDnf->isSatisfiedBy(
	new UserDefinedType(Bar::class)
)); // true

var_dump($quxDnf->isSatisfiedBy(
	new UserDefinedType(Baz::class)
)); // false

```

Outputs:

```
bool(true)
bool(true)
bool(false)
```

### Example DNF Building

```php
<?php

namespace Examples;

use donatj\PhpDnfSolver\Types\AndClause;
use donatj\PhpDnfSolver\Types\BuiltInType;
use donatj\PhpDnfSolver\Types\OrClause;
use donatj\PhpDnfSolver\Types\UserDefinedType;

require __DIR__ . '/../vendor/autoload.php';

interface A {}
interface B {}
interface C {}

var_dump((new OrClause(
	new UserDefinedType(A::class),
	new UserDefinedType(B::class),
	new UserDefinedType(C::class)
))->dnf()); // A|B|C

var_dump((new OrClause(
	new UserDefinedType(A::class),
	new AndClause(
		new UserDefinedType(B::class),
		new UserDefinedType(C::class)
	)
))->dnf()); // A|(B&C)

var_dump((new OrClause(
	new AndClause(new UserDefinedType(A::class), new UserDefinedType(B::class)),
	new AndClause(
		new UserDefinedType(B::class),
		new UserDefinedType(C::class)
	),
	new BuiltInType('null'),
))->dnf()); // (A&B)|(B&C)|null

```

Outputs:

```
string(32) "Examples\A|Examples\B|Examples\C"
string(34) "Examples\A|(Examples\B&Examples\C)"
string(52) "(Examples\A&Examples\B)|(Examples\B&Examples\C)|null"
```

## Documentation

### Class: \donatj\PhpDnfSolver\DNF

#### Method: DNF::getFromReflectionType

```php
function getFromReflectionType(\ReflectionType $type) : \donatj\PhpDnfSolver\DnfTypeInterface
```

Helper to convert a ReflectionType into it's DNF representation  

##### Example sources include

- ReflectionFunctionAbstract::getParameters()[…]->getType()  
- ReflectionParameter::getType()  
- ReflectionMethod::getReturnType()  
- ReflectionProperty::getType()

---

#### Method: DNF::reflectionTypeSatisfiesReflectionType

```php
function reflectionTypeSatisfiesReflectionType(\ReflectionType $satisfyingType, \ReflectionType $satisfiedType) : bool
```

Helper to quickly check if a ReflectionType satisfies another ReflectionType

##### Parameters:

- ***\ReflectionType*** `$satisfyingType` - The type which must be satisfied (e.g. a parameter type)
- ***\ReflectionType*** `$satisfiedType` - The type which must satisfy the other (e.g. a return type)

---

#### Method: DNF::getFromVarType

```php
function getFromVarType(\ReflectionParameter|\ReflectionProperty $parameter) : ?\donatj\PhpDnfSolver\DnfTypeInterface
```

Helper to quickly get a DNF representation of a (ReflectionParameter or ReflectionProperty)'s return type

---

#### Method: DNF::getFromReturnType

```php
function getFromReturnType(\ReflectionFunctionAbstract $func) : ?\donatj\PhpDnfSolver\DnfTypeInterface
```

Helper to quickly get a DNF representation of a ReflectionFunctionAbstract (ReflectionFunction /  
ReflectionMethod)'s return type

### Class: \donatj\PhpDnfSolver\Exceptions\InvalidArgumentException

### Class: \donatj\PhpDnfSolver\Types\AndClause

Represents a "and clause" - a set of types which must all be satisfied - e.g. "A&B&C"

#### Method: AndClause->__construct

```php
function __construct(\donatj\PhpDnfSolver\SingularDnfTypeInterface ...$types)
```

##### Parameters:

- ***\donatj\PhpDnfSolver\SingularDnfTypeInterface*** `$types` - The list of types to be satisfied

---

#### Method: AndClause->dnf

```php
function dnf() : string
```

Return the canonical string representation of the DNF representation of this type

---

#### Method: AndClause->isSatisfiedBy

```php
function isSatisfiedBy(\donatj\PhpDnfSolver\DnfTypeInterface $value) : bool
```

Tests if this type is satisfied by the given type  
  
For example, if this type is "A|(B&C)" and the given type matches just "A", this method returns true.  
If the given type matches just "B", this method returns false.  
If the given type matches "B&C", this method returns true.

---

#### Method: AndClause->count

```php
function count() : int
```

Returns the number of types in this DNF type

---

#### Method: AndClause->getTypes

```php
function getTypes() : array
```

##### Returns:

- ***\donatj\PhpDnfSolver\SingularDnfTypeInterface[]***

### Class: \donatj\PhpDnfSolver\Types\BuiltInType

Represents a "built in type" as defined by ReflectionNamedType::isBuiltin()

This includes:
- int
- float
- string
- bool
- array
- iterable

#### Method: BuiltInType->__construct

```php
function __construct(string $name)
```

##### Parameters:

- ***string*** `$name` - The name of the built-in type

---

#### Method: BuiltInType->dnf

```php
function dnf() : string
```

Return the canonical string representation of the DNF representation of this type

---

#### Method: BuiltInType->getTypeName

```php
function getTypeName() : string
```

Returns the fully qualified type name of this type

---

#### Method: BuiltInType->isSatisfiedBy

```php
function isSatisfiedBy(\donatj\PhpDnfSolver\DnfTypeInterface $value) : bool
```

Tests if this type is satisfied by the given type  
  
For example, if this type is "A|(B&C)" and the given type matches just "A", this method returns true.  
If the given type matches just "B", this method returns false.  
If the given type matches "B&C", this method returns true.

---

#### Method: BuiltInType->count

```php
function count() : int
```

Always 1 for singular types

Returns the number of types in this DNF type

### Class: \donatj\PhpDnfSolver\Types\CallableType

Represents a "callable" type

This includes:
- callable
- Closure
- Invokable classes

#### Method: CallableType->dnf

```php
function dnf() : string
```

Return the canonical string representation of the DNF representation of this type

---

#### Method: CallableType->isSatisfiedBy

```php
function isSatisfiedBy(\donatj\PhpDnfSolver\DnfTypeInterface $value) : bool
```

Tests if this type is satisfied by the given type  
  
For example, if this type is "A|(B&C)" and the given type matches just "A", this method returns true.  
If the given type matches just "B", this method returns false.  
If the given type matches "B&C", this method returns true.

---

#### Method: CallableType->getTypeName

```php
function getTypeName() : string
```

Returns the fully qualified type name of this type

---

#### Method: CallableType->count

```php
function count() : int
```

Always 1 for singular types

Returns the number of types in this DNF type

### Class: \donatj\PhpDnfSolver\Types\OrClause

Represents a "or" clause - a set of types where any one of them must be satisfied - e.g. "A|B|(C&D)"

#### Method: OrClause->__construct

```php
function __construct(\donatj\PhpDnfSolver\Types\AndClause|\donatj\PhpDnfSolver\SingularDnfTypeInterface ...$types)
```

##### Parameters:

- ***\donatj\PhpDnfSolver\Types\AndClause*** | ***\donatj\PhpDnfSolver\SingularDnfTypeInterface*** `$types` - The list of types to be satisfied. Does not accept an OrClause as DNF defines that as invalid.

---

#### Method: OrClause->dnf

```php
function dnf() : string
```

Return the canonical string representation of the DNF representation of this type

---

#### Method: OrClause->isSatisfiedBy

```php
function isSatisfiedBy(\donatj\PhpDnfSolver\DnfTypeInterface $value) : bool
```

Tests if this type is satisfied by the given type  
  
For example, if this type is "A|(B&C)" and the given type matches just "A", this method returns true.  
If the given type matches just "B", this method returns false.  
If the given type matches "B&C", this method returns true.

---

#### Method: OrClause->count

```php
function count() : int
```

Returns the number of types in this DNF type

---

#### Method: OrClause->getTypes

```php
function getTypes() : array
```

##### Returns:

- ***\donatj\PhpDnfSolver\Types\AndClause[]***

### Class: \donatj\PhpDnfSolver\Types\UserDefinedType

Represents a "user defined type" - a class, interface, or trait, etc.

```php
<?php
namespace donatj\PhpDnfSolver\Types;

class UserDefinedType {
	public $className;
}
```

#### Method: UserDefinedType->__construct

```php
function __construct(string $className)
```

##### Parameters:

- ***string*** `$className` - The name of the class, interface, or trait to be satisfied

**Throws**: `\donatj\PhpDnfSolver\Exceptions\InvalidArgumentException` - if the user defined type does not exist after triggering registered autoloaders

---

#### Method: UserDefinedType->dnf

```php
function dnf() : string
```

Return the canonical string representation of the DNF representation of this type

---

#### Method: UserDefinedType->getTypeName

```php
function getTypeName() : string
```

Returns the fully qualified type name of this type

---

#### Method: UserDefinedType->isSatisfiedBy

```php
function isSatisfiedBy(\donatj\PhpDnfSolver\DnfTypeInterface $value) : bool
```

Tests if this type is satisfied by the given type  
  
For example, if this type is "A|(B&C)" and the given type matches just "A", this method returns true.  
If the given type matches just "B", this method returns false.  
If the given type matches "B&C", this method returns true.

---

#### Method: UserDefinedType->count

```php
function count() : int
```

Always 1 for singular types

Returns the number of types in this DNF type