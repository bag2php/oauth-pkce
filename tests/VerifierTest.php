<?php

declare(strict_types=1);

namespace Bag2\OAuth\PKCE;

use InvalidArgumentException;

/**
 * Test case for PKCE Verifier
 *
 * @copyright 2019 Baguette HQ
 * @license Apache-2.0
 * @author USAMI Kenta <tadsan@zonu.me>
 */
class VerifierTest extends \Bag2\OAuth\PKCE\TestCase
{
    /**
     * @dataProvider provider
     */
    public function test(string $method, $code_verifier, $code_challenge)
    {
        $subject = Verifier::fromArray([
            'code_verifier' => $code_verifier,
            'code_challenge_method' => $method,
        ]);

        $this->assertTrue($subject->verify($code_challenge));
    }

    /**
     * @dataProvider provider
     */
    public function test_isValidCodeVerifier(string $method, $code_verifier)
    {
        $this->assertTrue(Verifier::isValidCodeVerifier($code_verifier));
    }

    public function provider()
    {
        return [
            [
                'plain',
                'dBjftJeZ4CVP-mB92K27uhbUJU1p1r_wW1gFWFOEjXk',
                'dBjftJeZ4CVP-mB92K27uhbUJU1p1r_wW1gFWFOEjXk',
            ],
            [
                'S256',
                'dBjftJeZ4CVP-mB92K27uhbUJU1p1r_wW1gFWFOEjXk',
                'E9Melhoa2OwvFrEMTJguCHaoeK1t8URWbuGJSstw-cM',
            ],
        ];
    }

    /**
     * @dataProvider methodProvider
     */
    public function test_isValidCodeChallengeMethod(bool $expected, string $method)
    {
        $this->assertSame($expected, Verifier::isValidCodeChallengeMethod($method));
    }

    public function methodProvider()
    {
        return [
            [true, 'plain'],
            [true, 'S256'],
            [false, ''],
            [false, '<?php'],
            [false, 's256'],
            [false, 's256 '],
            [false, "S256\n"],
        ];
    }

    /**
     * @dataProvider provider_raiseException
     */
    public function test_raiseException(string $code_challenge_method)
    {
        $code_verifier = 'E9Melhoa2OwvFrEMTJguCHaoeK1t8URWbuGJSstw-cM';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$method must be "S256" or "plain"');

        $_ = Verifier::fromArray(\compact('code_verifier', 'code_challenge_method'));
    }

    public function provider_raiseException()
    {
        return [
            [''],
            ['foo'],
        ];
    }
}
