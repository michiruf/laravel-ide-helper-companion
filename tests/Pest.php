<?php

use IdeHelperCompanion\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->in(__DIR__);
uses(RefreshDatabase::class)->in(__DIR__.'/Unit');

expect()->extend('toContainWithMessage', function (mixed $needle, string $message = '') {
    if (is_string($this->value)) {
        Assert::assertStringContainsString((string) $needle, $this->value, $message);
    } else {
        ! is_iterable($this->value) && throw new RuntimeException('invalid');
        Assert::assertContains($needle, $this->value);
    }
});

/**
 * @template TMock
 *
 * @param  class-string<TMock>  $class
 * @return LegacyMockInterface&MockInterface&TMock
 */
function mockAndBind(string $class): LegacyMockInterface&MockInterface
{
    $mock = mock($class);
    app()->bind($class, fn () => $mock);

    return $mock;
}
