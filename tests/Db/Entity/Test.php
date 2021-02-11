<?php

declare(strict_types=1);

namespace Gilek\DoctrinePhpDecimal\Test\Db\Entity;

use Decimal\Decimal;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="test")
 */
class Test
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Decimal
     *
     * @ORM\Column(name="value_string", type="php_decimal")
     */
    private $valueString;

    /**
     * @var Decimal
     *
     * @ORM\Column(name="value_integer", type="php_decimal")
     */
    private $valueInteger;

    /**
     * @var Decimal
     *
     * @ORM\Column(name="value_float", type="php_decimal")
     */
    private $valueFloat;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Decimal
     */
    public function getValueString(): Decimal
    {
        return $this->valueString;
    }

    /**
     * @param Decimal $valueString
     *
     * @return self
     */
    public function setValueString(Decimal $valueString): self
    {
        $this->valueString = $valueString;

        return $this;
    }

    /**
     * @return Decimal
     */
    public function getValueInteger(): Decimal
    {
        return $this->valueInteger;
    }

    /**
     * @param Decimal $valueInteger
     *
     * @return self
     */
    public function setValueInteger(Decimal $valueInteger): self
    {
        $this->valueInteger = $valueInteger;

        return $this;
    }

    /**
     * @return Decimal
     */
    public function getValueFloat(): Decimal
    {
        return $this->valueFloat;
    }

    /**
     * @param Decimal $valueFloat
     *
     * @return self
     */
    public function setValueFloat(Decimal $valueFloat): self
    {
        $this->valueFloat = $valueFloat;

        return $this;
    }
}
