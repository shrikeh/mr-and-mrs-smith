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

use Ds\Map;
use MrAndMrsSmith\TechTest\Equation;
use MrAndMrsSmith\TechTest\Equation\Part;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class MissingVariableFromEquation extends \InvalidArgumentException implements EquationFactoryException
{
    public function __construct(
        public readonly Part $missing,
        public readonly array $expected
    ) {
        parent::__construct(sprintf(
            'Missing variable part %s',
            $missing->toString(),
        ));
    }
}
