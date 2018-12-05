<?php
    /* @var $this yii\web\View */
    /* @var $user frontend\models\User */
    /* @var $currentUser frontend\models\User */
    /* @var $modelPicture frontend\modules\user\models\forms\PictureForm */
    
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
    
use dosamigos\fileupload\FileUpload;
    
$this->title = Html::encode($user->username);
?>


<div class="page-posts no-padding">
	<div class="row">
		<div class="page page-post col-sm-12 col-xs-12 post-82">


			<div class="blog-posts blog-posts-large">

				<div class="row">

					<!-- profile -->
					<article class="profile col-sm-12 col-xs-12">                                            
						<div class="profile-title">
							<img src="<?php echo $user->getPicture(); ?>" id="profile-picture" class="author-image" />
              
							<div class="author-name"><?php echo Html::encode($user->username); ?></div>
              
								<?php if (/*Первоначально проверим $currentUser на наличие*/$currentUser && $user->equals($currentUser)): ?>

  

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
											//Если ответ от ProfileController@actionUploadPicture - success
											if (data.result.success) {
											//Показываем сообщение об успешной загрузке
												$("#profile-image-success").show();
												//Прячем сообщение об неуспешной загрузке
												$("#profile-image-fail").hide();
												//Динамически устанавливаем src из ответа JSON
												$("#profile-picture").attr("src", data.result.pictureUri);
											} else {
												$("#profile-image-fail").html(data.result.errors.picture).show();
												$("#profile-image-success").hide();
											}        
										}',
        								],
									]); ?>
  
									<a href="#" class="btn btn-default">Редактировать профиль</a>
  
								<?php endif; ?>
      
                                           
							<!--<a href="#" class="btn btn-default">Upload profile image</a>-->
                        
								<br/>
								<br/>
								<div class="alert alert-success display-none" id="profile-image-success">Аватарка загружена</div>
								<div class="alert alert-danger display-none" id="profile-image-fail"></div>
							</div>
            
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
										<a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($item['nickname']) ? $item['nickname'] : $item['id']]); ?>">
											<?php echo Html::encode($item['username']); ?>
										</a>
									</div>
								<?php endforeach; ?>
							</div>

							<hr>
  
  
							<?php endif; ?>
            
							<?php if($user->about): ?>                            
								<div class="profile-description">
									<p><?php echo HtmlPurifier::process($user->about); ?></p>
								</div>
							<?php endif; ?>
            
							<div class="profile-bottom">
								<div class="profile-post-count">
									<span><?php echo $user->getPostCount(); ?> posts</span>
								</div>
							<div class="profile-followers">
								<a href="#" data-toggle="modal" data-target="#followersModal"><?php  echo $user->countFollowers() ?> followers</a>
							</div>
							<div class="profile-following">
								<a href="#" data-toggle="modal" data-target="#subscribesModal"><?php echo $user->countSubscriptions(); ?> subscriptions</a>    
							</div>
						</div>
            
          
            
					</article>

                    <div class="col-sm-12 col-xs-12">
                        <div class="row profile-posts">
                            <?php foreach($user->getPosts() as $post): ?>
                                <div class="col-md-4 profile-post">
                                    <a href="<?php  echo Url::to(['/post/default/view', 'id' => $post->getId()]); ?>">
                                        <img src="<?php echo Yii::$app->storage->getFile($post->filename); ?>" class="author-image" />
                                    </a>
                                </div>
                            <?php endforeach; ?>  
                        </div>
                    </div>

                                    
                </div>

            </div>
        </div>
    </div>
</div>


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
						<a href="<?php  echo Url::to(['/user/profile/view', 'nickname' => ($subscription['nickname']) ? $subscription['nickname'] : $subscription['id']]); ?>">
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
						<a href="<?php  echo Url::to(['/user/profile/view', 'nickname' => ($follower['nickname']) ? $follower['nickname'] : $follower['id']]); ?>">
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