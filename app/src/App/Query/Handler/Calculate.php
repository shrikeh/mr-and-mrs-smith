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

namespace App\Query\Handler;

use App\Query\CalculationQuery;
use App\Result\Calculated;
use MrAndMrsSmith\TechTest\Calculator;
use Psr\Log\LoggerInterface;
use Shrikeh\App\Query\QueryHandler;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class Calculate implements QueryHandler
{
    public const string LOG_MESSAGE_FORMAT = 'Calculating %s for %s:%s, %s:%s, correlation: %s';

    public function __construct(private Calculator $calculator, private LoggerInterface $logger)
    {
    }
    public function __invoke(CalculationQuery $calculationQuery): Calculated
    {
        $this->log($calculationQuery);

        $calculation = $this->calculator->calculate(
            $calculationQuery->first,
            $calculationQuery->second,
            $calculationQuery->operand,
        );

        return (new Calculated($calculation))->withCorrelation($calculationQuery->correlated());
    }

    private function log(CalculationQuery $calculationQuery): void
    {
        $this->logger->debug(sprintf(
            self::LOG_MESSAGE_FORMAT,
            $calculationQuery->operand->value,
            $calculationQuery->first->toString(),
            $calculationQuery->first->part()->toString(),
            $calculationQuery->second->toString(),
            $calculationQuery->second->part()->toString(),
            $calculationQuery->correlated()->toString(),
        ));
    }
}
