<?php
declare(strict_types=1);


namespace app\components\pagechange\responses;

/**
 * Class AbstractResponse
 * @package app\components\pagechange\responses
 */
abstract class AbstractResponse implements ResponseInterface
{
    /**
     * @return bool
     */
    public function isContentExists(): bool
    {
        return $this->getContent() !== null;
    }

    /**
     * @return bool
     */
    public function isStatusMessageExists(): bool
    {
        return $this->getStatusMessage() !== null;
    }
}
