<?php
declare(strict_types=1);


namespace app\components\pagechange;

use app\components\pagechange\responses\ResponseInterface;
use app\models\Page;

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

        $differ = new \SebastianBergmann\Diff\Differ();
        $diffArray = $differ->diffToArray($oldContent, $newContent);

        $addedDiff = array_filter($diffArray, function ($item) {
            return $item[1] === 1;
        });

        $removedDiff = array_filter($diffArray, function ($item) {
            return $item[1] === 2;
        });

        $addedLines = array_map(function ($item) {
            return '+ ' . $item[0];
        }, $addedDiff);

        $removedLines = array_map(function ($item) {
            return '- ' . $item[0];
        }, $removedDiff);

        $diff = [];
        if (!empty($addedLines)) {
            $diff[] = 'Added:' . PHP_EOL . implode(PHP_EOL, $addedLines);
        }
        if (!empty($removedLines)) {
            $diff[] = 'Removed:' . PHP_EOL . implode(PHP_EOL, $removedLines);
        }

        return implode($diff, PHP_EOL . PHP_EOL);
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
