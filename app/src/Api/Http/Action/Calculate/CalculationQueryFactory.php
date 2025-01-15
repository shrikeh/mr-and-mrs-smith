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

namespace Api\Http\Action\Calculate;

use Api\Http\Action\Calculate\CalculationQueryFactory\Exception\UnknownOperand;
use App\Query\CalculationQuery;
use MrAndMrsSmith\TechTest\Calculator\NumberFactory;
use MrAndMrsSmith\TechTest\Equation\Operand;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Addition;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Division;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Multiplication;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Subtraction;
use Psr\Http\Message\ServerRequestInterface;
use Shrikeh\AdrContracts\MessageFactory\Http\HttpQueryFactory;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Query;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory;
use ValueError;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class CalculationQueryFactory implements HttpQueryFactory
{
    public function __construct(
        private CorrelationFactory $correlationFactory,
        private NumberFactory $numberFactory,
    ) {
    }

    public function build(ServerRequestInterface $request): CalculationQuery
    {
        $params = $request->getQueryParams();

        try {
            $operand = Operand::from(strtolower($params['operand'] ?? ''));
        } catch (ValueError $exc) {
            throw new UnknownOperand($params['operand'] ?? '', $request);
        }
        $operandVariableTypes = $this->operandVariables($operand);

        return (new CalculationQuery(
            $operand,
            $this->numberFactory->create($params['first'], $operandVariableTypes[0]),
            $this->numberFactory->create($params['second'], $operandVariableTypes[1]),
        ))->withCorrelation($this->correlationFactory->correlation());
    }


    /**
     * @param Operand $operand
     * @return array<Lhs>
     */
    private function operandVariables(Operand $operand): array
    {
        return match ($operand) {
            Operand::ADDITION   => [Addition::AUGEND, Addition::ADDEND],
            Operand::SUBTRACTION => [Subtraction::MINUEND, Subtraction::SUBTRAHEND],
            Operand::MULTIPLICATION => [Multiplication::MULTIPLIER, Multiplication::MULTIPLICAND],
            Operand::DIVISION => [Division::DIVIDEND, Division::DIVISOR],
        };
    }
}
