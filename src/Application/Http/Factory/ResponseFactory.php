<?php

namespace App\Application\Http\Factory;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Yiisoft\DataResponse\DataResponse;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Http\Status;

final readonly class ResponseFactory implements DataResponseFactoryInterface {
    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param StreamFactoryInterface $streamFactory
     */
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface   $streamFactory,
    ) {
    }

    /**
     * @param $data
     * @param int $code
     * @param string $reasonPhrase
     * @return DataResponse
     */
    public function createResponse($data = null, int $code = Status::OK, string $reasonPhrase = ''): DataResponse {
        return new DataResponse($data, $code, $reasonPhrase, $this->responseFactory, $this->streamFactory);
    }
}
