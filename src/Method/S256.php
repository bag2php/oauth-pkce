<?php

declare(strict_types=1);

namespace Bag2\OAuth\PKCE\Method;

/**
 * PKCE verify method "s256" using SHA-256
 *
 * @copyright 2019 Baguette HQ
 * @license Apache-2.0
 * @author USAMI Kenta <tadsan@zonu.me>
 */
final class S256 extends AbstractMethod
{
    public function encode(string $string): string
    {
        $hash = \hash('sha256', $string, true);

        return \rtrim(\strtr(\base64_encode($hash), '+/', '-_'), '=');
    }
}
