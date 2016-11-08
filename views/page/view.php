<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Page */
/* @var $changes app\models\Change */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'action' => ['page/check', 'id' => $model->id],
        'method' => 'post',
        'options' => ['class' => 'form-inline']
    ]); ?>
    <?= Html::submitButton('Check now', ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

    <p>
        <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'category.title',
            'description',
            'url:url',
            'filter_from',
            'filter_to',
            'last_content:ntext',
            'last_status:ntext',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $changes,
        'columns' => [
            'diff:ntext',
            'status:ntext',
            'updated_at:datetime',
        ],
    ]); ?>

</div>
