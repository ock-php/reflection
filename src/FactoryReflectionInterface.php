<?php

declare(strict_types = 1);

namespace Ock\Reflection;

/**
 * @template T of object
 */
interface FactoryReflectionInterface extends AttributesHavingReflectionInterface, NameHavingReflectionInterface, \Reflector {

  /**
   * Gets the original class name that this method was requested for.
   *
   * This will be different from ->getDeclaringClass(), if the method is
   * declared in a parent class.
   *
   * @return class-string<T>
   *   Object reflecting the original class.
   *   If this is already a class reflector, it returns the reflector itself.
   */
  public function getClassName(): string;

  /**
   * Gets the original class that this method was requested for.
   *
   * This will be different from ->getDeclaringClass(), if the method is
   * declared in a parent class.
   *
   * @return \Ock\Reflection\ClassReflection<T>
   *   Object reflecting the original class.
   *   If this is already a class reflector, it returns the reflector itself.
   */
  public function getClass(): ClassReflection;

  /**
   * Gets the method name, if this is a method.
   *
   * @return string|null
   *   Method name, or NULL if this is a class.
   */
  public function getMethodName(): ?string;

  /**
   * Checks if this is a method.
   *
   * @return bool
   *   TRUE if this is a method, FALSE if it is a class.
   */
  public function isMethod(): bool;

  /**
   * Checks if this is a class.
   *
   * @return bool
   *   TRUE if this is a class, interface or trait.
   *   FALSE if it is a method.
   */
  public function isClassLike(): bool;

  /**
   * Checks if this is a class.
   *
   * @return bool
   *   TRUE if this is a class.
   *   FALSE if it is a method, interface or trait.
   */
  public function isClass(): bool;

  /**
   * Checks if this is an interface.
   *
   * @return bool
   *   TRUE if this is an interface.
   *   FALSE if it is a method, class or trait.
   */
  public function isInterface(): bool;

  /**
   * Checks if this is an interface.
   *
   * @return bool
   *   TRUE if this is a trait.
   *   FALSE if it is a method, class or interface.
   */
  public function isTrait(): bool;

  /**
   * Checks if a method is declared in a parent class.
   *
   * @return bool
   *   TRUE if the method is declared in a parent class.
   *   If this is a class, it will always return FALSE.
   */
  public function isInherited(): bool;

  /**
   * Gets parameters of the method, or of the class constructor.
   *
   * @return list<\Ock\Reflection\ParameterReflection>
   *   Parameters of the method, or of the class constructor, or empty array if
   *   a class does not have a constructor.
   */
  public function getParameters(): array;

  /**
   * Checks whether the class or method has required parameters.
   *
   * @return bool
   *   TRUE if at least one parameter is required.
   */
  public function hasRequiredParameters(): bool;

  /**
   * Checks if the class or method is abstract.
   *
   * @return bool
   *   TRUE if the class or method is abstract, FALSE if not.
   */
  public function isAbstract(): bool;

  /**
   * Checks if method is static or it is a class.
   *
   * A non-static factory must be called with a $this object.
   * A static factory must be called without a $this object.
   * The operation of constructing a new class instance falls clearly into the
   * second category.
   *
   * @return bool
   *   TRUE if this is a class or a static method.
   */
  public function isStatic(): bool;

  /**
   * Checks if the class is instantiable or a method is callable.
   */
  public function isCallable(): bool;

  /**
   * Checks if this is a constructor.
   *
   * @return bool
   *   TRUE if this is a method and it is a constructor.
   *   FALSE if this is not a method, or not a constructor.
   */
  public function isConstructor(): bool;

  /**
   * Checks if this is a destructor.
   *
   * @return bool
   *   TRUE if this is a method and it is a destructor.
   *   FALSE if this is not a method, or not a destructor.
   */
  public function isDestructor(): bool;

  /**
   * Gets the specified return type.
   *
   * If this is a class, it returns a named type with this class.
   */
  public function getReturnType(): ?\ReflectionType;

  /**
   * Gets the return value class name, if it is unique.
   *
   * @return class-string|null
   *   If this is a class, the class name is returned.
   *   If this is a method:
   *     - A class name, if the declared return type is a single class name.
   *     - NULL, if the declared type is not a single class name.
   */
  public function getReturnClassName(): string|null;

  /**
   * Gets the return value class, if it is unique.
   *
   * @return \Ock\Reflection\ClassReflection<object>|null
   *   The returned class, or NULL if no single return class can be determined.
   *
   * @throws \ReflectionException
   *   Returned class does not exist.
   */
  public function getReturnClass(): ?ClassReflection;

  /**
   * Gets the return value class, if it is unique.
   *
   * @return \Ock\Reflection\ClassReflection<object>|null
   *   The returned class, or NULL if no single return class can be determined.
   */
  public function getReturnClassIfExists(): ?ClassReflection;

}
