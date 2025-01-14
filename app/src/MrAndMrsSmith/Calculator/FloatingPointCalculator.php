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

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
interface FloatingPointCalculator
{
    public function add(string $augend, string $addend): string;

    public function subtract(string $minuend, string $subtrahend): string;
    public function divide(string $dividend, string $divisor): string;

    public function multiply(string $multiplicand, string $multiplier): string;
}
