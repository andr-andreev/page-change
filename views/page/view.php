<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Page */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'description',
            'url:url',
            'filter_from',
            'filter_to',
            'last_content:ntext',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $changes,
        'columns' => [
            'diff:ntext',
            'updated_at:datetime',
        ],
    ]); ?>

</div>
