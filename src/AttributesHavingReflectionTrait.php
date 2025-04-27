<?php

declare(strict_types=1);

namespace Ock\Reflection;

trait AttributesHavingReflectionTrait {

  /**
   * {@inheritdoc}
   */
  public function hasAttributes(?string $name, int $flags = \ReflectionAttribute::IS_INSTANCEOF): bool {
    return $this->getAttributes($name, $flags) !== [];
  }

  /**
   * {@inheritdoc}
   */
  public function getAttributeInstances(string $name, int $flags = \ReflectionAttribute::IS_INSTANCEOF): array {
    $attributes = $this->getAttributes($name, $flags);
    if (!$attributes) {
      return [];
    }
    return array_map(fn (\ReflectionAttribute $attribute) => $attribute->newInstance(), $attributes);
  }

}
