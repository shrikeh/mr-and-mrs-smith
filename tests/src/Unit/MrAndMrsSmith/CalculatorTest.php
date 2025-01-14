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

namespace Tests\Unit\MrAndMrsSmith;

use Infra\Calculator\BrickCalculator;
use Infra\Number\Brick;
use Infra\Number\Factory;
use MrAndMrsSmith\TechTest\Calculator;
use MrAndMrsSmith\TechTest\Calculator\EquationFactory;
use MrAndMrsSmith\TechTest\Equation\Operand;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Addition;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Division;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Multiplication;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Subtraction;
use MrAndMrsSmith\TechTest\Equation\Part\Rhs;
use PHPUnit\Framework\TestCase;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class CalculatorTest extends TestCase
{
    public function testItAddsAndSorts(): void
    {
        $first = Brick::create(1.7654, Addition::AUGEND);
        $second = Brick::create(1, Addition::ADDEND);
        $calculator = new Calculator(
            new EquationFactory(),
            new BrickCalculator(),
            new Factory()
        );

        $calculation = $calculator->calculate($second, $first, Operand::ADDITION);

        $this->assertSame(
            '2.7654',
            $calculation->number->toString(),
        );

        $this->assertSame(Rhs::ADDITION, $calculation->number->part());
    }

    public function testItSubtractsAndSorts(): void
    {
        $first = Brick::create(1.7654, Subtraction::SUBTRAHEND);
        $second = Brick::create(1, Subtraction::MINUEND);
        $calculator = new Calculator(
            new EquationFactory(),
            new BrickCalculator(),
            new Factory()
        );

        $calculation = $calculator->calculate($second, $first, Operand::SUBTRACTION);

        $this->assertSame(
            '-0.7654',
            $calculation->number->toString(),
        );

        $this->assertSame(Rhs::SUBTRACTION, $calculation->number->part());
    }

    public function testItMultiplesAndSorts(): void
    {
        $first = Brick::create(9.2345, Multiplication::MULTIPLICAND);
        $second = Brick::create('1.21', Multiplication::MULTIPLIER);
        $calculator = new Calculator(
            new EquationFactory(),
            new BrickCalculator(),
            new Factory()
        );

        $calculation = $calculator->calculate($second, $first, Operand::MULTIPLICATION);

        $this->assertSame(
            '11.173745',
            $calculation->number->toString(),
        );

        $this->assertSame(Rhs::MULTIPLICATION, $calculation->number->part());
    }

    public function testItDividesAndSorts(): void
    {
        $first = Brick::create(-16.2345, Division::DIVISOR);
        $second = Brick::create('1.21', Division::DIVIDEND);
        $calculator = new Calculator(
            new EquationFactory(),
            new BrickCalculator(),
            new Factory()
        );

        $calculation = $calculator->calculate($second, $first, Operand::DIVISION);

        $this->assertSame(
            '-0.074533',
            $calculation->number->toString(),
        );

        $this->assertSame(Rhs::DIVISION, $calculation->number->part());
    }
}
