<?php

namespace app\commands;

use app\models\Change;
use app\controllers\PageTrait;
use Yii;
use yii\console\Controller;
use app\models\Page;
use yii\helpers\Console;

class PageController extends Controller
{
    use PageTrait;

    public function actionCheck()
    {
        $pages = Page::find()->all();

        /* @var $page \app\models\Page */
        foreach ($pages as $page) {
            echo "[{$page->url}]" . PHP_EOL;
            $diff = $this->makeChange($page);
            $this->saveChange($page, $diff);

            echo (empty($diff['status']) ? $diff['diff'] : $diff['status']) . PHP_EOL . PHP_EOL;
        }
    }

}
