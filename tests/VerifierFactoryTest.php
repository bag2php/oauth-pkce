<?php

declare(strict_types=1);

namespace Bag2\OAuth\PKCE;

use InvalidArgumentException;
use RandomLib\Factory as RandomFactory;
use RuntimeException;

/**
 * Test case for VerifierFactory
 *
 * @copyright 2019 Baguette HQ
 * @license Apache-2.0
 * @author USAMI Kenta <tadsan@zonu.me>
 */
class VerifierFactoryTest extends \Bag2\OAuth\PKCE\TestCase
{
    /** @var \Bag2\OAuth\PKCE\VerifierFactory */
    private $subject;

    public function setUp(): void
    {
        $this->subject = new VerifierFactory();
    }

    /**
     * @dataProvider provider
     */
    public function test(string $method_name)
    {
        $byte_length = 128;
        $actual = $this->subject->generate($byte_length, $method_name);

        $this->assertIsString($actual[0]);
        $this->assertSame($byte_length, \strlen($actual[0]));
        $this->assertInstanceOf(Challenge::class, $actual[1]);
    }

    public function provider()
    {
        return [
            'method plain' => ['plain'],
            'method S256' => ['S256'],
        ];
    }

    /**
     * @dataProvider provider_raiseException
     */
    public function test_raiseException(int $byte_length)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$byte_length must be between 43 and 128');

        $_ = $this->subject->generate($byte_length, 'plain');
    }

    public function provider_raiseException()
    {
        return [
            [-1],
            [0],
            [1],
            [42],
            [129],
            [999],
            [PHP_INT_MAX],
        ];
    }

    public function test_customRandomFactory()
    {
        $message = 'jfladkfjlaksfjafdasfa';
        $random_factory = new class($message) extends TokenGenerator {
             private $message;

             public function __construct(string $message)
             {
                 parent::__construct();

                 $this->message = $message;
             }

             public function generate(int $byte_length): string
             {
                 throw new RuntimeException($this->message);
             }
        };

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage($message);

        $subject = new VerifierFactory($random_factory);
        $_ = $subject->generate(256, 'piyo');
    }
}
