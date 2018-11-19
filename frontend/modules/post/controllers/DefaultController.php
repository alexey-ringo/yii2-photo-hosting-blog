<?php

namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use frontend\models\Post;
use frontend\modules\post\models\forms\PostForm;

/**
 * Default controller for the `post` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the create view for the module
     * @return string
     */
    public function actionCreate()
    {
        $model = new PostForm(Yii::$app->user->identity);
        if($model->load(Yii::$app->request->post())) {
            //Данные из формы из поля picture
            $model->picture = UploadedFile::getInstance($model, 'picture');
            
            if($model->save()) {
                Yii::$app->session->setFlash('success', 'Пост создан');
                return $this->goHome();
            }
            
        }
        return $this->render('create', [
                'model' => $model,
            ]);
    }
    
    /**
     * Renders for the create view for the module
     * @return string
     */
    public function actionView($id) {
        return $this->render('view', [
            'post' => $this->findPost($id),
            ]);
    }
    
    /**
     * @param integer $id
     * @return User
     * @throws NotFoundHttpException
     */ 
    private function findPost($id) {
        if($user = Post::findOne($id)) {
            return $user;
        }
        throw new NotFoundHttpException();
    }
}
