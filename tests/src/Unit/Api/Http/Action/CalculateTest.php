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

namespace Tests\Unit\Api\Http\Action;

use Api\Http\Action\Calculate;
use Api\Http\Action\Calculate\CalculationQueryFactory\Exception\UnknownOperand;
use Api\Http\Action\Calculate\Exception\ErrorCreatingQuery;
use Ergebnis\Http\Method;
use MrAndMrsSmith\TechTest\Equation\Operand;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\AdrContracts\MessageFactory\Http\HttpQueryFactory;
use Shrikeh\AdrContracts\Responder\HttpResponder;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Query;
use Shrikeh\App\Message\Result;
use Shrikeh\App\Query\QueryBus\CorrelatingQueryBus;
use Teapot\StatusCode;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class CalculateTest extends TestCase
{
    use ProphecyTrait;

    public function testItThrowsAnExceptionIfTheQueryCannotBeCreated(): void
    {
        $serverRequest = (new ServerRequest(Method::GET, '/foo'))->withQueryParams(['operand' => 'foo']);
        $httpQueryFactory = $this->prophesize(HttpQueryFactory::class);

        $previous = new UnknownOperand('bar', $serverRequest);
        $httpQueryFactory->build($serverRequest)->willThrow($previous);

        $queryBus = new class () implements CorrelatingQueryBus {
            public function handle(Correlated&Query $query): Result&Correlated
            {
            }
        };

        $calculate = new Calculate(
            $queryBus,
            $httpQueryFactory->reveal(),
            $this->prophesize(HttpResponder::class)->reveal()
        );

        $this->expectExceptionObject(new ErrorCreatingQuery($serverRequest, $previous));

        $calculate($serverRequest);
    }

    public function testItRespondsIfTheOperandIsMissing(): void
    {
        $serverRequest = new ServerRequest(Method::GET, '/foo');
        $httpQueryFactory = $this->prophesize(HttpQueryFactory::class);
        $response = new Response(StatusCode::OK);

        $httpQueryFactory->build($serverRequest)->shouldNotBeCalled();
        $httpResponder = $this->prophesize(HttpResponder::class);

        $httpResponder->respond()->willReturn($response);


        $queryBus = new class () implements CorrelatingQueryBus {
            public function handle(Correlated&Query $query): Result&Correlated
            {
            }
        };

        $calculate = new Calculate(
            $queryBus,
            $httpQueryFactory->reveal(),
            $httpResponder->reveal()
        );

        $this->assertSame($response, $calculate($serverRequest));
    }

    public function testItRunsACalculationQuery(): void
    {
        $serverRequest = (new ServerRequest(Method::GET, '/foo'))
            ->withQueryParams(['operand' => Operand::MULTIPLICATION->value]);
        $response = new Response(StatusCode::OK);

        $query = $this->prophesize(Query::class)
            ->willImplement(Correlated::class)
            ->reveal();

        $httpQueryFactory = $this->prophesize(HttpQueryFactory::class);

        $httpQueryFactory->build($serverRequest)->willReturn($query);

        $result = $this->prophesize(Result::class)
            ->willImplement(Correlated::class)
            ->reveal();

        $queryBus = new readonly class ($query, $result) implements CorrelatingQueryBus {
            public function __construct(public Correlated&Query $query, public Result&Correlated $result)
            {
            }
            public function handle(Correlated&Query $query): Result&Correlated
            {
                if ($query === $this->query) {
                    return $this->result;
                }
            }
        };

        $responder = $this->prophesize(HttpResponder::class);
        $responder->respond($result)->willReturn($response);

        $calculate = new Calculate(
            $queryBus,
            $httpQueryFactory->reveal(),
            $responder->reveal(),
        );


        $this->assertSame($response, $calculate($serverRequest));
    }
}
