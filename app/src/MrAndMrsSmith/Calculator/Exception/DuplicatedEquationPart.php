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

namespace MrAndMrsSmith\TechTest\Calculator\Exception;

use InvalidArgumentException;
use MrAndMrsSmith\TechTest\Equation\Number;
use MrAndMrsSmith\TechTest\Equation\Part;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class DuplicatedEquationPart extends InvalidArgumentException implements EquationFactoryException
{
    public function __construct(
        public readonly Part $part,
        public readonly Number $first,
        public readonly Number $second
    ) {
        parent::__construct(
            sprintf('Equation already has one part %s', $this->part->toString())
        );
    }
}
