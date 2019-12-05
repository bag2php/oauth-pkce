<?php

declare(strict_types=1);

namespace Bag2\OAuth\PKCE\Method;

/**
 * Abstract class of PKCE verify method
 *
 * @copyright 2019 Baguette HQ
 * @license Apache-2.0
 * @author USAMI Kenta <tadsan@zonu.me>
 */
abstract class AbstractMethod
{
    public function __set($_name, $_value)
    {
        throw new \OutOfRangeException('Do not set property');
    }

    abstract public function encode(string $string): string;

    abstract public function name(): string;

    public function verify(string $code_verifier, string $code_challenge): bool
    {
        return \hash_equals($this->encode($code_verifier), $code_challenge);
    }
}
