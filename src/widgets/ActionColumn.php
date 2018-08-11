<?php


namespace app\widgets;

use Yii;
use yii\helpers\Html;

class ActionColumn extends \yii\grid\ActionColumn
{
    public $header = 'Actions';
    public $buttonOptions = ['class' => 'btn btn-info btn-sm'];
    public $headerOptions = ['width' => 220, 'class' => 'text-center'];
    public $contentOptions = ['class' => 'text-center'];

    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $title = Yii::t('yii', 'View');
                $options = [
                    'class' => 'btn btn-info btn-sm',
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ];
                return Html::a($title, $url, $options);
            };
        }

        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $title = Yii::t('yii', 'Update');
                $options = [
                    'class' => 'btn btn-success btn-sm',
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ];
                return Html::a($title, $url, $options);
            };
        }

        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $title = Yii::t('yii', 'Delete');
                $options = [
                    'class' => 'btn btn-danger btn-sm',
                    'title' => $title,
                    'aria-label' => $title,
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ];
                return Html::a($title, $url, $options);
            };
        }

        parent::initDefaultButtons();
    }
}