<?php

namespace frontend\modules\user\controllers;

use yii\web\Controller;
use frontend\models\User;
use yii\web\NotFoundHttpException;


/**
 * Default controller for the `user` module
 */
class ProfileController extends Controller
{
    
    public function actionView($nickname) {
        
        return $this->render('view', [
            'user' => $this->findUser($nickname),
            ]);
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
