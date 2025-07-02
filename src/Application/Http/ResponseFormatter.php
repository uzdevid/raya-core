<?php

declare(strict_types=1);

namespace App\Application\Http;

use App\Application\Http\Factory\ResponseDataFactory;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\DataResponse\DataResponse;
use Yiisoft\DataResponse\DataResponseFormatterInterface;
use Yiisoft\DataResponse\Formatter\JsonDataResponseFormatter;

final readonly class ResponseFormatter implements DataResponseFormatterInterface {
    /**
     * @param JsonDataResponseFormatter $jsonDataResponseFormatter
     * @param ResponseDataFactory $responseDataFactory
     */
    public function __construct(
        private JsonDataResponseFormatter $jsonDataResponseFormatter,
        private ResponseDataFactory       $responseDataFactory
    ) {
    }

    /**
     * @param DataResponse $dataResponse
     * @return ResponseInterface
     * @throws JsonException
     */
    public function format(DataResponse $dataResponse): ResponseInterface {
        $response = $dataResponse->withData(
            $this->responseDataFactory->createFromResponse($dataResponse)->toArray()
        );

        return $this->jsonDataResponseFormatter->format($response);
    }
}
