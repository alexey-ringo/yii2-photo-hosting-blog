<?php

/* @var $this yii\web\View  */
/* @var $model frontend\modules\post\models\forms\PostForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="post-default-index">
    
    <h1>Создать пост</h1>
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?php echo $form->field($model, 'picture')->fileInput(); ?>
    
    <?php echo $form->field($model, 'hashtag'); ?>
    
    <?php echo $form->field($model, 'description'); ?>
    
    <?php echo Html::submitButton('Применить'); ?>
    
    <?php ActiveForm::end(); ?>
    
    
</div>