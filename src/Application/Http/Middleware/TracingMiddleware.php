<?php

namespace App\Application\Http\Middleware;

use App\Application\Http\Exception\BadRequestHttpException;
use App\Application\Http\Provider\TracerProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\RequestProvider\RequestHeaderProvider;

readonly final class TracingMiddleware implements MiddlewareInterface {
    /**
     * @param RequestHeaderProvider $requestHeaders
     * @param TracerProviderInterface $tracerProvider
     */
    public function __construct(
        private RequestHeaderProvider   $requestHeaders,
        private TracerProviderInterface $tracerProvider
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        if (!$this->requestHeaders->has('X-Request-Id')) {
            throw new BadRequestHttpException('X-Request-Id not set');
        }

        $this->tracerProvider->withId($this->requestHeaders->getLine('X-Request-Id'));

        return $handler->handle($request);
    }
}
