<?php

use yii\db\Migration;

/**
 * Handles the creation for table `change`.
 * Has foreign keys to the tables:
 *
 * - `pages`
 */
class m161015_220159_create_change_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('change', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->notNull(),
            'diff' => $this->text(),
            'created_at' => $this->integer(11),
            'updated_at' =>$this->integer(11),
        ]);

        // creates index for column `page_id`
        $this->createIndex(
            'idx-change-page_id',
            'change',
            'page_id'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops index for column `page_id`
        $this->dropIndex(
            'idx-change-page_id',
            'change'
        );

        $this->dropTable('change');
    }
}
