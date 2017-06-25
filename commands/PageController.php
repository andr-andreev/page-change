<?php

namespace app\commands;

use app\components\pagechange\PageChange;
use app\models\Page;
use yii\console\Controller;
use yii\di\Container;
use yii\helpers\Console;

class PageController extends Controller
{
    public function actionCheck()
    {
        $container = new Container;
        $pages = Page::find()->all();

        /* @var $page \app\models\Page */
        foreach ($pages as $page) {
            $this->stdout("[{$page->url}]" . PHP_EOL);

            /** @var PageChange $pageChange */
            $pageChange = $container->get(PageChange::className(), [$page]);

            $diff = $pageChange->makeChange();

            if ($diff->isContentDiffExists()) {
                $this->stdout($diff->getDiff() . PHP_EOL, Console::FG_GREEN);
                $pageChange->saveChange($diff);
            } elseif ($diff->isExceptionDiffExists()) {
                $this->stdout($diff->getResponse()->getStatusMessage() . PHP_EOL, Console::FG_RED);
                $pageChange->saveChange($diff);
            } else {
                $this->stdout('No changes' . PHP_EOL, Console::FG_GREY);
            }
        }
    }

}
