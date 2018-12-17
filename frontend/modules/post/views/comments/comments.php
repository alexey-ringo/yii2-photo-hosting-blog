<?php use yii\helpers\Url; ?>
<?php use yii\helpers\Html; ?>

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