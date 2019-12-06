<?php

declare(strict_types=1);

namespace Bag2\OAuth\PKCE;

/**
 * PKCE VerifierFactory
 *
 * @copyright 2019 Baguette HQ
 * @license Apache-2.0
 * @author USAMI Kenta <tadsan@zonu.me>
 */
final class VerifierFactory
{
    /** @var TokenGenerator */
    private $token_generator;

    public function __construct(TokenGenerator $token_generator = null)
    {
        $this->token_generator = $token_generator ?? new TokenGenerator();
    }

    /**
     * @return array{0:string,1:Challenge}
     */
    public function generate(int $byte_length, string $method_name): array
    {
        $verifier = $this->token_generator->generate($byte_length);

        return [$verifier, Challenge::fromVerifier($verifier, $method_name)];
    }
}
