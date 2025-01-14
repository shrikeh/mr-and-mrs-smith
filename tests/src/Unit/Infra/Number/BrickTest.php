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

namespace Tests\Unit\Infra\Number;

use Infra\Number\Brick;
use Infra\Number\Brick\Exception\InputIsNotANumber;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Addition;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Subtraction;
use PHPUnit\Framework\TestCase;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class BrickTest extends TestCase
{
    public function testItReturnsThePart(): void
    {
        $number = Brick::create('1', Addition::ADDEND);

        $this->assertSame(Addition::ADDEND, $number->part());
    }

    public function testItReturnsTheString(): void
    {
        $number = Brick::create(1.234873483745, Subtraction::MINUEND);

        $this->assertSame('1.234873483745', $number->toString());
    }

    public function testItThrowsAnExceptionIfTheNumberIsInvalid(): void
    {
        $value = 'sdjfhjdffh';
        $part = Subtraction::SUBTRAHEND;

        $this->expectExceptionObject(new InputIsNotANumber($value, $part));

        Brick::create($value, $part);
    }
}
