<?php

declare(strict_types=1);

namespace Ock\Reflection;

interface NameHavingReflectionInterface {

  /**
   * Gets a name to use in debug and log messages.
   *
   * This name is unique against other reflectors of any type.
   *
   * @return string
   *   For classes, this will be the qualified class name.
   *   For methods, this will be like "$class::$method()".
   *   For parameters, it will be
   */
  public function getDebugName(): string;

  /**
   * Gets a name to use as identifier.
   *
   * This identifier is unique against other classes and methods, but could
   * clash with other class members like properties and constants, assuming
   * these would use the same identifier pattern "$class::$member".
   *
   * @return string
   *   For classes, this will be the qualified class name.
   *   For methods, this will be like "$class::$method".
   */
  public function getFullName(): string;

}
