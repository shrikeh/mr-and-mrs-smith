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

use App\Result\Calculated;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Shrikeh\AdrContracts\Responder\HttpResponder;
use Shrikeh\App\Message\Result;
use Teapot\StatusCode;
use Twig\Environment;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class CalculationResponder implements HttpResponder
{
    public function __construct(private Environment $twig)
    {
    }

    public function respond(?Result $result = null): ResponseInterface
    {

        return new Response(
            status: StatusCode::OK,
            body: $this->twig->render(
                'calculator.twig',
                $this->contextFromResult($result),
            ),
        );
    }

    private function contextFromResult(?Result $result = null): array
    {
        if (!$result instanceof Calculated) {
            return [];
        }
        $calculation = $result->calculation;
        return [
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
    }

    public function supports(Result $result): bool
    {
        return $result instanceof Calculated;
    }
}
