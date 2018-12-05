<?php

use yii\db\Migration;

/**
 * Handles adding likes_and_hashtag to table `post`.
 */
class m181205_213254_add_likes_and_hashtag_columns_to_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%post}}', 'likes', $this->integer()->notNull()->defaultValue(0)->unsigned());
        $this->addColumn('{{%post}}', 'hashtag', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%post}}', 'hashtag');
        $this->dropColumn('{{%post}}', 'likes');
    }
}
