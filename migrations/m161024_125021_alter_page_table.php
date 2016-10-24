<?php

use yii\db\Migration;

class m161024_125021_alter_page_table extends Migration
{
    public function up()
    {
        $this->addColumn('page', 'last_status', $this->text());
    }

    public function down()
    {
        $this->dropColumn('page', 'last_status');
    }
}
