<?php

use yii\db\Migration;

class m161110_110428_create_page_index extends Migration
{
    public function up()
    {
        $this->createIndex('idx-pages-category_id', 'page', 'category_id');
    }

    public function down()
    {
        $this->dropIndex('idx-pages-category_id', 'page');
    }
}
