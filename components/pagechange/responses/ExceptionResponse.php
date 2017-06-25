<?php

declare(strict_types=1);

namespace app\components\pagechange\responses;

use GuzzleHttp\Exception\TransferException;

/**
 * Class ExceptionResponse
 * @package app\components\pagechange\responses
 */
class ExceptionResponse extends AbstractResponse
{
    /**
     * @var TransferException
     */
    protected $exception;

    /**
     * ExceptionResponse constructor.
     * @param $exception
     */
    public function __construct(TransferException $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return null|string
     */
    public function getContent(): ?string
    {
        return null;
    }

    /**
     * @return null|string
     */
    public function getStatusMessage(): ?string
    {
        return 'Exception catched:' . PHP_EOL . $this->exception->getMessage();
    }
}
