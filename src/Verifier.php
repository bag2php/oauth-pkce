<?php

declare(strict_types=1);

namespace Bag2\OAuth\PKCE;

use Bag2\OAuth\PKCE\Method\AbstractMethod as PKCEMethod;
use Bag2\OAuth\PKCE\Method\S256;
use Bag2\OAuth\PKCE\Method\Plain;
use InvalidArgumentException;

/**
 * PKCE Verifier
 *
 * @copyright 2019 Baguette HQ
 * @license Apache-2.0
 * @author USAMI Kenta <tadsan@zonu.me>
 */
final class Verifier
{
    private const IMPLEMENTED_METHOD = [
        'S256' => S256::class,
        'plain' => Plain::class,
    ];

    /** @var string */
    private $verifier;
    /** @var PKCEMethod */
    private $method;

    public function __construct(string $code_verifier, PKCEMethod $method)
    {
        $this->verifier = $code_verifier;
        $this->method = $method;
    }

    /**
     * @phan-param 'S256'|'plain' $method
     * @psalm-param 'S256'|'plain' $method
     */
    public static function fromString(string $code_verifier, string $method): self
    {
        $class = self::IMPLEMENTED_METHOD[$method] ?? false;

        if ($class === false) {
            throw new InvalidArgumentException('$method must be "S256" or "plain"');
        }

        return new self($code_verifier, new $class());
    }

    public function verify(string $code_challenge): bool
    {
        return $this->method->verify($this->verifier, $code_challenge);
    }
}
