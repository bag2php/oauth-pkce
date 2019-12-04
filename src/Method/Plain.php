<?php

declare(strict_types=1);

namespace Bag2\OAuth\PKCE\Method;

/**
 * PKCE verify method "plain"
 *
 * @copyright 2019 Baguette HQ
 * @license Apache-2.0
 * @author USAMI Kenta <tadsan@zonu.me>
 */
final class Plain extends AbstractMethod
{
    public function encode(string $string): string
    {
        return $string;
    }

    public function verify(string $code_verifier, string $code_challenge): bool
    {
        return \hash_equals($code_verifier, $code_challenge);
    }
}
