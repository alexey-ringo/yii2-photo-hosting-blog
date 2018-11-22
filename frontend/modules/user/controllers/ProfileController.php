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
        //Установка формата ответа
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = new PictureForm();
        //В атрибут объекта класса PictureForm загружаем объект класса UploadedFile
        $model->picture = UploadedFile::getInstance($model, 'picture');
        /*
        [picture] => yii\web\UploadedFile Object
        (
            [name] => IMG_0522.JPG
            [tempName] => /tmp/phpw2fFhB
            [type] => image/jpeg
            [size] => 611652
            [error] => 0
        )
        */
        
        if ($model->validate()) {   
            
            //Получаем текущего пользователя
            $user = Yii::$app->user->identity;
            //Загружаем файл, сохраняем,
            //и Записываем возвращенный путь к загруженному файлу 
            //в атрибут текущего пользователя (прикрепляем аватарку к данному пользователю)
            $user->picture = Yii::$app->storage->saveUploadedFile($model->picture); // 15/27/30379e706840f951d22de02458a4788eb55f.jpg
            
            //Сохраняем текущего пользователя без валидации - только его вновь наполненный аттрибут picture
            if ($user->save(false, ['picture'])) {
                return [
                    'success' => true, 
                    'pictureUri' => Yii::$app->storage->getFile($user->picture),
                ];
            }
        }
        return ['success' => false, 'errors' => $model->getErrors()];
    }
    
    //Подписка текущего пользователя на выбранного
    public function actionSubscribe($id) {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        /* @var $currentUser User */
        //Эксемпляр класса User, под которым сейчас залогинен текущий пользователь
        $currentUser = Yii::$app->user->identity;
        //Пользователь, на которого хотим подписаться (чью стр профиля просматриваем)
        $user = $this->getUserById($id);
        
        //Подписываем пользователя $currentUser на пользователя $user через метод followUser класса User
        $currentUser->followUser($user);
        
        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickName()]);
    }
    
    //отписка текущего пользователя от выбранного
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
    
    
    //Возвращает экземпляр пользователя, но которого нужно подписаться в actionSubscribe()
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
