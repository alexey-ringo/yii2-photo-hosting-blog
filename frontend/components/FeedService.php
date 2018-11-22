<?php

namespace frontend\components;

use yii\base\Component;
use yii\base\Event;
use frontend\models\Feed;

/**
 * Feed component
 * Сервис, ответственный за формирование новостной ленты,
 * т.е. - наполнение таблицы Feed
 */
 
class FeedService extends Component {
    
    public function addToFeeds(Event $event) {
        //Переданы из frontend\models\events\PostCreateEvent
        //совместно с событиеим создания поста EVENT_POST_CREATED,
        //инициированном после сохранения поста в models\form\PostForm
        $user = $event->getUser(); 
        $post = $event->getPost();
        
        $followers = $user->getFollowers(); //frontend\models\User
        
        //Перебираем всех подписчиков автора поста
        foreach($followers as $follower) {
            //Создаем записи в БД о новом посте,
            //по одной для каждого побписчика
            $feedItem = new Feed();
            
            //id подписчика - получателя новости
            $feedItem->user_id = $follower['id'];
            //Автор поста
            $feedItem->author_id = $user->id;
            $feedItem->author_name = $user->username;
            $feedItem->author_nickname = $user->getNickname();
            $feedItem->author_picture = $user->getPicture();
            //Инф о посте
            $feedItem->post_id = $post->id;
            $feedItem->post_filename = $post->filename;
            $feedItem->post_description = $post->description;
            $feedItem->post_created_at = $post->created_at;
            
            $feedItem->save();
        }
    }
}