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

namespace MrAndMrsSmith\TechTest\Calculator;

use Ds\Map;
use MrAndMrsSmith\TechTest\Calculator\Exception\DuplicatedEquationPart;
use MrAndMrsSmith\TechTest\Calculator\Exception\MissingVariableFromEquation;
use MrAndMrsSmith\TechTest\Equation;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Addition;
use MrAndMrsSmith\TechTest\Equation\Number;
use MrAndMrsSmith\TechTest\Equation\Operand;
use MrAndMrsSmith\TechTest\Equation\Part;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Division;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Multiplication;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Subtraction;
use MrAndMrsSmith\TechTest\Equation\Part\Rhs;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class EquationFactory
{
    /**
     * In formal mathematics, the order of variables is important and have explicit names. While in half
     * of the cases for this calculator, order is meaningless (ie 2+3 == 3+2 ), this is not true for all of them, e.g.
     * division (6/2 != 2/6), and subtraction (1-2 != 2-1).
     * To avoid hard to debug weirdness, we explicitly use the formal parts of a variable to decide precedence.
     */
    public function toEquation(Operand $operand, Number $first, Number $second): Equation
    {
        return match ($operand) {
            Operand::ADDITION   => $this->addition($first, $second, $operand),
            Operand::SUBTRACTION => $this->subtraction($first, $second, $operand),
            Operand::MULTIPLICATION => $this->multiplication($first, $second, $operand),
            Operand::DIVISION => $this->division($first, $second, $operand),
        };
    }

    private function addition(Number $first, Number $second, Operand $operand): Equation
    {
        $variables = $this->map($first, $second, Addition::AUGEND, Addition::ADDEND);

        return new Equation(
            $variables->get(Addition::AUGEND),
            $variables->get(Addition::ADDEND),
            $operand
        );
    }

    private function subtraction(Number $first, Number $second, Operand $operand): Equation
    {
        $variables = $this->map($first, $second, Subtraction::MINUEND, Subtraction::SUBTRAHEND);

        return new Equation(
            $variables->get(Subtraction::MINUEND),
            $variables->get(Subtraction::SUBTRAHEND),
            $operand,
        );
    }

    private function multiplication(Number $first, Number $second, Operand $operand): Equation
    {
        $variables = $this->map($first, $second, Multiplication::MULTIPLIER, Multiplication::MULTIPLICAND);

        return new Equation(
            $variables->get(Multiplication::MULTIPLIER),
            $variables->get(Multiplication::MULTIPLICAND),
            $operand,
        );
    }

    private function division(Number $first, Number $second, Operand $operand): Equation
    {
        $variables = $this->map($first, $second, Division::DIVIDEND, Division::DIVISOR);

        return new Equation(
            $variables->get(Division::DIVIDEND),
            $variables->get(Division::DIVISOR),
            $operand,
        );
    }

    private function map(Number $first, Number $second, Part ...$expected): Map
    {
        $variables = new Map();
        foreach ([$first, $second] as $number) {
            if ($variables->hasKey($number->part())) {
                throw new DuplicatedEquationPart($number->part(), $first, $second);
            }
            $variables->put($number->part(), $number);
        }

        foreach ($expected as $part) {
            if (!$variables->hasKey($part)) {
                throw new MissingVariableFromEquation($part, $expected);
            }
        }

        return $variables;
    }
}
