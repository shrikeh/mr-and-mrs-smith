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

use Baldinof\RoadRunnerBundle\BaldinofRoadRunnerBundle;
use League\FlysystemBundle\FlysystemBundle;
use Shrikeh\ApiContext\Http\Bundle as Http;
use Shrikeh\ApiContext\Kernel\DefaultKernel;
use Shrikeh\SymfonyApp\Bundle\App;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;

return [
    FrameworkBundle::class => [DefaultKernel::ENVS_ALL => true],
    BaldinofRoadRunnerBundle::class => [DefaultKernel::ENVS_ALL => true],
    App::class => [DefaultKernel::ENVS_ALL => true],
    Http::class  => [DefaultKernel::ENVS_ALL => true],
    TwigBundle::class => [DefaultKernel::ENVS_ALL => true],
];
