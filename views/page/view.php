<?php

use luya\bootstrap4\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Page */
/* @var $changes app\models\Change */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-view">

    <h1 class="mb-3">
        <?= Html::encode($this->title) ?>

        <?php $form = ActiveForm::begin([
            'action' => ['page/check', 'id' => $model->id],
            'method' => 'post',
            'options' => ['class' => 'ml-3 d-inline']
        ]); ?>
        <?= Html::submitButton('Check updates', ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>
    </h1>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="page-details-tab" data-toggle="tab" href="#page-details" role="tab"
               aria-controls="page-details" aria-selected="true">
                Details
            </a>
            <a class="nav-item nav-link" id="page-history-tab" data-toggle="tab" href="#page-history" role="tab"
               aria-controls="page-history" aria-selected="false">
                History
            </a>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="page-details" role="tabpanel" aria-labelledby="page-details-tab">
            <div class="my-2">
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
            </div>
        </div>
        <div class="tab-pane fade" id="page-history" role="tabpanel" aria-labelledby="page-history-tab">
            <div class="my-2">
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
                    'summaryOptions' => ['class' => 'mb-2']
                ]); ?>
            </div>
        </div>
    </div>
</div>
