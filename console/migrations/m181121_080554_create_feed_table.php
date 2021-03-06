<?php

use yii\db\Migration;

/**
 * Handles the creation of table `feed`.
 */
class m181121_080554_create_feed_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%feed}}', [
            'id' => $this->primaryKey()->unsigned(),
            //id пользователя, которому предназначена запись
            'user_id' => $this->integer()->unsigned(),
            'author_id' => $this->integer()->unsigned(),
            'author_name' => $this->string(),
            'author_nickname' => $this->integer(70),
            'author_picture' => $this->string(),
            'post_id' => $this->integer()->unsigned(),
            'post_filename' => $this->string()->notNull(),
            'post_description' => $this->text(),
            'post_created_at' => $this->integer()->notNull()->unsigned(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%feed}}');
    }
}
