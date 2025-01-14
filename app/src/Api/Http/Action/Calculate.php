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

namespace Api\Http\Action;

use Api\Http\Action\Calculate\Exception\ErrorCreatingQuery;
use Api\Http\Action\Calculate\Exception\ErrorHandlingQuery;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shrikeh\AdrContracts\MessageFactory\Http\Exception\HttpMessageFactoryException;
use Shrikeh\AdrContracts\MessageFactory\Http\HttpQueryFactory;
use Shrikeh\AdrContracts\Responder\HttpResponder;
use Shrikeh\ApiContext\Http\Action;
use Shrikeh\App\Query\QueryBus\CorrelatingQueryBus;
use Shrikeh\App\Query\QueryBus\Exception\QueryBusException;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class Calculate implements Action
{
    public function __construct(
        private CorrelatingQueryBus $queryBus,
        private HttpQueryFactory $calculationQueryFactory,
        private HttpResponder $httpResponder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if (array_key_exists('operand', $request->getQueryParams())) {
            return $this->calculateFromRequest($request);
        }

        return $this->httpResponder->respond();
    }

    private function calculateFromRequest(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $calculationQuery = $this->calculationQueryFactory->build($request);
        } catch (HttpMessageFactoryException $exc) {
            throw new ErrorCreatingQuery($request, $exc);
        }
        $calculated = $this->queryBus->handle($calculationQuery);

        return $this->httpResponder->respond($calculated);
    }
}
