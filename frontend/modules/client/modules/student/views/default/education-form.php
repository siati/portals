<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\modules\client\modules\student\models\EducationBackground */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\models\StaticMethods;
use frontend\modules\client\modules\student\models\EducationBackground;
?>

<?php $educationYears = EducationBackground::admissionAndExaminationYears($model->applicant, $model->study_level); ?>
<?php $isPriOrSec = in_array($lvl = $model->study_level, [$pri = EducationBackground::study_level_primary, $sec = EducationBackground::study_level_secondary]) ?>
<?php $isCertOrDip = in_array($lvl, [$cert = EducationBackground::study_level_certificate, $dip = EducationBackground::study_level_diploma]) ?>
<?php $dgr = EducationBackground::study_level_degree ?>
<?php $mst = EducationBackground::study_level_masters ?>
<?php $phd = EducationBackground::study_level_phd ?>


<?php $form = ActiveForm::begin(['id' => 'form-edcn-det', 'enableAjaxValidation' => true]); ?>

<?= Html::activeHiddenInput($model, 'id') ?>

<?= Html::activeHiddenInput($model, 'applicant') ?>

<div class="gnrl-frm-divider">Education Background Details</div>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'study_level', ['addon' => ['prepend' => ['content' => '<i class="fa fa-certificate"></i>']]])->dropDownList(EducationBackground::studyLevelsToDisplay($model->applicant, $model->study_level), ['disabled' => $isPriOrSec]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'institution_type', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->dropDownList(EducationBackground::institutionTypesToDisplay($model->study_level), ['disabled' => $isPriOrSec]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'school_type', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->dropDownList(EducationBackground::schoolTypes()) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'institution_name', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->textInput(['maxlength' => true]) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'course_name', ['addon' => ['prepend' => ['content' => '<i class="fa fa-book"></i>']]])->textInput(['maxlength' => true, 'disabled' => $isPriOrSec]) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'since', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar"></i>']]])->dropDownList($snc = StaticMethods::ranges($educationYears[1] + 10, $educationYears[0], 1, true)) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'till', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar"></i>']]])->dropDownList(StaticMethods::ranges(max($snc) + 4, $educationYears[0] + 2, 1, true)) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'exam_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-align-justify"></i>']]])->textInput(['maxlength' => true]) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'score', ['addon' => ['prepend' => ['content' => '<i class="fa fa-percent"></i>']]])->textInput(['maxlength' => true, 'disabled' => !$isPriOrSec]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'out_of', ['addon' => ['prepend' => ['content' => '<i class="fa fa-list-ol"></i>']]])->textInput(['maxlength' => true, 'disabled' => !$isPriOrSec]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'grade', ['addon' => ['prepend' => ['content' => '<i class="fa fa-graduation-cap"></i>']]])->dropDownList(EducationBackground::merits($model->study_level), ['prompt' => '-- Grades --', 'disabled' => $isPriOrSec || in_array($model->study_level, [$mst, $phd])]) ?></td>
        <?php if (in_array($model->study_level, [$pri, $sec])): ?>
            <td class="td-pdg-lft"><?= $form->field($model, 'sponsored', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->dropDownList(EducationBackground::sponsoreds()) ?></td>
        <?php endif; ?>
    </tr>
</table>

<?= Html::submitButton('Update', ['id' => 'edcn-btn-inner', 'class' => 'btn btn-primary pull-right', 'style' => 'display: none', 'name' => 'education-button-inner']) ?>

<?php ActiveForm::end(); ?>
