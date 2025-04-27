<?php

declare(strict_types=1);

namespace Ock\Reflection;

/**
 * Trait with shared implementations.
 */
trait FactoryReflectionTrait {

  use AttributesHavingReflectionTrait;

  /**
   * {@inheritdoc}
   */
  public function hasRequiredParameters(): bool {
    $parameters = $this->getParameters();
    foreach ($parameters as $parameter) {
      if (!$parameter->isOptional()) {
        return TRUE;
      }
    }
    return FALSE;
  }

}
