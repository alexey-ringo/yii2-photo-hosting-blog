<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post`.
 */
class m181116_124244_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->unsigned(),
            'filename' => $this->string()->notNull(),
            'description' => $this->text(),
            'complaints' => $this->integer()->unsigned(),
            'created_at' => $this->integer()->notNull()->unsigned(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%post}}');
    }
}
