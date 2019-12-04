<?php

declare(strict_types=1);

namespace Bag2\OAuth\PKCE;

use InvalidArgumentException;

/**
 * Test case for TokenGenerator
 *
 * @copyright 2019 Baguette HQ
 * @license Apache-2.0
 * @author USAMI Kenta <tadsan@zonu.me>
 */
class TokenGeneratorTest extends \Bag2\OAuth\PKCE\TestCase
{
    /** @var \Bag2\OAuth\PKCE\TokenGenerator */
    private $subject;

    public function setUp(): void
    {
        $this->subject = new TokenGenerator();
    }

    /**
     * @dataProvider provider
     */
    public function test(int $byte_length)
    {
        foreach (\range(0, 10) as $_) {
            $actual = $this->subject->generate($byte_length);

            $this->assertSame($byte_length, \strlen($actual));
        }
    }

    public function provider()
    {
        foreach (\range(43, 128) as $length) {
            yield "length {$length} bytes" => [$length];
        }
    }

    /**
     * @dataProvider provider_raiseException
     */
    public function test_raiseException(int $byte_length)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$byte_length must be between 43 and 128');

        $_ = $this->subject->generate($byte_length);
    }

    public function provider_raiseException()
    {
        return [
            [-1],
            [0],
            [1],
            [42],
            [129],
            [999],
            [PHP_INT_MAX],
        ];
    }
}
