<?php

declare(strict_types=1);

namespace Bag2\OAuth\PKCE\Method;

/**
 * Test case for S256 method class
 *
 * @copyright 2019 Baguette HQ
 * @license Apache-2.0
 * @author USAMI Kenta <tadsan@zonu.me>
 */
class S256Test extends \Bag2\OAuth\PKCE\TestCase
{
    /** @var \Bag2\OAuth\PKCE\Method\S256 */
    private $method;

    public function setUp(): void
    {
        $this->method = new S256();
    }

    /**
     * @dataProvider provider_verify
     */
    public function test_verify(bool $expected, $code_challenge, $code_verifier)
    {
        $actual = $this->method->verify($code_verifier, $code_challenge);

        $this->assertSame($expected, $actual);
    }

    /**
     * @dataProvider provider_encode
     */
    public function test_encode(string $expected, string $input)
    {
        $this->assertSame($expected, $this->method->encode($input));
    }

    public function provider_verify()
    {
        return [
            [
                false, '', '',
            ],
            [
                false,
                'dBjftJeZ4CVP-mB92K27uhbUJU1p1r_wW1gFWFOEjXk',
                'dBjftJeZ4CVP-mB92K27uhbUJU1p1r_wW1gFWFOEjXk',
            ],
            [
                true,
                'E9Melhoa2OwvFrEMTJguCHaoeK1t8URWbuGJSstw-cM',
                'dBjftJeZ4CVP-mB92K27uhbUJU1p1r_wW1gFWFOEjXk',
            ],
        ];
    }

    public function provider_encode()
    {
        return [
            [
                'E9Melhoa2OwvFrEMTJguCHaoeK1t8URWbuGJSstw-cM',
                'dBjftJeZ4CVP-mB92K27uhbUJU1p1r_wW1gFWFOEjXk',
            ],
        ];
    }
}
