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

use MrAndMrsSmith\TechTest\Equation\Number;
use MrAndMrsSmith\TechTest\Equation\Operand;
use MrAndMrsSmith\TechTest\Equation\Part\Rhs;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class Equation
{
    public function __construct(public Number $first, public Number $second, public Operand $operand)
    {

    }
}
