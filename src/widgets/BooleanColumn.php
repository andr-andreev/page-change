<?php

namespace app\widgets;


use yii\grid\DataColumn;
use yii\helpers\Html;

class BooleanColumn extends DataColumn
{
    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $value = (bool)$this->getDataCellValue($model, $key, $index);

        return Html::tag('span', $value ? 'Yes' : 'No', [
            'class' => [
                'badge',
                $value ? 'badge-success' : 'badge-secondary',
            ]
        ]);
    }
}