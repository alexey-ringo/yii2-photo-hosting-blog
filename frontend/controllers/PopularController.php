<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;
use frontend\models\Post;

class PopularController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $popularPosts = new Post();
        
        return $this->render('index', [
            'popularPosts' => $popularPosts->getPosts(),
            ]);
    }

}
