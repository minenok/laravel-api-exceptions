<?php

declare(strict_types=1);

namespace Lanin\Laravel\ApiExceptions;

use Lanin\Laravel\ApiExceptions\Contracts\DontReport;

class BadRequestApiException extends ApiException implements DontReport
{
    public function __construct(string $message = '', ?\Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'The server cannot process the request due to its malformed syntax.';
        }

        parent::__construct(400, 'bad_request', $message, $previous);
    }
}
