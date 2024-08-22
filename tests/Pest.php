<?php

use IdeHelperCompanion\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;

uses(TestCase::class)->in(__DIR__);
uses(RefreshDatabase::class)->in(__DIR__.'/Unit');

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
