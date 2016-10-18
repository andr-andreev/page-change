<?php

use yii\db\Migration;

/**
 * Handles the creation for table `page`.
 */
class m161015_214518_create_page_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('page', [
            'id' => $this->primaryKey(),
            'description' => $this->string(),
            'url' => $this->string()->notNull(),
            'filter_from' => $this->string(),
            'filter_to' => $this->string(),
            'last_content' => $this->text(),
            'created_at' => $this->integer(11),
            'updated_at' =>$this->integer(11),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('page');
    }
}
