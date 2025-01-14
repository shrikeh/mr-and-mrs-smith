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

namespace Tests\Unit\MrAndMrsSmith\Calculator;

use Infra\Number\Brick;
use MrAndMrsSmith\TechTest\Calculator\EquationFactory;
use MrAndMrsSmith\TechTest\Calculator\Exception\DuplicatedEquationPart;
use MrAndMrsSmith\TechTest\Calculator\Exception\MissingVariableFromEquation;
use MrAndMrsSmith\TechTest\Equation\Operand;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Division;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Multiplication;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Subtraction;
use PHPUnit\Framework\TestCase;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class EquationFactoryTest extends TestCase
{
    public function testItThrowsAnExceptionIfThereIsDuplicationOfParts(): void
    {
        $first = Brick::create(1, Subtraction::SUBTRAHEND);
        $second = Brick::create(1, Subtraction::SUBTRAHEND);

        $this->expectExceptionObject(new DuplicatedEquationPart(Subtraction::SUBTRAHEND, $first, $second));

        $equationFactory = new EquationFactory();
        $equationFactory->toEquation(Operand::SUBTRACTION, $first, $second);
    }

    public function testItThrowsAnExceptionIfThereIsAMismatch(): void
    {
        $first = Brick::create(1, Division::DIVISOR);
        $second = Brick::create(1, Multiplication::MULTIPLICAND);

        $this->expectExceptionObject(new MissingVariableFromEquation(Division::DIVIDEND, [
            Division::DIVIDEND,
            Division::DIVISOR,
        ]));

        $equationFactory = new EquationFactory();
        $equationFactory->toEquation(Operand::DIVISION, $first, $second);
    }

    public function testItReturnsEquationsInCorrectOrder(): void
    {
        $equationFactory = new EquationFactory();

        $divisor = Brick::create(1, Division::DIVISOR);
        $dividend = Brick::create(1, Division::DIVIDEND);

        $equation = $equationFactory->toEquation(
            operand: Operand::DIVISION,
            first: $divisor,
            second: $dividend,
        );

        $this->assertSame($dividend, $equation->first);
        $this->assertSame($divisor, $equation->second);
    }
}
