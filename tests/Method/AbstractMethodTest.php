<?php

declare(strict_types=1);

namespace Bag2\OAuth\PKCE\Method;

/**
 * Test case for PKCE "plain" method
 *
 * @copyright 2019 Baguette HQ
 * @license Apache-2.0
 * @author USAMI Kenta <tadsan@zonu.me>
 */
class AbstractMethodTest extends \Bag2\OAuth\PKCE\TestCase
{
    /** @var \Bag2\OAuth\PKCE\Method\AbstractMethod */
    private $subject;

    public function setUp(): void
    {
        $this->subject = new class() extends AbstractMethod {
            public function name(): string
            {
                return 'anonymous';
            }

            public function encode(string $string): string
            {
                return \str_rot13($string);
            }
        };
    }

    public function test_encode()
    {
        $code_verifier = $expected = 'verified';
        $actual = $this->subject->encode('irevsvrq');

        $this->assertSame($expected, $actual);
    }

    public function test_verify()
    {
        $code_verifier = $expected = 'verified';

        $this->assertTrue($this->subject->verify($code_verifier, 'irevsvrq'));
    }

    public function test_set_property()
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage('Do not set property');

        $this->subject->prop = null;
    }
}
