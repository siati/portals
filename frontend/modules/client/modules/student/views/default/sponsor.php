<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\modules\client\modules\student\models\ApplicantSponsors */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\models\PostalCodes;
use frontend\modules\client\modules\student\models\ApplicantSponsors;
use common\models\StaticMethods;
?>

<?php $form = ActiveForm::begin(['id' => 'form-spnsr-det', 'enableAjaxValidation' => true]); ?>

<?= Html::activeHiddenInput($model, 'id') ?>

<?= Html::activeHiddenInput($model, 'applicant') ?>

<div class="gnrl-frm-divider">Sponsor Details</div>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'name', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'relationship', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->dropDownList(ApplicantSponsors::relatioships()) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'study_level', ['addon' => ['prepend' => ['content' => '<i class="fa fa-graduation-cap"></i>']]])->dropDownList(ApplicantSponsors::studyLevels()) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'phone', ['addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-phone"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'email', ['addon' => ['prepend' => ['content' => '<i class="fa fa-at"></i>']]])->textInput(['maxlength' => true]) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'postal_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-envelope-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'postal_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-building-o"></i>']]])->dropDownList(StaticMethods::modelsToArray(PostalCodes::allCodes(), 'id', 'town', false), ['prompt' => '-- Select Town --']) ?></td>
    </tr>
</table>

<?= Html::submitButton('Update', ['id' => 'spnsrs-btn-inner', 'class' => 'btn btn-primary pull-right', 'style' => 'display: none', 'name' => 'sponsors-button-inner']) ?>

<?php ActiveForm::end(); ?>
