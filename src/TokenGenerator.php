<?php

declare(strict_types=1);

namespace Bag2\OAuth\PKCE;

use InvalidArgumentException;
use RandomLib\Factory as RandomFactory;
use RandomLib\Generator as RandomGenerator;

/**
 * TokenGenerator
 *
 * @copyright 2019 Baguette HQ
 * @license Apache-2.0
 * @author USAMI Kenta <tadsan@zonu.me>
 */
class TokenGenerator
{
    /** @see https://tools.ietf.org/html/rfc7636#section-4.1 */
    private const CHARACTERS
        = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-._~';

    /** @var RandomGenerator */
    private $generator;

    public function __construct(RandomGenerator $random_generator = null)
    {
        $this->generator = $random_generator ?? $this->getRandomGenerator();
    }

    private function getRandomGenerator(): RandomGenerator
    {
        return (new RandomFactory)->getMediumStrengthGenerator();
    }

    public function generate(int $byte_length): string
    {
        if ($byte_length < 43 || 128 < $byte_length) {
            throw new InvalidArgumentException('$byte_length must be between 43 and 128');
        }

        return $this->generator->generateString($byte_length, self::CHARACTERS);
    }
}
