<?php

use app\models\Category;
use app\models\Page;
use app\widgets\ActionColumn;
use rmrevin\yii\fontawesome\FA;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Webpages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Follow a new webpage', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'is_active',
                'format' => 'raw',
                'filter' => Page::statusList(),
                'value' => function (Page $data) {
                    return FA::icon($data->is_active ? FA::_CHECK : FA::_TIMES);
                },
                'headerOptions' => [
                    'width' => 100,
                    'class' => 'text-center',
                ],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'header' => 'Category',
                'attribute' => 'category_title',
                'filter' => ArrayHelper::map(Category::find()->asArray()->all(), 'id', 'title'),
                'value' => 'category.title',
                'headerOptions' => [
                    'width' => 180,
                ],
            ],
            [
                'attribute' => 'description',
                'format' => 'raw',
                'value' => function (Page $data) {
                    $linkName = empty($data->description) ? $data->url : $data->description;

                    return Html::a($linkName, $data->url, ['target' => '_blank']);
                },
            ],
            [
                'attribute' => 'filter_from',
                'label' => 'Filter',
                'format' => 'raw',
                'value' => function (Page $data) {
                    return FA::icon($data->filter_from && $data->filter_from ? FA::_CHECK : FA::_TIMES);
                },
                'headerOptions' => [
                    'width' => 80,
                    'class' => 'text-center',
                ],
                'contentOptions' => ['class' => 'text-center'],
            ],
            'updated_at:datetime',
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>
</div>
