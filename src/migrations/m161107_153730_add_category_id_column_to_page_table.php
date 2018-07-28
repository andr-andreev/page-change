<?php

use yii\db\Migration;

/**
 * Handles adding category_id to table `page`.
 */
class m161107_153730_add_category_id_column_to_page_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('page', 'category_id', $this->integer()->defaultValue(1));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('page', 'category_id');
    }
}
