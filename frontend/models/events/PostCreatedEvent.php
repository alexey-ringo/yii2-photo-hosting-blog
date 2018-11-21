<?php
namespace frontend\models\events;

use yii\base\Event;
use frontend\models\User;
use frontend\models\Post;

/**
 * Передает данные вместе с событием EVENT_POST_CREATED
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