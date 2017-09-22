<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = 'Signup Form';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <div class="site-signup-cont">
        
        <h1><?= $this->title ?></h1>

        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <?= $form->field($model, 'id_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card"></i>']]])->textInput(['autofocus' => true])->label('National ID. No.') ?>

        <?= $form->field($model, 'email', ['addon' => ['prepend' => ['content' => '<i class="fa fa-at"></i>']]])->label('Email Address') ?>

        <?= $form->field($model, 'phone', ['addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-phone"></i>']]])->label('Phone No.') ?>

        <?= $form->field($model, 'username', ['addon' => ['prepend' => ['content' => '<i class="fa fa-user"></i>']]])->label('Username') ?>

        <?= $form->field($model, 'password', ['addon' => ['prepend' => ['content' => '<i class="fa fa-key"></i>']]])->passwordInput()->label('Password') ?>

        <?= $form->field($model, 'confirm_password', ['addon' => ['prepend' => ['content' => '<i class="fa fa-lock"></i>']]])->passwordInput()->label('Confirm Password') ?>

        <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
