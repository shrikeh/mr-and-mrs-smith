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

namespace Tests\Unit\Infra\Calculator\BrickCalculator;

use Infra\Calculator\BrickCalculator;
use PHPUnit\Framework\TestCase;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class AdditionsTest extends TestCase
{
    private BrickCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new BrickCalculator();
    }

    public function testItCanAddIntegers(): void
    {
        $this->assertSame(
            '8',
            $this->calculator->add('1', '7')
        );
    }

    public function testItCanAddFloats(): void
    {
        $this->assertSame(
            '3094759487.8633504372',
            $this->calculator->add('1052.7349875642', '3094758435.128362873')
        );
    }

    public function testItCanAddNegativeNumbers(): void
    {
        $this->assertSame(
            '-329807322736.8677',
            $this->calculator->add('-1023849238.6443', '-328783473498.2234')
        );
    }

    public function testItCanAddZero(): void
    {
        $this->assertSame(
            '-329807322736.8677',
            $this->calculator->add('-329807322736.8677', '0')
        );
    }
}
