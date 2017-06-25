<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Page */
/* @var $changes app\models\Change */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'category.title',
            'description',
            'url:url',
            'filter_from',
            'filter_to',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <h2>Page history</h2>

    <?php $form = ActiveForm::begin([
        'action' => ['page/check', 'id' => $model->id],
        'method' => 'post',
        'options' => ['class' => 'form-inline']
    ]); ?>
    <?= Html::submitButton('Check now', ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

    <?= GridView::widget([
        'dataProvider' => $changes,
        'columns' => [
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'headerOptions' => [
                    'width' => 150,
                ],
            ],
            'diff:ntext',
            'status:ntext',
        ],
    ]); ?>

</div>
