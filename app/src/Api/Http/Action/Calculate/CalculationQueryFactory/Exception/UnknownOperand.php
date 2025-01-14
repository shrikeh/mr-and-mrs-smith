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

namespace Api\Http\Action\Calculate\CalculationQueryFactory\Exception;

use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class UnknownOperand extends InvalidArgumentException implements CalculationQueryFactoryException
{
    public function __construct(public readonly string $operand, public readonly ServerRequestInterface $request)
    {
        parent::__construct(sprintf('Unknown operand "%s"', $this->operand));
    }
}
