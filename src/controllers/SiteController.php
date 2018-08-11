<?php

namespace app\controllers;

use app\controllers\PageChange;
use app\models\Change;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use Zend\Feed\Writer\Entry;
use Zend\Feed\Writer\Feed;


class SiteController extends Controller
{
    /**
     * Generates RSS feed
     * @return mixed
     */
    public function actionRss()
    {
        $changes = Change::find()
            ->innerJoinWith(['page', 'category'])
            ->orderBy(['id' => SORT_DESC])
            ->limit(Yii::$app->params['rssItemsCount'])
            ->all();

        $feed = new Feed();
        $this->initFeed($feed);

        foreach ($changes as $change) {
            $entry = $this->initFeedEntry($feed->createEntry(), $change);
            $feed->addEntry($entry);
        }

        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_RAW;
        $response->getHeaders()->set('Content-Type', 'application/rss+xml; charset=utf-8');

        return $feed->export('rss');
    }

    /**
     * @param Feed $feed
     */
    private function initFeed(Feed $feed)
    {
        $feed->setTitle(Yii::$app->params['name']);
        $feed->setDescription(Yii::$app->params['name']);
        $feed->setLink(Url::toRoute('/', true));
        $feed->setFeedLink(Url::to(['site/rss']), 'rss');
        $feed->setDateModified(time());
    }

    /**
     * @param Entry $entry
     * @param Change $model
     * @return Entry
     */
    private function initFeedEntry(Entry $entry, Change $model)
    {
        $entry->setId(Url::toRoute(['change/view', 'id' => $model->id], true));
        $entry->setTitle($model->getExtendedTitle());
        $entry->setLink($model->page->url);
        $entry->setDateCreated(\DateTime::createFromFormat('U', $model->created_at));
        $entry->setContent(Yii::$app->formatter->asNtext($model->diff ?: $model->status));

        return $entry;
    }
}
