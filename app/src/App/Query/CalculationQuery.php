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

namespace App\Query;

use MrAndMrsSmith\TechTest\Equation\Number;
use MrAndMrsSmith\TechTest\Equation\Operand;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation\Traits\WithCorrelation;
use Shrikeh\App\Message\Query;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class CalculationQuery implements Query, Correlated
{
    use WithCorrelation;

    public function __construct(public Operand $operand, public Number $first, public Number $second)
    {
    }
}
