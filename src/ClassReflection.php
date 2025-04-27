<?php

declare(strict_types = 1);

namespace Ock\Reflection;

/**
 * @template T of object
 *
 * @template-extends \ReflectionClass<T>
 * @template-implements \Ock\Reflection\FactoryReflectionInterface<T>
 */
class ClassReflection extends \ReflectionClass implements FactoryReflectionInterface {

  use FactoryReflectionTrait;

  use NonMethodTrait;

  /**
   * Constructor.
   *
   * @phpstan-param T|class-string<T> $objectOrClass
   *
   * @throws \ReflectionException
   *   Class does not exist or has missing dependencies.
   */
  public function __construct(object|string $objectOrClass) {
    try {
      parent::__construct($objectOrClass);
    }
    // @phpstan-ignore catch.neverThrown
    catch (\Error $e) {
      // This error happens if a class can be autoloaded, but the base class or
      // one of the interfaces is missing.
      assert(is_string($objectOrClass));
      // Re-throw as ReflectionException.
      // This way, calling code can handle this the same way as if the class
      // itself did not exist.
      throw new \ReflectionException(sprintf(
        'Class % is not available: %s',
        $objectOrClass,
        $e->getMessage(),
      ), 0, $e);
    }
  }

  /**
   * Creates an instance from a class name that is known to exist.
   *
   * @param class-string<T> $name
   *
   * @return self<T>
   */
  public static function createKnown(string $name): self {
    try {
      // Extend at your own risk.
      // @phpstan-ignore-next-line
      return new static($name);
    }
    catch (\ReflectionException|\Error $e) {
      // The class does not exist, or one of its parents or interfaces is
      // missing.
      throw new \RuntimeException("Known class $name was not found or could not be loaded.", 0, $e);
    }
  }

  /**
   * Static factory. Creates an instance if the class exists.
   *
   * @param class-string<T> $name
   *   Class name.
   *
   * @return self<T>|null
   *   New instance, or NULL if the class does not exist, or if one of its
   *   parents or interfaces is missing.
   */
  public static function createIfExists(string $name): self|null {
    try {
      // Extend at your own risk.
      // @phpstan-ignore-next-line
      return new static($name);
    }
    catch (\ReflectionException|\Error) {
      // The class does not exist, or one of its parents or interfaces is
      // missing.
      return null;
    }
  }

  /**
   * Gets a list including the class itself and its non-constructor methods.
   *
   * Normally only public, non-abstract methods should be considered as
   * factories. However, some inspectors need to analyze other methods, if only
   * to detect and report invalid placement of attributes.
   *
   * The constructor can always be omitted, because the class reflector itself
   * already provides everything one could want from it.
   *
   * @return list<\Ock\Reflection\FactoryReflectionInterface<T>>
   */
  public function getFactories(): array {
    return [$this, ...$this->getFilteredMethods(constructor: false)];
  }

  /**
   * @param int|null $filter
   *
   * @return list<\Ock\Reflection\MethodReflection<T>>
   */
  public function getMethods(int|null $filter = null): array {
    try {
      return array_map(
        fn (\ReflectionMethod $method) => new MethodReflection($this->name, $method->getName()),
        parent::getMethods($filter),
      );
    }
    catch (\ReflectionException $e) {
      throw new \RuntimeException('Unreachable code.', 0, $e);
    }
  }

  /**
   * Gets methods that match the filters.
   *
   * @param int|null $filter
   *
   * @return list<\Ock\Reflection\MethodReflection<T>>
   */
  public function getCallableMethods(int|null $filter = null): array {
    $methods = parent::getMethods($filter);
    $result = [];
    foreach ($methods as $method) {
      if ($method->isAbstract() || $method->isConstructor() || !$method->isPublic()) {
        continue;
      }
      try {
        $result[] = new MethodReflection($this->name, $method->getName());
      }
      catch (\ReflectionException $e) {
        throw new \RuntimeException('Unreachable code.', 0, $e);
      }
    }
    return $result;
  }

  /**
   * Gets methods that match the filters.
   *
   * @param int|null $filter
   * @param bool|null $static
   * @param bool|null $public
   * @param bool|null $protected
   * @param bool|null $private
   * @param bool|null $abstract
   * @param bool|null $final
   * @param bool|null $constructor
   *
   * @return list<\Ock\Reflection\MethodReflection<T>>
   */
  public function getFilteredMethods(
    int $filter = null,
    bool $static = null,
    bool $public = null,
    bool $protected = null,
    bool $private = null,
    bool $abstract = null,
    bool $final = null,
    bool $constructor = null,
  ): array {
    $methods = parent::getMethods($filter);
    $result = [];
    foreach ($methods as $method) {
      if ($static !== null && $static !== $method->isStatic()) {
        continue;
      }
      if ($public !== null && $public !== $method->isPublic()) {
        continue;
      }
      if ($protected !== null && $protected !== $method->isProtected()) {
        continue;
      }
      if ($private !== null && $private !== $method->isPrivate()) {
        continue;
      }
      if ($abstract !== null && $abstract !== $method->isAbstract()) {
        continue;
      }
      if ($final !== null && $final !== $method->isFinal()) {
        continue;
      }
      if ($constructor !== null && $constructor !== $method->isConstructor()) {
        continue;
      }
      try {
        $result[] = new MethodReflection($this->name, $method->getName());
      }
      catch (\ReflectionException $e) {
        throw new \RuntimeException('Unreachable code.', 0, $e);
      }
    }
    return $result;
  }

