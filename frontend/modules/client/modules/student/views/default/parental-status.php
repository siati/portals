<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use frontend\modules\client\modules\student\models\Applicants;
?>

<?php $form = ActiveForm::begin(['id' => 'form-prntl-stts', 'enableAjaxValidation' => true]); ?>

<?= Html::activeHiddenInput($applicant, 'id') ?>

<?= $form->field($applicant, 'parents', ['addon' => ['prepend' => ['content' => '<i class="fa fa-male"></i>']]])->dropDownList(Applicants::parentalStatuses(), ['style' => 'width: 45%']) ?>

<?= $form->field($applicant, 'father_death_cert_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true, 'style' => 'width: 45%']) ?>

<?= $form->field($applicant, 'mother_death_cert_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true, 'style' => 'width: 45%']) ?>

<?php ActiveForm::end(); ?>
