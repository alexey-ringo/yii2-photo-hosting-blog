<?php

/* @var $this yii\web\View */
/* @var $currentUser frontend\models\User */
/* @var $popularPosts frontend\models\Post */

use yii\web\JqueryAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = 'Популярные посты';
?>

<div class="page-posts no-padding">
	<div class="row">
		<div class="page page-post col-sm-12 col-xs-12 post-82">


			<div class="blog-posts blog-posts-large">

				<div class="row">


                    <div class="col-sm-12 col-xs-12">
                        <div class="row profile-posts">
    <?php if ($popularPosts): ?>
                            <?php foreach($popularPosts as $popularPost): ?>
                                <div class="col-md-4 profile-post">
                                    <a href="<?php  echo Url::to(['/post/default/view', 'id' => $popularPost->getId()]); ?>">
                                        <img src="<?php echo Yii::$app->storage->getFile($popularPost->filename); ?>" class="author-image" />
                                    </a>
                                </div>
                            <?php endforeach; ?>
    <?php else: ?>
        <div class="col-md-12">
            Постов еще нет!
        </div>
    <?php endif; ?>
                        </div>
                    </div>
                
                
                
                </div>
            </div>
        </div>
    </div>
</div>    

                                   
                                

<?php $this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);