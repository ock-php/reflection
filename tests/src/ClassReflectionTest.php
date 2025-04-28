<?php

declare(strict_types = 1);

namespace Ock\Reflection\Tests;

use Ock\Reflection\ClassReflection;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ock\Reflection\ClassReflection
 */
class ClassReflectionTest extends TestCase {

  public function testCreateKnown(): void {
    $reflection = ClassReflection::createKnown(\stdClass::class);
    $this->assertEquals(new ClassReflection(\stdClass::class), $reflection);
    $this->assertSame(\stdClass::class, $reflection->name);
  }

}
