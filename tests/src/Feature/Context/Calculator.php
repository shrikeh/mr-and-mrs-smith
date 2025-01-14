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

namespace Tests\Feature\Context;

use Behat\Behat\Context\Context;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Step\Given;
use Behat\Step\When;
use Behat\Step\Then;

use MrAndMrsSmith\TechTest\Calculator as SmithCalculator;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class Calculator implements Context
{

    private SMithCalculator $calculator;
    public function __construct()
    {
        $this->calculator = new SmithCalculator();
    }

    #[Given('that the augend is :augend')]
    public function thatTheAugendIs($augend): void
    {
        throw new PendingException();
    }

    #[Given('the addend is :addend')]
    public function theAddendIs($addend): void
    {
        throw new PendingException();
    }

    #[When('the two values are added')]
    public function theTwoValuesAreAdded(): void
    {
        throw new PendingException();
    }

    #[Then('the sum is :sum')]
    public function theSumIs($sum): void
    {
        throw new PendingException();
    }
}
