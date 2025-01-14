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

namespace MrAndMrsSmith\TechTest;

use Generator;
use MrAndMrsSmith\TechTest\Calculator\EquationFactory;
use MrAndMrsSmith\TechTest\Calculator\FloatingPointCalculator;
use MrAndMrsSmith\TechTest\Calculator\NumberFactory;
use MrAndMrsSmith\TechTest\Equation\Number;
use MrAndMrsSmith\TechTest\Equation\Operand;
use MrAndMrsSmith\TechTest\Equation\Part\Rhs;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class Calculator
{
    public function __construct(
        private EquationFactory $equationFactory,
        private FloatingPointCalculator $floatingPointCalculator,
        private NumberFactory $numberFactory
    ) {
    }

    public function calculate(Number $first, Number $second, Operand $operand): Calculation
    {
        $equation = $this->equationFactory->toEquation($operand, $first, $second);

        $result = match ($operand) {
            Operand::ADDITION   => $this->add($equation),
            Operand::SUBTRACTION => $this->subtract($equation),
            Operand::MULTIPLICATION => $this->multiply($equation),
            Operand::DIVISION => $this->divide($equation),
        };

        return new Calculation($equation, $result);
    }

    private function add(Equation $equation): Number
    {
        return  $this->numberFactory->create(
            $this->floatingPointCalculator->add($equation->first->toString(), $equation->second->toString()),
            Rhs::ADDITION,
        );
    }

    private function subtract(Equation $equation): Number
    {
        return  $this->numberFactory->create(
            $this->floatingPointCalculator->subtract($equation->first->toString(), $equation->second->toString()),
            Rhs::SUBTRACTION,
        );
    }

    private function divide(Equation $equation): Number
    {
        return $this->numberFactory->create(
            $this->floatingPointCalculator->divide($equation->first->toString(), $equation->second->toString()),
            Rhs::DIVISION,
        );
    }

    private function multiply(Equation $equation): Number
    {
        return $this->numberFactory->create(
            $this->floatingPointCalculator->multiply($equation->first->toString(), $equation->second->toString()),
            Rhs::MULTIPLICATION,
        );
    }
}
