<?php

use app\models\Page;
use yii\db\Migration;

class m170625_165400_add_column_to_page_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('page', 'is_active', $this->smallInteger()->after('id')->defaultValue(Page::STATUS_ACTIVE));
    }

    public function safeDown()
    {
        $this->dropColumn('page', 'is_active');
    }
}
