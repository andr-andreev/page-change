<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m161107_153059_create_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
        ]);

        $this->insert('category', ['title' => 'Default category']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('category');
    }
}
