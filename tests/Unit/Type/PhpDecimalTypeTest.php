<?php

declare(strict_types=1);

namespace Gilek\DoctrinePhpDecimal\Test\Unit\Type;

use Decimal\Decimal;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Generator;
use Gilek\DoctrinePhpDecimal\Type\PhpDecimalType;
use PHPStan\Testing\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;

final class PhpDecimalTypeTest extends TestCase
{
    /** @var AbstractPlatform|MockObject */
    private $platform;

    /** @var PhpDecimalType */
    private $sut;

    /**
     * @return Generator<array>
     */
    public function correctDatabaseToPhpValueConversionDataProvider(): Generator
    {
        yield ['123.45', '123.450'];
        yield [123, '123.000'];
    }

    /**
     * @return Generator<array>
     */
    public function incorrectDatabaseToPhpValueConversionDataProvider(): Generator
    {
        yield [
            123.45,
            "Could not convert PHP value '123.45' of type 'double' to type 'Decimal\Decimal'. " .
            'Expected one of the following types: string, integer'
        ];

        yield [
            '1asd',
            'Could not convert database value "1asd" to Doctrine Type php_decimal. Expected format: N[.N]'
        ];

        yield [
            [],
            "Could not convert PHP value of type 'array' to type 'Decimal\Decimal'. " .
            'Expected one of the following types: string, integer'
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->platform = $this->createMock(AbstractPlatform::class);
        $this->sut = (new ReflectionClass(PhpDecimalType::class))->newInstanceWithoutConstructor();
    }

    /**
     * @test
     * @dataProvider correctDatabaseToPhpValueConversionDataProvider
     *
     * @param float|int|string $value
     * @param string $expectedValue
     */
    public function should_convert_database_value_to_php($value, string $expectedValue): void
    {
        $result = $this->sut->convertToPHPValue($value, $this->platform);

        $this->assertSame($result->tofixed(3), $expectedValue);
    }

    /**
     * @test
     * @dataProvider incorrectDatabaseToPhpValueConversionDataProvider
     *
     * @param mixed $value
     * @param string $expectedMessage
     */
    public function should_throw_exception_on_database_value_conversation_to_php($value, string $expectedMessage): void
    {
        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage($expectedMessage);
        $this->sut->convertToPHPValue($value, $this->platform);
    }

    /**
     * @test
     */
    public function should_not_convert_null_to_php_value(): void
    {
        $this->assertNull($this->sut->convertToPHPValue(null, $this->platform));
    }

    /**
     * @test
     */
    public function should_not_convert_decimal_to_php_value(): void
    {
        $decimal = new Decimal('123');
        $this->assertSame($decimal, $this->sut->convertToPHPValue($decimal, $this->platform));
    }
}
