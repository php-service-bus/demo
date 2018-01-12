#### An example of using the "[service-bus](https://github.com/mmasiukevich/service-bus)" framework

## Install

``` bash
$ git clone git@github.com:mmasiukevich/service-bus-demo.git
$ cd service-bus
$ composer install -o
$ docker-compose up
```

## Usage
``` bash
$ php tools/registerCustomerCommand.php
```

## Description

This example shows the user registration process, consisting of several steps (@see [flow-example.pdf](https://github.com/mmasiukevich/service-bus/blob/master/flow-example.pdf)):
* Checking the uniqueness of email
* Creating a User aggregate
* After the user is created, the saga is launched, which is responsible for confirming the registration; sending a greeting message after confirming registration

## Brief description of the process
* **Command**: Used to request that an action should be taken
* **Event**: Used to communicate that some action has taken place
* Each message (command/event) is executed atomic This means that for one iteration only 1 message will be executed
* As a message bus, the example uses RabbitMQ

## @todo list
* Change the logic of work with indexes ("event-sourcing" component). The index will be just a hash table with its storage
* Get away from callback hell (change ReactPHP to AmPHP)
* Optimistic/pessimistic locks
* Scheduler
* Reducing the dependence of the user domain on the framework
* Service-bus tests coverage
* Time-based UUID (for messages)
