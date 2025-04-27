<?php

declare(strict_types=1);

namespace Ock\Reflection;

/**
 * Contains static factories.
 */
class FactoryReflection {

  /**
   * Converts a native or any reflector into a FactoryReflection* object.
   *
   * @param \Reflector $reflector
   *   Native or other reflector.
   *
   * @return \Ock\Reflection\FactoryReflectionInterface<object>|null
   *   A factory reflection object, or NULL if conversion not available.
   */
  public static function fromReflector(\Reflector $reflector): ?FactoryReflectionInterface {
    return match (true) {
      $reflector instanceof FactoryReflectionInterface => $reflector,
      $reflector instanceof \ReflectionClass => ClassReflection::createKnown($reflector->name),
      $reflector instanceof \ReflectionMethod => new MethodReflection($reflector->class, $reflector->name),
      default => null,
    };
  }

}
