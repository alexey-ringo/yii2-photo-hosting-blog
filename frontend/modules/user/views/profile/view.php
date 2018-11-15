<?php
    /* @var $this yii\web\View */
    /* @var $user frontend\models\User */
    /* @var $currentUser frontend\models\User */
    /* @var $modelPicture frontend\modules\user\models\forms\PictureForm */
    
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\helpers\HtmlPurifier;
    
    use dosamigos\fileupload\FileUpload;
?>

<h3><?php echo Html::encode($user->username); ?></h3>
<p><?php echo HtmlPurifier::process($user->about); ?></p>

 <img src="<?php echo $user->getPicture(); ?>" />

<?php if ($currentUser && $user->equals($currentUser)): ?>

  <hr>
 
  
  <?= FileUpload::widget([
    //наша модель frontend\modules\user\models\forms\PictureForm
    'model' => $modelPicture,
    //$picture - атрибут в ней
    'attribute' => 'picture',
    //путь к контроллеру и AJAX-action
    'url' => ['/user/profile/upload-picture'], // your url, this is just for demo purposes,
    'options' => ['accept' => 'image/*'],
    'clientOptions' => [
        'maxFileSize' => 2000000
    ],
    // Also, you can specify jQuery-File-Upload events
    // see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
        'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
    ],
  ]); ?>
<?php endif; ?>  

<?php if ($currentUser && !$user->equals($currentUser)): ?>
  <?php if (!$currentUser->isFollowing($user)): ?>
    <a href="<?php echo Url::to(['/user/profile/subscribe', 'id' => $user->getId()]) ?>" class="btn btn-info">Подписаться</a>
  <?php else: ?>
    <a href="<?php echo Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]) ?>" class="btn btn-info">Отписаться</a>
  <?php endif; ?>

  <hr>
  
    <h5>Мои друзья, кто также подписан на <?php echo Html::encode($user->username); ?></h5>
    <div class="row">
      <?php foreach($currentUser->getMutualSubscriptionsTo($user) as $item): ?>
        <div class="col-md-12">
          <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($item['nickname']) ? $item['nickname'] : $item['$id']]); ?>">
        <?php echo Html::encode($item['username']); ?>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

  <hr>
  
  
<?php endif; ?>


<!-- Button modal subscriptions -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#subscribesModal">
  Подписки: <?php echo $user->countSubscriptions(); ?>
</button>

<!-- Button modal followers -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#followersModal">
  Подписчики : <?php  echo $user->countFollowers() ?>
</button>



<!-- Subscribes Modal -->
<div class="modal fade" id="subscribesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Подписки</h4>
      </div>
      <div class="modal-body">
        <?php foreach ($user->getSubscriptions() as $subscription) : ?>
          <div class="col-md-12">
            <?php /*Ищем подписку на пользователя по его nickname, если отсутствует - то по id */ ?>
            <a href="<?php  echo Url::to(['/user/profile/view', 'nickname' => $subscription['nickname'] ? $subscription['nickname'] : $subscription['id']]); ?>">
              <?php echo Html::encode($subscription['username']); ?>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <button type="button" class="btn btn-primary">Сохранить</button>
      </div>
    </div>
  </div>
</div>

<!-- Followers Modal -->
<div class="modal fade" id="followersModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Подписчики</h4>
      </div>
      <div class="modal-body">
        <?php foreach ($user->getFollowers() as $follower) : ?>
          <div class="col-md-12">
            <?php /*Ищем подписчика по его nickname, если отсутствует - то по id */ ?>
            <a href="<?php  echo Url::to(['/user/profile/view', 'nickname' => $follower['nickname'] ? $follower['nickname'] : $follower['id']]); ?>">
              <?php echo Html::encode($follower['username']); ?>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <button type="button" class="btn btn-primary">Сохранить</button>
      </div>
    </div>
  </div>
</div>