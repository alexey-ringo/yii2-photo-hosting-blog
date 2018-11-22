<?php

namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
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
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
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
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        
        return $this->render('view', [
            'post' => $this->findPost($id),
            'currentUser' => $currentUser,
            ]);
    }
    
    
    public function actionLike() {
        //Если гость - отправляем на стр входа
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        //Какой пост лайкнули
        $id = Yii::$app->request->post('id');
        $post = $this->findPost($id);
        
        /* @var $currentUser User */
        //Кто лайкнул
        $currentUser = Yii::$app->user->identity;
        
        //Изменение кол-ва лайков по данному посту
        $post->like($currentUser);
        
        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
            ];
    }
    
    public function actionUnlike() {
        //Если гость - отправляем на стр входа
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        //Какой пост лайкнули
        $id = Yii::$app->request->post('id');
        $post = $this->findPost($id);
        
        /* @var $currentUser User */
        //Пользователь, который лайкнул (т.е. - текущий)
        $currentUser = Yii::$app->user->identity;
        
        //Изменение кол-ва лайков по данному посту
        $post->unlike($currentUser);
        
        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
            ];
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
