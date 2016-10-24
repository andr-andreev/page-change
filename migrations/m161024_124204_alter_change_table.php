<?php

use yii\db\Migration;

class m161024_124204_alter_change_table extends Migration
{
    public function up()
    {
        $this->addColumn('change', 'status', $this->text());
    }

    public function down()
    {
        $this->dropColumn('change', 'status');
    }
}
