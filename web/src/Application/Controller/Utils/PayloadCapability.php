<?php
declare(strict_types=1);

namespace HelloFresh\Application\Controller\Utils;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait PayloadCapability
{
    private function validateRequestPayloadScope(array $expected, array $payload)
    {
        $message = 'Invalid scope. Missing ou invalid parameter: "%s".';

        foreach ($expected as $key) {
            if (!array_key_exists($key, $payload) || !isset($payload[$key])) {
                throw new BadRequestHttpException(sprintf($message, $key));
            }
        }
    }
}
