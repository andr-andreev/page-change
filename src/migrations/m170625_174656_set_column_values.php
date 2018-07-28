<?php

use app\models\Page;
use yii\db\Migration;

class m170625_174656_set_column_values extends Migration
{
    public function safeUp()
    {
        Page::updateAll(['is_active' => Page::STATUS_ACTIVE]);
    }

    public function safeDown()
    {
        echo "m170625_174656_set_column_values cannot be reverted.\n";

        return false;
    }
}
