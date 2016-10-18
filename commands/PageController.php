<?php

namespace app\commands;

use app\models\Change;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7;
use Html2Text\Html2Text;
use Yii;
use yii\console\Controller;
use app\models\Page;
use SebastianBergmann\Diff\Differ;
use yii\helpers\Console;

class PageController extends Controller
{
    public function actionCheck()
    {
        $pages = Page::find()->all();

        /* @var $page \app\models\Page */
        foreach ($pages as $page) {
            echo $page->url . PHP_EOL;

            $oldContent = $page->last_content;

            // Fetch new content from web server
            $content = $this->getContent($page->url);

            // Filter content using block filter
            $newContent = $this->filterContent($content, $page->filter_from, $page->filter_to);

            // Find any changes
            $diff = $this->diffContent($oldContent, $newContent);
            echo $diff . PHP_EOL;

            if (!empty($diff)) {
                $change = new Change();
                $change->page_id = $page->id;
                $change->diff = $diff;
                $change->save();

                $page->last_content = $newContent;
                $page->save();
            }
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
        $client = new Client(['timeout' => 5.0]);

        try {
            $response = $client->request('GET', $url, [
                'headers' => ['User-Agent' => 'Mozilla/5.0']
            ]);

            $content = (string)$response->getBody();
        } catch (TransferException $e) {
            $content = 'Exception catched:' . PHP_EOL . $e->getMessage();
            if ($e->hasResponse()) {
                $content .= PHP_EOL . Psr7\str($e->getResponse());
            }
        }

        $formatter = new Html2Text($content, ['width' => 0]);

        return $formatter->getText();
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
