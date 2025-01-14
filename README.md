# Mr & Mrs Smith Tech Test

## Overview

Tech tests are always a mix between getting it done quickly and getting it done _right_. Full disclosure: this is certainly more than 90 minutes of effort. However, my thinkins is that there will be many candidates that know Symfony, but I need to use this opportunity to demonstrate I know more than just the Symfony docs.

(If you want to know if I'm familiar with Symfony, please see https://github.com/symfony/symfony/blob/7.3/CONTRIBUTORS.md?plain=1#L1247C15-L1247C16).

Instead, I wanted to show I understand more than just Symfony and TDD (though I wish there had been more time to go back and make the test coverage a bit higher - the ones that aren't covered at all are trivial such as Exception messages and Enums).

So, the things I focussed on demonstrating:

* Research, learning the names of things in formal mathematics (ie product, quotient, etc). These have been created as Enums, to help with ubiquitous language.
* The app is designed around an opinionated layout of DDD (see _Architecture_ below).
* It was mentioned during the interview process that you use queues, so I went with a Query Bus to demonstrate a knowledge of CQRS.
* The app is built around Action-Domain-Responder (ADR) pattern. As such, there isn't a _controller_ but a single invokable that corresponds to a public action (calculate).
* You mentioned in the tech test that it could be in any framework, therefore I have tried to make this as agnostic to framework as possible (ie using abstraction and PSR-7 HTTP message). With a little work, this could run as a Laravel app.

## Style and Architecture

The test is written as a Query Bus-led application in PHP 8.3 and Symfony. To avoid local development host requirements, it runs in a Docker container, which will be built on first run. Tests similarly will run inside the container. The app combines several concepts, including Domain-Driven Design (DDD) and Action Domain Responder (ADR)

### Architecture

The application runs from the `/app` directory, with `/tests` and `/vendor` separate. This allows the docker container to have a production version without tests. Inside `/app`, the `src` directory contains four folders:

* `Api` for the actual application interface (in this case, HTTP).
* `App` for the application itself, which is based on *Commands* and *Queries*. This design pattern immediately allows splitting the application up with Command Query Responsibility Segregation (CQRS).
* `Infra`, which is where implementation specifics of business logic is written (in this example, the calculator and number implementation using the `brick/math` package.
* `MrAndMrsSmith`, which contains a slightly contrived effort at business domain logic.

Corresponding unit tests are in `tests/src/Unit`. Were there time for Behavioural Driven Development (BDD), they would live in `tests/src/Feature`.

### Where to start

There is only one _Action_, `Calculate`, in `app/src/Api/Http/Action/Calculate.php`. This is responsible (via delegation) to converting the `ServerRequest` into message for the Query Bus, and then responding with a `Response`. 

## Requirements

The app expects docker. You must provide a GITHUB_OAUTH token to build the app. (This is necessary to pull down the packages). The container uses Roadrunner to serve HTTP content.
Any questions, please let me know.

To serve the content on port 9901, run:

```bash
export GITHUB_OAUTH=<token>
make up
```

### Testing

To run the tests, run the following command from the host machine:

```bash
make test
```

This will start the container (building as necessary) and then run `composer test` within the container.
Infection is installed, but I haven't had a chance to implement the mutations it's spotted.


## @Todo

* Work on infection feedback and cover trivial code coverage
* Behat testing (ran out of time)
