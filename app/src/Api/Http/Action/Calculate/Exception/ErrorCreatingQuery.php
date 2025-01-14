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

use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Shrikeh\AdrContracts\Action\Http\Exception\HttpActionException;
use Shrikeh\AdrContracts\MessageFactory\Http\Exception\HttpMessageFactoryException;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class ErrorCreatingQuery extends RuntimeException implements HttpActionException
{
    public function __construct(
        public readonly ServerRequestInterface $request,
        HttpMessageFactoryException $previous
    ) {
        parent::__construct(
            message: sprintf('Error creating query: %s', $previous->getMessage()),
            previous: $previous
        );
    }
}
