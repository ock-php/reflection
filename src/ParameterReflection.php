<?php

declare(strict_types=1);

namespace Ock\Reflection;

/**
 * Enhanced parameter reflector.
 */
class ParameterReflection extends \ReflectionParameter implements AttributesHavingReflectionInterface, NameHavingReflectionInterface {

  use AttributesHavingReflectionTrait;

  /**
   * Gets the parameter type class name, if it is unique.
   *
   * @return class-string|null
   *   A class name, if the declared return type is a single class name.
   *   NULL, if the declared type is not a single class name.
   */
  public function getParamClassName(): ?string {
    $type = $this->getType();
    if (!$type instanceof \ReflectionNamedType
      || $type->isBuiltin()
    ) {
      return null;
    }
    $name = $type->getName();
    if ($name === 'self' || $name === 'static') {
      $name = $this->getDeclaringClass()?->name
        // With current php versions, the exception below should be impossible.
        ?? throw new \RuntimeException(sprintf(
          'Unexpected %s parameter type on %s.',
          $name,
          $this->getDebugName(),
        ));
    }
    /** @var class-string $name */
    return $name;
  }

  /**
   * {@inheritdoc}
   */
  public function getDebugName(): string {
    // @todo Get the original class name, even if the function is inherited.
    $function = $this->getDeclaringFunction();
    return \sprintf(
      'parameter $%s of %s',
      $this->name,
      $function instanceof \ReflectionMethod
        ? $function->class . '::' . $function->name . '()'
        : $function->name,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFullName(): string {
    $function = $this->getDeclaringFunction();
    return \sprintf(
      '%s$%s',
      $function instanceof \ReflectionMethod
        ? $function->class . '::' . $function->name . '()'
        : $function->name,
      $this->name,
    );
  }

}
