<?php

declare(strict_types=1);

namespace Gilek\DoctrinePhpDecimal\Type;

use BadMethodCallException;
use Decimal\Decimal;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use DomainException;
use TypeError;

class PhpDecimalType extends Type
{
    public const NAME = 'php_decimal';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getDecimalTypeDeclarationSQL($column);
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof Decimal) {
            throw ConversionException::conversionFailedInvalidType(
                $value,
                $this->getName(),
                ['null', Decimal::class]
            );
        }

        return (string) $value;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof Decimal) {
            return $value;
        };

        if (!is_string($value) && !is_integer($value)) {
            throw ConversionException::conversionFailedInvalidType(
                $value,
                Decimal::class,
                ['string', 'integer']
            );
        }

        try {
            $decimal = new Decimal($value);
        } catch (BadMethodCallException | TypeError | DomainException $exception) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                'N[.N]'
            );
        }

        return $decimal;
    }
}
