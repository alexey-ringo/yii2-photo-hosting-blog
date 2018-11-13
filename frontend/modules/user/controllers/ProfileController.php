<?php

namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;
use yii\web\NotFoundHttpException;


/**
 * Default controller for the `user` module
 */
class ProfileController extends Controller
{
    
    public function actionView($nickname) {
        /* @var $currentUser User */
        //Эксемпляр класса User, под которым сейчас залогинен текущий пользователь
        $currentUser = Yii::$app->user->identity;
        
        return $this->render('view', [
            'currentUser' => $currentUser,
            'user' => $this->findUser($nickname),
            ]);
    }
    
    public function actionSubscribe($id) {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        /* @var $currentUser User */
        //Эксемпляр класса User, под которым сейчас залогинен текущий пользователь
        $currentUser = Yii::$app->user->identity;
        //Пользователь, чью стр профиля просматриваем
        $user = $this->getUserById($id);
        
        //Пользователя $currentUser нужно подписать на пользователя $user через метод followUser класса User
        $currentUser->followUser($user);
        
        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickName()]);
    }
    
    public function actionUnsubscribe($id) {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        /* @var $currentUser User */
        //Эксемпляр класса User, под которым сейчас залогинен текущий пользователь
        $currentUser = Yii::$app->user->identity;
        //Пользователь, чью стр профиля просматриваем
        $user = $this->getUserById($id);
        
        //Пользователя $currentUser нужно подписать на пользователя $user через метод followUser класса User
        $currentUser->unfollowUser($user);
        
        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickName()]);
    }
    
    /**
     * @param integer $id
     * @return User
     * @throws NotFoundHttpException
     */ 
    public function getUserById($id) {
        
        if ($user = User::findOne($id)) {
            return $user;
        }
        
        throw new NotFoundHttpException();
    }
    
    /**
     * @param string $nickname
     * @return User
     * @throws NotFoundHttpException
     */ 
    public function findUser($nickname) {
        //Если пользователь не найден по nickname то ищем по id
        if ($user = User::find()->where(['nickname' => $nickname])->orWhere(['id' => $nickname])->one()) {
            return $user;
        }
        
        throw new NotFoundHttpException();
    }
    
}
