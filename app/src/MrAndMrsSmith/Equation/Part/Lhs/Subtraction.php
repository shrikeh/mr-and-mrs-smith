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

namespace MrAndMrsSmith\TechTest\Equation\Part\Lhs;

use MrAndMrsSmith\TechTest\Equation\Part\Lhs;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
enum Subtraction: string implements Lhs
{
    case MINUEND = 'minuend';

    case SUBTRAHEND = 'subtrahend';

    public function toString(): string
    {
        return $this->value;
    }
}
