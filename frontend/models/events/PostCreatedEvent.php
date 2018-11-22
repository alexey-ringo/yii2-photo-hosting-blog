<?php
namespace frontend\models\events;

use yii\base\Event;
use frontend\models\User;
use frontend\models\Post;

/**
 * Собирает и передает в сервис FeedService все прикрепленные данные о созданном посте
 * вместе с самим событием создания нового поста EVENT_POST_CREATED
 * 
 */ 
class PostCreatedEvent extends Event {
    
    /**
     * @var User
     */
    public $user;
    
    /**
     * @var Post
     */
    public $post;
    
    public function getUser()/*: User */
    {
        return $this->user;
    }

    public function getPost()/*: Post */
    {
        return $this->post;
    }
    
}