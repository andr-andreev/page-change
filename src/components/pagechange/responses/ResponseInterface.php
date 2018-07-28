<?php
declare(strict_types=1);


namespace app\components\pagechange\responses;

/**
 * Interface ResponseInterface
 * @package app\components\pagechange\responses
 */
interface ResponseInterface
{
    /**
     * @return null|string
     */
    public function getContent(): ?string;

    /**
     * @return null|string
     */
    public function getStatusMessage(): ?string;

    /**
     * @return bool
     */
    public function isContentExists(): bool;

    /**
     * @return bool
     */
    public function isStatusMessageExists(): bool;
}
