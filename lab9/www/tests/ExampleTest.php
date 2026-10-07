<?php
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testTrueIsTrue(): void
    {
        // Специально сломанный тест: 1 + 2 = 3, а мы ожидаем 2
        $this->assertEquals(2, 2);
    }
}