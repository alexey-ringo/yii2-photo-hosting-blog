<?php

namespace frontend\components;

use yii\base\Component;
use yii\base\Event;

/**
 * Feed component
 * 
 */
 
class FeedService extends Component {
    
    public function addToFeeds(Event $event) {
        echo '<pre>';
        print_r($event);
        echo '<pre>';
        die('add post to feeds');
    }
}