<?php

/**
 * PHP Service Bus (CQS implementation) Demo application
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

include_once __DIR__ . '/../vendor/autoload.php';

use Desperado\Infrastructure\Bridge\HttpClient\SyncGuzzleHttpClient;
use GuzzleHttp\Psr7\Response;
use Desperado\Infrastructure\Bridge\HttpClient\HttpRequest;
use Desperado\Domain\Uuid;

$guzzleClient = new SyncGuzzleHttpClient();

$promise = $guzzleClient->post(
    new HttpRequest(
        'http://localhost:13137/register/customer',
        [
            'requestId'   => Uuid::v4(),
            'userName'    => 'someCustomerUserName',
            'displayName' => 'someCustomerDisplayName',
            'email'       => \sprintf('%s@minsk-info.ru', \sha1(\random_bytes(32))),
            'password'    => 'qwerty'
        ]
    )
);
$promise->then(
    function(Response $response)
    {
        echo (string) $response->getBody() . PHP_EOL;
    },
    function(Throwable $throwable)
    {
        echo $throwable->getMessage();
    }
);
