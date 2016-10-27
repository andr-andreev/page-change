<?php


namespace app\controllers;

use app\models\Change;
use app\models\Page;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7;
use Html2Text\Html2Text;
use Symfony\Component\DomCrawler\Crawler;
use SebastianBergmann\Diff\Differ;

trait PageTrait
{
    public function makeChange(Page $page)
    {
        // Fetch new content from web server
        $content = $this->getContent($page->url);
        if (!empty($content['status'])) {
            return ['status' => $content['status']];
        }

        // Filter content using block filter
        $newContent = $this->filterContent($content['content'], $page->filter_from, $page->filter_to);

        // Find any changes
        $oldContent = $page->last_content;
        $diff = $this->diffContent($oldContent, $newContent);

        return ['content' => $newContent, 'diff' => $diff];
    }

    public function saveChange(Page $page, $diff)
    {
        $change = new Change();
        $change->page_id = $page->id;
        if (!empty($diff['diff'])) {
            $change->diff = $diff['diff'];
            $change->save();

            $page->last_content = $diff['content'];
            $page->last_status = '';
            $page->save();
        } elseif (!empty($diff['status']) && $page->last_status !== $diff['status']) {
            $change->status = $diff['status'];
            $change->save();

            $page->last_status = $diff['status'];
            $page->save();
        }
    }

    private function diffContent($oldContent, $newContent)
    {
        if (is_null($oldContent)) {
            $oldContent = '';
        }

        $differ = new Differ;
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

    private function getContent($url)
    {
        $client = new Client([
            'timeout' => \Yii::$app->params['timeout']
        ]);

        try {
            $response = $client->request('GET', $url, [
                'headers' => ['User-Agent' => 'Mozilla/5.0']
            ]);

            $content = (string)$response->getBody();
        } catch (TransferException $e) {
            $status = 'Exception catched:' . PHP_EOL . $e->getMessage();
//            if ($e->hasResponse()) {
//                $status .= PHP_EOL . Psr7\str($e->getResponse());
//            }
            return ['status' => $status];
        }

        // using DomCrawler component to convert encoding to utf-8
        $crawler = new Crawler($content);

        // using Html2Text to get clean text
        $formatter = new Html2Text($crawler->html(), [
            'width' => \Yii::$app->params['textWidth']
        ]);

        return ['content' => trim($formatter->getText())];
    }

    private function filterContent($content, $filterFrom, $filterTo)
    {
        if (empty($filterFrom) || empty($filterTo)) {
            return $content;
        }

        $quotedFrom = preg_quote($filterFrom, '/');
        $quotedTo = preg_quote($filterTo, '/');
        $re = "/(?s)(?<={$quotedFrom})(.*?)(?={$quotedTo})/ui";

        preg_match($re, $content, $matches);

        return isset($matches[0]) ? $matches[0] : $content;
    }
}