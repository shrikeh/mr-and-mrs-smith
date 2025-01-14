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

use Api\Http\Action\Calculate\CalculationResponder;
use App\Result\Calculated;
use Infra\Number\Brick;
use MrAndMrsSmith\TechTest\Calculation;
use MrAndMrsSmith\TechTest\Equation;
use MrAndMrsSmith\TechTest\Equation\Operand;
use MrAndMrsSmith\TechTest\Equation\Part\Lhs\Addition;
use MrAndMrsSmith\TechTest\Equation\Part\Rhs;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Teapot\StatusCode;
use Twig\Environment;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class CalculationResponderTest extends TestCase
{
    use ProphecyTrait;

    public function testItRendersThePage(): void
    {
        $body = 'foo';
        $environment = $this->prophesize(Environment::class);
        $environment->render('calculator.twig', [])->willReturn($body);

        $responder = new CalculationResponder($environment->reveal());

        $response = $responder->respond();

        $this->assertSame(StatusCode::OK, $response->getStatusCode());
        $this->assertSame($body, $response->getBody()->getContents());
    }

    public function testItRendersTheResult(): void
    {
        $body = 'foo';
        $environment = $this->prophesize(Environment::class);
        $calculation = new Calculation(
            new Equation(
                Brick::create(1, Addition::AUGEND),
                Brick::create(2, Addition::ADDEND),
                Operand::ADDITION
            ),
            Brick::create(3, Rhs::ADDITION),
        );
        $result = new Calculated($calculation);

        $context = [
            'calculation' => [
                'first' => [
                    'formal_name' => $calculation->equation->first->part()->toString(),
                    'value' => $calculation->equation->first->toString(),
                ],
                'second' => [
                    'formal_name' => $calculation->equation->second->part()->toString(),
                    'value' => $calculation->equation->second->toString(),
                ],
                'operand' => $calculation->equation->operand->value,
                'result' => [
                    'formal_name' => $calculation->number->part()->toString(),
                    'value' => $calculation->number->toString(),
                ],
            ]
        ];

        $environment->render('calculator.twig', $context)->willReturn(json_encode($context));

        $responder = new CalculationResponder($environment->reveal());
        $response = $responder->respond($result);

        $this->assertSame(json_encode($context), $response->getBody()->getContents());
    }
}
