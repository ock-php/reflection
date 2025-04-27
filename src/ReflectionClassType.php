<?php

declare(strict_types = 1);

namespace Ock\Reflection;

/**
 * Reflection type class that works if it is instantiated normally.
 */
class ReflectionClassType extends \ReflectionNamedType {

  /**
   * Constructor.
   *
   * @param class-string $class
   */
  public function __construct(
    private readonly string $class,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return $this->class;
  }

  /**
   * {@inheritdoc}
   */
  public function isBuiltin(): false {
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function allowsNull(): false {
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function __toString(): string {
    return $this->class;
  }

}
