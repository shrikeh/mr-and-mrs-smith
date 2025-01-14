<?php

/*
 * This file is part of Barney's Symfony skeleton for Domain-Driven Design
 *
 * (c) Barney Hanlon <symfony@shrikeh.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Infra\Calculator;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use MrAndMrsSmith\TechTest\Calculator\FloatingPointCalculator;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class BrickCalculator implements FloatingPointCalculator
{

    public const int DEFAULT_SCALE = 6;
    public const RoundingMode DEFAULT_ROUNDING_MODE = RoundingMode::UP;

    public function __construct(
        private int $scale = self::DEFAULT_SCALE,
        private RoundingMode $roundingMode = self::DEFAULT_ROUNDING_MODE
    ) {

    }

    public function add(string $augend, string $addend): string
    {
        return (string) BigDecimal::of($augend)->plus(BigDecimal::of($addend));
    }

    public function subtract(string $minuend, string $subtrahend): string
    {

        return (string) BigDecimal::of($minuend)->minus(BigDecimal::of($subtrahend));
    }

    public function divide(string $dividend, string $divisor): string
    {
        return (string) BigDecimal::of($dividend)->dividedBy(
            that: BigDecimal::of($divisor),
            scale: $this->scale,
            roundingMode: $this->roundingMode,
        )->toFloat();
    }

    public function multiply(string $multiplicand, string $multiplier): string
    {
        return (string) BigDecimal::of($multiplicand)->multipliedBy(BigDecimal::of($multiplier));
    }
}
