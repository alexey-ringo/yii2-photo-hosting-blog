<?php

/* @var $this yii\web\view */
/* @var $post frontend\models\Post */
/* @var $currentUser frontend\models\User */
/* @var $comments frontend\models\Comment */
/* @var $modelComment frontend\modules\post\models\forms\CommentForm */

use yii\helpers\Url;
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\web\JqueryAsset;

?>

<div class="page-posts no-padding">
    <div class="row">
        <div class="page page-post col-sm-12 col-xs-12 post-82">


            <div class="blog-posts blog-posts-large">

                <div class="row">
                    <article class="post col-sm-12 col-xs-12">
                            <div class="post-meta">
                                <div class="post-title">
                                    <img src="<?php echo $post->user->getPicture(); ?>" class="author-image" />
                                    <div class="author-name">
                                        <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($post->user->nickname) ? $post->user->nickname : $post->user->id]); ?>">
                                            <?php if ($post->user): ?>
                                            <?php /*echo $post->user->username; */?>
                                            <?php echo Html::encode($post->user->username); ?>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="post-type-image">
                                <a href="#">
                                    <img src="<?php echo $post->getImage(); ?>" alt="">
                                </a>
                            </div>
                            <div class="post-description">
                                <p><?php echo Html::encode($post->description); ?></p>
                            </div>
                            <div class="post-bottom">
                                <div class="post-likes">
                                    <a href="#" class="btn btn-secondary"><i class="fa fa-lg fa-heart-o"></i></a>
                                    <span class="likes-count"><?php echo $post->countLikes(); ?></span>
                                    <?php if($currentUser): ?>
                                        <a href="#" class="btn btn-primary button-like 
                                        <?php
                                            /* Если пользователь существует, и он уже лайкнул пост - прячем кнопку Лайк */
                                            echo ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : ""; ?>" 
                                            data-id="<?php echo $post->id; ?>">
                                            Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                                        </a>
        
                                        <a href="#" class="btn btn-primary button-unlike <?php 
                                            echo ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none"; ?>" 
                                            data-id="<?php echo $post->id; ?>">
                                            Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="post-comments">
                                    <a href="#">5 comments</a>

                                </div>
                                <div class="post-date">
                                    <span>Jan 14, 2016</span>    
                                </div>
                                <div class="post-report">
                                    <a href="#">Report post</a>    
                                </div>
                            </div>
                    </article>
                    
                    
                    
                    
                    
                    <div class="col-sm-12 col-xs-12">
                        <h4>5 comments</h4>
                        <div class="comments-post">

                            <div class="single-item-title"></div>
                            <div class="row">
                                <ul class="comment-list">

                                    <?php foreach($comments as $comment): ?>
                                    <!-- comment item -->
                                    <li class="comment">
                                        <div class="comment-user-image">
                                            <img src="<?php echo $comment->user->getPicture() ?>" class="author-image" />
                                        </div>
                                        <div class="comment-info">
                                            <h4 class="author">
                                                <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($comment->user->nickname) ? $comment->user->nickname : $comment->user->id]); ?>">
                                                    <?php if ($comment->user_id): ?>
                                                        <?php echo Html::encode($comment->user->username); ?>
                                                    <?php endif; ?>
                                                </a> 
                                                <span>(<?php echo Yii::$app->formatter->asDatetime($comment->created_at); ?>)</span>
                                            </h4>
                                                <p><?php echo $comment->text; ?></p>
                                        </div>
                                    </li>
                                    <!-- comment item -->
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <!-- comments-post -->
                    </div>
                    
                    
                    
                    
                    
                    <?php if($currentUser): ?>
                    <div class="col-sm-12 col-xs-12">
                        <div class="comment-respond">
                            <h4>Ваш комментарий:</h4>
                            <form action="#" method="post">
                                <p class="comment-form-comment">
                                    <textarea name="comment main-comment" id="main-comment" data-id="<?= $post->id ?>" rows="6" class="form-control" placeholder="Text" aria-required="true"></textarea>
                                </p>
                                <p class="form-submit">
                                    <button type="submit" id="main-comment-btn" class="btn btn-secondary">Отправить</button> 
                                </p>				
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
    ]);
    
$this->registerJsFile('@web/js/comments.js', [
    'depends' => JqueryAsset::className(),
    ]);
?>