<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = 'Sign In';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="gnrl-frm usr-lgn">

    <div class="gnrl-frm-cont">

        <?php $form = ActiveForm::begin(['id' => 'form-login', 'enableAjaxValidation' => true]); ?>

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy fit-in-pn">

            <br/>

            <?= $form->field($model, 'username', ['addon' => ['prepend' => ['content' => '<i class="fa fa-user"></i>']]])->textInput(['autofocus' => true, 'placeholder' => 'Username or Email', 'style' => 'text-align: center'])->label(false)->error(['style' => 'text-align: center']) ?>

            <br/>

            <?= $form->field($model, 'password', ['addon' => ['prepend' => ['content' => '<i class="fa fa-lock"></i>']]])->passwordInput(['placeholder' => 'Password', 'style' => 'text-align: center'])->label(false)->error(['style' => 'text-align: center']) ?>

        </div>

        <div class="gnrl-frm-ftr">

            <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
