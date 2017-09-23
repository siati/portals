<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\modules\client\modules\student\models\ApplicantsGuarantors */
/* @var $relationship string */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\models\Counties;
use common\models\SubCounties;
use common\models\Constituencies;
use common\models\Wards;
use common\models\PostalCodes;
use common\models\StaticMethods;
use frontend\modules\client\modules\student\models\ApplicantsGuarantors;
?>

<?php $form = ActiveForm::begin(['id' => 'form-grntr-det', 'enableAjaxValidation' => true]); ?>

<?= Html::activeHiddenInput($model, 'id') ?>

<?= Html::activeHiddenInput($model, 'applicant') ?>

<div class="gnrl-frm-divider">Guarantor's Personal Details</div>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'fname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'mname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'lname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'yob', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar"></i>']]])->dropDownList(StaticMethods::ranges(ApplicantsGuarantors::oldest(), ApplicantsGuarantors::youngest(), 1, true)) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'gender', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(StaticMethods::genders(), ['prompt' => '-- Gender --']) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'id_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'phone', ['addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-phone"></i>']]])->textInput(['maxlength' => true]) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'email', ['addon' => ['prepend' => ['content' => '<i class="fa fa-at"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'occupation', ['addon' => ['prepend' => ['content' => '<i class="fa fa-hand-grab-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'kra_pin', ['addon' => ['prepend' => ['content' => '<i class="fa fa-certificate"></i>']]])->textInput(['maxlength' => true]) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'county', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(Counties::allCounties(), 'id', 'name', false), ['prompt' => '-- Select County --', 'onchange' => "countyChanged($(this).val(), $('#applicantsguarantors-sub_county').val(), $('#applicantsguarantors-sub_county'), '../../../site/dynamic-subcounties', $('#applicantsguarantors-constituency').val(), $('#applicantsguarantors-constituency'), '../../../site/dynamic-constituencies')"]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'sub_county', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(SubCounties::subcountiesForCounty($model->county), 'id', 'name', false), ['prompt' => '-- Select Subcounty --']) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'constituency', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(Constituencies::constituenciesForCounty($model->county), 'id', 'name', false), ['prompt' => '-- Select Constituency --', 'onchange' => "dynamicWards($(this).val(), $('#applicantsguarantors-ward').val(), $('#applicantsguarantors-ward'), '../../../site/dynamic-wards')"]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'ward', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(Wards::wardsForConstituency($model->constituency), 'id', 'name', false), ['prompt' => '-- Select Ward --']) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'location', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'sub_location', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'village', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'postal_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-envelope-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'postal_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-building-o"></i>']]])->dropDownList($postalCodes = StaticMethods::modelsToArray(PostalCodes::allCodes(), 'id', 'town', false), ['prompt' => '-- Select Town --']) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'employed', ['addon' => ['prepend' => ['content' => '<i class="fa fa-bank"></i>']]])->dropDownList(ApplicantsGuarantors::employeds()) ?></td>
    </tr>
</table>

<div id="oth-grntr-det">

    <div class="gnrl-frm-divider">Guarantor's Employment Details</div>

    <table>
        <tr>
            <td class="td-pdg-lft"><?= $form->field($model, 'employer_name', ['addon' => ['prepend' => ['content' => '<i class="fa fa-bank"></i>']]])->textInput(['maxlength' => true]) ?></td>
            <td class="td-pdg-lft"><?= $form->field($model, 'staff_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-badge"></i>']]])->textInput(['maxlength' => true]) ?></td>
            <td class="td-pdg-lft"><?= $form->field($model, 'employer_phone', ['addon' => ['prepend' => ['content' => '<i class="fa fa-phone"></i>']]])->textInput(['maxlength' => true]) ?></td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="td-pdg-lft"><?= $form->field($model, 'employer_email', ['addon' => ['prepend' => ['content' => '<i class="fa fa-envelope"></i>']]])->textInput(['maxlength' => true]) ?></td>
            <td class="td-pdg-lft"><?= $form->field($model, 'employer_postal_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-envelope-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
            <td class="td-pdg-lft"><?= $form->field($model, 'employer_postal_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-building-o"></i>']]])->dropDownList($postalCodes, ['prompt' => '-- Select Town --']) ?></td>
        </tr>
    </table>

</div>

<?= Html::submitButton('Update', ['id' => 'grntrs-btn-inner', 'class' => 'btn btn-primary pull-right', 'style' => 'display: none', 'name' => 'guarantors-button-inner']) ?>

<?php ActiveForm::end(); ?>
