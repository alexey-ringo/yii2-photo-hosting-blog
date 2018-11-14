<?php

namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;
use yii\web\NotFoundHttpException;

use yii\web\Response;
use yii\web\UploadedFile;
use frontend\modules\user\models\forms\PictureForm;



/**
 * Default controller for the `user` module
 */
class ProfileController extends Controller
{
    
    public function actionView($nickname) {
        /* @var $currentUser User */
        //Эксемпляр класса User, под которым сейчас залогинен текущий пользователь
        $currentUser = Yii::$app->user->identity;
        
        $modelPicture = new PictureForm();
        
        return $this->render('view', [
            'currentUser' => $currentUser,
            'user' => $this->findUser($nickname),
            'modelPicture' => $modelPicture,
            ]);
    }
    
    /**
     * 
     * Handle profile image upload via AJAX request
     */
    public function actionUploadPicture() {
        $model = new PictureForm();
        $model->picture = UploadedFile::getInstance($model, 'picture');
        
        if($model->validate()) {
            echo '<pre>';
            print_r($model->attributes);
            echo '</pre>';
            echo 'OK'; die;
        }
        echo '<pre>';
        print_r($model->getErrors());
        echo '</pre>';
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
