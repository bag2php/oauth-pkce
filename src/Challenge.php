<?php

declare(strict_types=1);

namespace Bag2\OAuth\PKCE;

use Bag2\OAuth\PKCE\Method\AbstractMethod as PKCEMethod;
use Bag2\OAuth\PKCE\Method\S256;
use Bag2\OAuth\PKCE\Method\Plain;
use InvalidArgumentException;

/**
 * PKCE Challenge
 *
 * @copyright 2019 Baguette HQ
 * @license Apache-2.0
 * @author USAMI Kenta <tadsan@zonu.me>
 */
final class Challenge
{
    private const IMPLEMENTED_METHOD = [
        'S256' => S256::class,
        'plain' => Plain::class,
    ];

    /** @var string */
    private $challenge;
    /** @var PKCEMethod */
    private $method;

    public function __construct(string $code_challenge, PKCEMethod $method)
    {
        $this->challenge = $code_challenge;
        $this->method = $method;
    }

    /**
     * @param array{code_challenge:string,code_challenge_method:string} $code_challenge_and_method
     * @phan-param array{code_challenge:string,code_challenge_method:'S256'|'plain'} $code_challenge_and_method
     * @psalm-param array{code_challenge:string,code_challenge_method:'S256'|'plain'} $code_challenge_and_method
     */
    public static function fromArray(array $code_challenge_and_method): self
    {
        [
            'code_challenge' => $challenge,
            'code_challenge_method' => $method_name
        ] = $code_challenge_and_method;

        return new self($challenge, self::getMethodByName($method_name));
    }

    public static function fromVerifier(string $verifier, string $method_name): self
    {
        $method = self::getMethodByName($method_name);
        $challenge = $method->encode($verifier);

        return new self($challenge, $method);
    }

    /**
     * @phan-param 'S256'|'plain' $method_name
     * @psalm-param 'S256'|'plain' $method_name
     */
    public static function getMethodByName(string $method_name): PKCEMethod
    {
        $class = self::IMPLEMENTED_METHOD[$method_name] ?? false;

        if ($class === false) {
            throw new InvalidArgumentException('$method must be "S256" or "plain"');
        }

        return new $class;
    }

    public static function isValidCodeChallengeMethod(string $method): bool
    {
        return isset(self::IMPLEMENTED_METHOD[$method]);
    }

    public static function isValidCodeVerifier(string $code_verifier): bool
    {
        return \preg_match('/\A[A-Za-z0-9._~-]{43,128}\z/', $code_verifier) === 1;
    }

    /**
     * @return array{code_challenge:string,code_challenge_method:string}
     * @phan-return array{code_challenge:string,code_challenge_method:'S256'|'plain'}
     * @psalm-return array{code_challenge:string,code_challenge_method:'S256'|'plain'}
     */
    public function toArray(): array
    {
        return [
            'code_challenge' => $this->challenge,
            'code_challenge_method' => $this->method->name(),
        ];
    }

    public function verify(string $code_verifier): bool
    {
        return $this->method->verify($code_verifier, $this->challenge);
    }
}
