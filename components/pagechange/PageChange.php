<?php

declare(strict_types=1);

namespace app\components\pagechange;

use app\models\Change;
use app\models\Page;
use yii\base\BaseObject;

/**
 * Class PageChange
 * @package app\components\pagechange
 */
class PageChange extends BaseObject
{
    /** @var  Page */
    protected $page;
    /**
     * @var
     */
    protected $client;

    /**
     * PageChange constructor.
     * @param Page $page
     * @param array $config
     */
    public function __construct(Page $page, array $config = [])
    {
        $this->page = $page;

        parent::__construct($config);
    }

    /**
     * @return Differ
     */
    public function makeChange(): \app\components\pagechange\Differ
    {
        $change = new HttpClient($this->page->url);
        $content = $change->getContent();

        return new Differ($this->page, $content);
    }

    /**
     * @param Differ $diff
     */
    public function saveChange(\app\components\pagechange\Differ $diff)
    {
        $thisPage = $this->page;
        $response = $diff->getResponse();

        $change = new Change();
        $change->page_id = $thisPage->id;

        if ($diff->isContentDiffExists()) {
            $change->diff = $diff->getDiff();
            $change->save();

            $thisPage->last_content = $diff->getNewContent();
            $thisPage->last_status = '';
            $thisPage->save();
        }

        if ($diff->isExceptionDiffExists()) {
            $change->status = $response->getStatusMessage();
            $change->save();

            $thisPage->last_status = $response->getStatusMessage();
            $thisPage->save();
        }
    }
}
