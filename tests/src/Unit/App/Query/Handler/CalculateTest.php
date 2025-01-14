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

namespace Tests\Unit\App\Query\Handler;

use App\Query\CalculationQuery;
use App\Query\Handler\Calculate;
use Infra\Calculator\BrickCalculator;
use Infra\Number\Brick;
use Infra\Number\Factory;
use MrAndMrsSmith\TechTest\Calculator;
use MrAndMrsSmith\TechTest\Calculator\EquationFactory;
use MrAndMrsSmith\TechTest\Equation\Operand;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Addition;
use MrAndMrsSmith\TechTest\Equation\Part\Rhs;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Log\LoggerInterface;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory\UlidCorrelation;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class CalculateTest extends TestCase
{
    use ProphecyTrait;

    public function testItCalculates(): void
    {
        $correlation = (new UlidCorrelation())->correlation();

        $first = Brick::create(1, Addition::AUGEND);
        $second = Brick::create(2, Addition::ADDEND);
        $operand = Operand::ADDITION;

        $query = (new CalculationQuery($operand, $first, $second))->withCorrelation($correlation);

        $psrLogger = $this->prophesize(LoggerInterface::class);

        $calculator = new Calculator(
            new EquationFactory(),
            new BrickCalculator(),
            new Factory()
        );

        $handler = new Calculate($calculator, $psrLogger->reveal());

        $calculated = $handler($query);

        $this->assertSame($calculated->correlated(), $correlation);
        $this->assertSame(
            Rhs::ADDITION,
            $calculated->calculation->number->part()
        );

        $this->assertSame(
            $first->toString(),
            $calculated->calculation->equation->first->toString()
        );

        $psrLogger->debug(sprintf(
            Calculate::LOG_MESSAGE_FORMAT,
            $operand->value,
            $first->toString(),
            Addition::AUGEND->toString(),
            $second->toString(),
            Addition::ADDEND->toString(),
            $correlation->toString(),
        ))->shouldHaveBeenCalledOnce();
    }
}
