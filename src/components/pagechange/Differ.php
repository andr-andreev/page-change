<?php
declare(strict_types=1);


namespace app\components\pagechange;

use app\components\pagechange\responses\ResponseInterface;
use app\models\Page;
use SebastianBergmann\Diff\Output\DiffOnlyOutputBuilder;

/**
 * Class Differ
 * @package app\components\pagechange
 */
class Differ
{
    /**
     * @var Page
     */
    protected $page;
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Differ constructor.
     * @param $page
     * @param $response
     */
    public function __construct(Page $page, ResponseInterface $response)
    {
        $this->page = $page;
        $this->response = $response;
    }

    /**
     * @return null|string
     */
    public function getNewContent(): ?string
    {
        return $this->getFilteredContent();
    }

    protected function getFilteredContent(): ?string
    {
        $content = $this->response->getContent();
        $filterFrom = $this->page->filter_from;
        $filterTo = $this->page->filter_to;
        if (empty($filterFrom) || empty($filterTo)) {
            return $content;
        }

        $quotedFrom = preg_quote($filterFrom, '/');
        $quotedTo = preg_quote($filterTo, '/');
        $re = "/(?s)(?<={$quotedFrom})(.*?)(?={$quotedTo})/ui";

        preg_match($re, $content, $matches);

        return isset($matches[0]) ? $matches[0] : $content;
    }

    /**
     * @return bool
     */
    public function isContentDiffExists(): bool
    {
        return $this->response->isContentExists() && !empty($this->getDiff());
    }

    /**
     * @return string
     */
    public function getDiff(): string
    {
        return static::getDiffContent($this->page->last_content, $this->getFilteredContent());
    }

    /**
     * @param $oldContent
     * @param $newContent
     * @return string
     */
    protected static function getDiffContent($oldContent, $newContent): string
    {
        if ($oldContent === null) {
            $oldContent = '';
        }

        $differ = new \SebastianBergmann\Diff\Differ(new DiffOnlyOutputBuilder(''));

        $diff = $differ->diff($oldContent, $newContent);

        return $diff;
    }

    /*
     * Filter content using block filter
     *
     * @return null|string
     */

    /**
     * @return bool
     */
    public function isExceptionDiffExists(): bool
    {
        $isStatusMessageExists = $this->response->isStatusMessageExists();
        $isEqualStatusMessage = $this->page->last_status === $this->response->getStatusMessage();

        return $isStatusMessageExists && !$isEqualStatusMessage;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