  /**
   * Gets the method with the given name.
   *
   * @param string $name
   *   Method name.
   *
   * @return \Ock\Reflection\MethodReflection<T>
   *   The method.
   *
   * @throws \ReflectionException
   *   Method does not exist.
   */
  public function getMethod(string $name): MethodReflection {
    return new MethodReflection($this->name, $name);
  }

  /**
   * @return \Ock\Reflection\MethodReflection<T>|null
   */
  public function getConstructor(): ?MethodReflection {
    if (!$this->hasMethod('__construct')) {
      return null;
    }
    try {
      return new MethodReflection($this->name, '__construct');
    }
    catch (\ReflectionException $e) {
      throw new \RuntimeException('Unreachable code.', 0, $e);
    }
  }

  /**
   * Gets methods that match the filters.
   *
   * @param int|null $filter
   * @param bool|null $static
   * @param bool|null $public
   * @param bool|null $protected
   * @param bool|null $private
   * @param bool|null $readonly
   *
   * @return list<\ReflectionProperty>
   */
  public function getFilteredProperties(
    int $filter = null,
    bool $static = null,
    bool $public = null,
    bool $protected = null,
    bool $private = null,
    bool $readonly = null,
  ): array {
    $properties = parent::getProperties($filter);
    $result = [];
    foreach ($properties as $property) {
      if ($static !== NULL && $static !== $property->isStatic()) {
        continue;
      }
      if ($public !== NULL && $public !== $property->isPublic()) {
        continue;
      }
      if ($protected !== NULL && $protected !== $property->isProtected()) {
        continue;
      }
      if ($private !== NULL && $private !== $property->isPrivate()) {
        continue;
      }
      if ($readonly !== NULL && $readonly !== $property->isReadOnly()) {
        continue;
      }
      try {
        $result[] = new \ReflectionProperty($this->name, $property->getName());
      }
      catch (\ReflectionException $e) {
        throw new \RuntimeException('Unreachable code.', 0, $e);
      }
    }
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function getClassName(): string {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public function getClass(): static {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isClassLike(): true {
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function isClass(): bool {
    return !$this->isInterface() && !$this->isTrait();
  }

  /**
   * {@inheritdoc}
   */
  public function isInherited(): false {
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function getParameters(): array {
    return $this->getConstructor()?->getParameters() ?? [];
  }

  /**
   * {@inheritdoc}
   */
  public function isStatic(): true {
    // Constructing a class does not need a $this object, so this factory counts
    // as static.
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function isCallable(): bool {
    return $this->isInstantiable();
  }

  /**
   * @return \Ock\Reflection\ReflectionClassType
   */
  #[\Override]
  public function getReturnType(): ReflectionClassType {
    return new ReflectionClassType($this->name);
  }

  /**
   * Gets the return value class name, if it is unique.
   *
   * @return class-string
   *   The class name of this class.
   */
  #[\Override]
  public function getReturnClassName(): string {
    return $this->name;
  }

  /**
   * Gets the "return value class", which is the class itself.
   *
   * @return self
   */
  #[\Override]
  public function getReturnClass(): self {
    return $this;
  }

  /**
   * Gets the "return value class", which is the class itself.
   *
   * @return self
   */
  #[\Override]
  public function getReturnClassIfExists(): self {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDebugName(): string {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public function getFullName(): string {
    return $this->name;
  }

  /**
   * Gets the interface name, if the class implements exactly one interface.
   *
   * @param bool $inclusive
   *   If TRUE, and if this reflector object itself represents an interface,
   *   then the name of that interface will be included in the list.
   *
   * @return string|null
   *   The interface name, or NULL if the class implements no interfaces, or
   *   more than one interface.
   */
  public function getOnlyInterfaceName(bool $inclusive = false): ?string {
    $interfaces = $this->getInterfaceNames();
    if ($inclusive && $this->isInterface()) {
      return ($interfaces === []) ? $this->name : null;
    }
    return count($interfaces) !== 1 ? null : $interfaces[0];
  }

  /**
   * Gets interface names including the interface itself if it is one.
   *
   * @return list<class-string>
   */
  public function getInclusiveInterfaceNames(): array {
    $names = $this->getInterfaceNames();
    if ($this->isInterface()) {
      $names = [$this->name, ...$names];
    }
    return $names;
  }

}
