<?php

namespace ProjectApi\Middleware;

use Slim\App;
use Throwable;
use Monolog\Logger;
use Slim\Psr7\Request;
use Psr\Log\LoggerInterface;
use ProjectApi\Exception\InvalidInputException;
use InvalidArgumentException;

class CustomErrorHandler
{
    private Logger $logger;

    public function __construct(private App $app)
    {
        $this->logger = $this->app->getContainer()->get('logger');
    }
    public function __invoke(
        Request $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails,
        ?LoggerInterface $logger = null
    ) {
        $payload = $this->getPayload($exception);

        if ($displayErrorDetails) {
            $payload['details'] = $exception->getMessage();
        }

        $response = $this->app->getResponseFactory()->createResponse();
        $response->getBody()->write(
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        );
        return $response->withStatus($payload['status_code']);
    }
    private function getPayload(Throwable $exception): array
    {
        if ($exception instanceof InvalidInputException) {
            $this->logger->debug(json_encode($exception->getDataErrors()));

            return [
                'errors' => $exception->getDataErrors(),
                'code' => 'validation_exception',
                'id' => 'invalid_input_exception',
                'status_code' => 400,
            ];
        }
        if ($exception instanceof InvalidArgumentException) {
            $this->logger->debug(json_encode($exception->getMessage()));
            return [
                'errors' => $exception->getMessage(),
                'id' => 'invalid_input_exception',
                'status_code' => 404,
            ];
        }
        $this->logger->error($exception->getMessage());
        return [
            'error' => 'Oops... Something went wrong, please try again later.',
            'code' => 'internal_error',
            'status_code' => 500,
        ];
    }
}
