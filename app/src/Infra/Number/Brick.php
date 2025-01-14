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

namespace Infra\Number;

use Brick\Math\BigDecimal;
use Brick\Math\Exception\MathException;
use Infra\Number\Brick\Exception\InputIsNotANumber;
use MrAndMrsSmith\TechTest\Equation\Number;
use MrAndMrsSmith\TechTest\Equation\Part;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class Brick implements Number
{
    public static function create(mixed $value, Part $part): Number
    {
        try {
            $decimal = BigDecimal::of($value);

            return new self($decimal, $part);
        } catch (MathException $exc) {
            throw new InputIsNotANumber($value, $part);
        }
    }

    private function __construct(
        private BigDecimal $value,
        private Part $part,
    ) {
    }

    public function part(): Part
    {
        return $this->part;
    }

    public function toString(): string
    {
        return (string) $this->value;
    }
}
