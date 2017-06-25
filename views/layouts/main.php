<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Page Change',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => '',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav '],
        'encodeLabels' => false,
        'items' => [
            ['label' => FA::icon(FA::_LIST) . ' Webpages', 'url' => Url::toRoute('page/index')],
            ['label' => FA::icon(FA::_TH_LIST) . ' Categories', 'url' => Url::toRoute('category/index')],
            ['label' => FA::icon(FA::_RSS) . ' RSS', 'url' => Url::toRoute('page/rss')],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
