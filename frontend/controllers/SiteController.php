<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;

//use frontend\components\AuthHandler;

/**
 * Site controller
 */
class SiteController extends Controller
{
    
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            /*
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            */
        ];
    }
    
    //Метод, который будет срабатывать в случае успешного ответа провайдера OAuth
    /*
    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }
    */

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $users = User::find()->all();
        return $this->render('index', [
            'users' => $users,
            ]);
    }

    


    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

}
