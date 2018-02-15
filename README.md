#### An example of using the "[service-bus](https://github.com/mmasiukevich/service-bus)" framework

## Install

``` bash
$ git clone git@github.com:mmasiukevich/service-bus-demo.git
$ cd service-bus-demo
$ composer install -o
$ docker-compose up
```

## Usage
``` bash
$ php tools/registerCustomerCommand.php
```

## Description

This example shows the user registration process, it's consisting of several steps (@see [flow-example.pdf](https://github.com/mmasiukevich/service-bus-demo/blob/master/flow-example.pdf)):
* Checking the uniqueness of e-mail
* Creating a User aggregate
* After the user is created, saga is launched, which is responsible for confirming the registration and sending a greeting message after confirming registration

## Brief description of the process
* **Command**: it's used for an request that action should be taken
* **Event**: it's used to communicate that some action has taken place
* Every message (command/event) is executed atomic. This one means - that for one iteration it will be only 1 message executed.
* As a message bus, the example uses RabbitMQ

## @todo list
* Get away from callback hell (change ReactPHP to AmPHP)
* Optimistic/pessimistic locks
* Reducing the dependence of the user domain on the framework
* Service-bus tests coverage
* Time-based UUID (for messages)
