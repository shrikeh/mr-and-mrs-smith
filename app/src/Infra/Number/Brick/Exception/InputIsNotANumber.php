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

namespace Infra\Number\Brick\Exception;

use MrAndMrsSmith\TechTest\Equation\Number\Exception\NumberException;
use MrAndMrsSmith\TechTest\Equation\Part;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class InputIsNotANumber extends \InvalidArgumentException implements NumberException
{
    public function __construct(public readonly mixed $number, public readonly Part $part)
    {
        parent::__construct(sprintf(
            'Could not parse the %s from input of %s',
            $this->part->toString(),
            $this->number,
        ));
    }
}
