<?php

declare(strict_types=1);

namespace Ock\Reflection;

trait NonMethodTrait {

  /**
   * {@inheritdoc}
   */
  public function getMethodName(): ?string {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function isMethod(): false {
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function isConstructor(): bool {
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function isDestructor(): bool {
    return false;
  }

}
