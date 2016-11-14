<?php

use yii\helpers\Html;
use yii\grid\GridView;

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
            [
                'attribute' => 'category_title',
                'value' => 'category.title'
            ],
            [
                'attribute' => 'description',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a(empty($data->description) ? $data->url : $data->description, $data->url);
                },
            ],
            [
                'attribute' => 'filter_from',
                'label' => 'Filter',
                'format' => 'html',
                'value' => function ($data) {
                    return $data['filter_from'] && $data['filter_from'] ? '<span class="glyphicon glyphicon-ok"></span>' : '';
                },
            ],
            'updated_at:datetime',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
