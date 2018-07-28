<?php

use app\models\Category;
use app\models\Page;
use app\widgets\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Webpages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1 class="mb-3">
        <?= Html::encode($this->title) ?>

        <?= Html::a('Follow a new webpage', ['create'], ['class' => 'btn btn-success']) ?>
    </h1>

    <?= \luya\bootstrap4\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'is_active',
                'format' => 'raw',
                'filter' => Page::statusList(),
                'class' => \app\widgets\BooleanColumn::class,
                'headerOptions' => [
                    'width' => 100,
                ],
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
                'class' => \app\widgets\BooleanColumn::class,
                'value' => function (Page $data) {
                    return $data->filter_from && $data->filter_to;
                },
                'headerOptions' => [
                    'width' => 80,
                ],
            ],
            'updated_at:datetime',
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete}',
            ],
        ],
        'summaryOptions' => ['class' => 'mb-2']
    ]); ?>
</div>
