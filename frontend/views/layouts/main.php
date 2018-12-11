<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
//use frontend\assets\AppAsset;
use frontend\assets\Insta3Asset;
use common\widgets\Alert;

//AppAsset::register($this);
Insta3Asset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<?php $this->beginBody() ?>
<body class="home page">

    <div class="wrapper">
        <header>                
            <div class="header-top">
                <div class="container">
                    <div class="col-md-4 col-sm-4 col-md-offset-4 col-sm-offset-4 brand-logo">
                        <h1>
                            <a href="<?php echo Url::to(['/site/index']); ?>">
                                <img src="/img/logo.png" alt="">
                            </a>
                        </h1>
                    </div>			
                    <div class="col-md-4 col-sm-4 navicons-topbar">
                        <ul>
                            <li class="blog-search">
                                <a href="#" title="Search"><i class="fa fa-search"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="header-main-nav">
                <div class="container">
                    <div class="main-nav-wrapper">
                        <nav class="main-menu">
                            
                            <?php
                            $menuItems = [
                                ['label' => 'Лента новостей', 'url' => ['/site/index']],
                                ['label' => 'Популярные', 'url' => ['/popular/index']],
    
                            ];
                            if (Yii::$app->user->isGuest) {
                                $menuItems[] = ['label' => 'Зарегистрироваться', 'url' => ['/user/default/signup']];
                                $menuItems[] = ['label' => 'Войти', 'url' => ['/user/default/login']];
                            } else {
                                $menuItems[] = ['label' => 'Мой профиль', 'url' => ['/user/profile/view', 'nickname' => Yii::$app->user->identity->getNickname()]];
                                $menuItems[] = ['label' => 'Новый пост', 'url' => ['/post/default/create']];
                                $menuItems[] = '<li>'
                                        . Html::beginForm(['/user/default/logout'], 'post')
                                        . Html::submitButton(
                                                'Logout (' . Yii::$app->user->identity->username . ')<i class="fa fa-sign-out"></i>',
                                                ['class' => 'btn btn-link logout']
                                        )
                                        . Html::endForm()
                                        . '</li>';
                            }
                            echo Nav::widget([
                                'id' => 'yii-nav-mobile',
                                'options' => ['class' => 'menu navbar-nav navbar-right'],
                                'items' => $menuItems,
                            ]);
                            ?>
                            
                            
                        </nav>				
                    </div>
                </div>
            </div>


                            <?php
                            NavBar::begin([
                                'brandLabel' => Yii::$app->name,
                                'brandUrl' => Yii::$app->homeUrl,
                                'brandLabel' => '<img src="/img/favicon-32x32.png" class="img-responsive"/>InstaBlog',
                                'options' => [
                                    'class' => 'main-menu navbar-wrapper navbar-default',
                                    'id' => 'yii-navbar-desctop',
                                ],
                            ]);
                            
                            $menuItems = [
                                ['label' => 'Лента новостей', 'url' => ['/site/index']],
                                ['label' => 'Популярные', 'url' => ['/popular/index']],
    
                            ];
                            if (Yii::$app->user->isGuest) {
                                $menuItems[] = ['label' => 'Зарегистрироваться', 'url' => ['/user/default/signup']];
                                $menuItems[] = ['label' => 'Войти', 'url' => ['/user/default/login']];
                            } else {
                                $menuItems[] = ['label' => 'Мой профиль', 'url' => ['/user/profile/view', 'nickname' => Yii::$app->user->identity->getNickname()]];
                                $menuItems[] = ['label' => 'Новый пост', 'url' => ['/post/default/create']];
                                $menuItems[] = '<li>'
                                        . Html::beginForm(['/user/default/logout'], 'post')
                                        . Html::submitButton(
                                                'Logout (' . Yii::$app->user->identity->username . ')<i class="fa fa-sign-out"></i>',
                                                ['class' => 'btn btn-link logout']
                                        )
                                        . Html::endForm()
                                        . '</li>';
                            }
                            echo Nav::widget([
                                'id' => 'yii-nav-desctop',
                                'options' => ['class' => 'menu navbar-nav navbar-right'],
                                'items' => $menuItems,
                            ]);
                            
                            NavBar::end();
                            ?>
                            

        </header>	
        <div class="container full">
            
    <?= Alert::widget() ?>
    <?= $content ?>
            
        </div>
        <!--Добавил div class=push и вынес footer за пределы wrapper-->
        <div class="push"></div>
        
    </div>
    
    <footer>                
        <div class="footer">
            <div class="back-to-top-page">
                <a class="back-to-top"><i class="fa fa-angle-double-up"></i></a>
            </div>
            <p class="text">Images | 2017</p>
        </div>
    </footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>