<?php

use yii\db\Migration;

/**
 * Class m181203_123330_add_indexes_for_tables
 */
class m181203_123330_add_indexes_for_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-post-user_id-user-id', '{{%post}}', 'user_id', '{{%user}}', 'id', 
            'CASCADE', 'CASCADE'
            );
            
        $this->addForeignKey(
            'fk-comment-user_id-user-id', '{{%comment}}', 'user_id', '{{%user}}', 'id', 
            'CASCADE', 'CASCADE'
            );
            
        $this->addForeignKey(
            'fk-comment-post_id-post-id', '{{%comment}}', 'post_id', '{{%post}}', 'id', 
            'CASCADE', 'CASCADE'
            );
            
        $this->addForeignKey(
            'fk-feed-author_id-user-id', '{{%feed}}', 'author_id', '{{%user}}', 'id', 
            'CASCADE', 'CASCADE'
            );
            
        $this->addForeignKey(
            'fk-feed-post_id-post-id', '{{%feed}}', 'post_id', '{{%post}}', 'id', 
            'CASCADE', 'CASCADE'
            );
            
         $this->addForeignKey(
             'fk-auth-user_id-user-id', '{{%auth}}', 'user_id', '{{%user}}', 'id', 
             'CASCADE', 'CASCADE'
             );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-post-user_id-user-id', 
            '{{%post}}'
        );
        
        $this->dropForeignKey(
            'fk-comment-user_id-user-id', 
            '{{%comment}}'
        );
        
        $this->dropForeignKey(
            'fk-comment-post_id-post-id', 
            '{{%comment}}'
        );
        
        $this->dropForeignKey(
            'fk-feed-author_id-user-id', 
            '{{%feed}}'
        );
        
        $this->dropForeignKey(
            'fk-feed-post_id-post-id', 
            '{{%feed}}'
        );
        
        $this->dropForeignKey(
            'fk-auth-user_id-user-id', 
            '{{%auth}}'
        );
        
        
    }

}