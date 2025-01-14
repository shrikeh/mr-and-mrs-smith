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

use MrAndMrsSmith\TechTest\Calculator\NumberFactory;
use MrAndMrsSmith\TechTest\Equation\Number;
use MrAndMrsSmith\TechTest\Equation\Part;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class Factory implements NumberFactory
{

    public function create(mixed $value, Part $part): Number
    {
        return Brick::create($value, $part);
    }
}
