<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment`.
 */
class m181130_133719_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey()->unsigned(),
            'parent_id' => $this->integer()->notNull()->defaultValue(0),
            'user_id' => $this->integer()->notNull()->unsigned(),
            'post_id' => $this->integer()->notNull()->unsigned(),
            'text' => $this->text(),
            'created_at' => $this->integer()->notNull()->unsigned(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%comment}}');
    }
}
