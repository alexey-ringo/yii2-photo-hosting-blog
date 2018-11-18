<?php

namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
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
}
