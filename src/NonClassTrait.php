<?php

declare(strict_types=1);

namespace Ock\Reflection;

trait NonClassTrait {

  /**
   * {@inheritdoc}
   */
  public function isClassLike(): false {
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function isClass(): false {
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function isInterface(): false {
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function isTrait(): false {
    return false;
  }

}
