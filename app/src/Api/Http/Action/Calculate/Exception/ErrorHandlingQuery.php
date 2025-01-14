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

namespace Api\Http\Action\Calculate\Exception;

use RuntimeException;
use Shrikeh\AdrContracts\Action\Http\Exception\HttpActionException;
use Shrikeh\App\Message\Query;
use Shrikeh\App\Query\QueryBus\Exception\QueryBusException;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class ErrorHandlingQuery extends RuntimeException implements HttpActionException
{
    public function __construct(public readonly Query $query, QueryBusException $previous)
    {
        parent::__construct(
            message: sprintf('Error handling query of type %s: %s', get_class($query), $previous->getMessage()),
            previous: $previous,
        );
    }
}
