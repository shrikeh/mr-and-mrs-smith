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

namespace Tests\Unit\Api\Http\Action\Calculate;

use Api\Http\Action\Calculate\CalculationQueryFactory;
use Api\Http\Action\Calculate\CalculationQueryFactory\Exception\UnknownOperand;
use DateTimeImmutable;
use DateTimeInterface;
use Ergebnis\Http\Method;
use Infra\Number\Factory;
use MrAndMrsSmith\TechTest\Equation\Operand;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\App\Message\Correlation;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory\UlidCorrelation;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class CalculationQueryFactoryTest extends TestCase
{
    private CorrelationFactory $correlationFactory;

    protected function setUp(): void
    {
        $this->correlationFactory = new UlidCorrelation();
    }
    public function testItCreatesAQuery(): void
    {
        $serverRequest = (new ServerRequest(Method::GET, '/foo'))
            ->withQueryParams([
                'operand' => Operand::MULTIPLICATION->value,
                'second' => '-3.5',
                'first' => '100',
            ]);

        $correlation = $this->correlationFactory->correlation();

        $correlationFactory = new readonly class ($correlation) implements CorrelationFactory {
            public function __construct(private Correlation $correlation)
            {
            }

            public function correlation(DateTimeInterface $dateTime = new DateTimeImmutable()): Correlation
            {
                return $this->correlation;
            }
        };

        $calculationQueryFactory = new CalculationQueryFactory(
            $correlationFactory,
            new Factory()
        );

        $query = $calculationQueryFactory->build($serverRequest);

        $this->assertSame(Operand::MULTIPLICATION, $query->operand);
        $this->assertSame('100', $query->first->toString());
        $this->assertSame('-3.5', $query->second->toString());
        $this->assertSame($correlation, $query->correlated());
    }

    public function testItThrowsAnExceptionIfTheOperandIsNotRecognized(): void
    {
        $invalid = 'foobarbaz';
        $serverRequest = (new ServerRequest(Method::GET, '/foo'))
            ->withQueryParams([
                'operand' => $invalid,
                'second' => '-3.5',
                'first' => '100',
            ]);

        $calculationQueryFactory = new CalculationQueryFactory(
            $this->correlationFactory,
            new Factory()
        );

        $this->expectExceptionObject(new UnknownOperand($invalid, $serverRequest));

        $calculationQueryFactory->build($serverRequest);
    }
}
