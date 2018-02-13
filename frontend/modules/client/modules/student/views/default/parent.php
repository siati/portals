<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $parent \frontend\modules\client\modules\student\models\ApplicantsParents */
/* @var $relationship string */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\models\Counties;
use common\models\SubCounties;
use common\models\Constituencies;
use common\models\Wards;
use common\models\PostalCodes;
use common\models\StaticMethods;
use common\models\LmBaseEnums;
use frontend\modules\client\modules\student\models\ApplicantsParents;
?>

<?php $pre = Yii::$app->request->isAjax ? 'site' : '../../../site' ?>

<?php $form = ActiveForm::begin(['id' => 'form-prnt-det', 'enableAjaxValidation' => true]); ?>

<?= Html::activeHiddenInput($parent, 'id') ?>

<?= Html::activeHiddenInput($parent, 'applicant') ?>

<?= Html::activeHiddenInput($parent, 'relationship') ?>

<div class="gnrl-frm-divider"><?= $relationship ?>'s Personal Details</div>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($parent, 'fname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($parent, 'mname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($parent, 'lname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($parent, 'yob', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar"></i>']]])->dropDownList(StaticMethods::ranges(ApplicantsParents::oldest(), ApplicantsParents::youngest(), 1, true)) ?></td>
    </tr>
</table>

<table>
    <tr>
        <?php if (!in_array($parent->relationship, [ApplicantsParents::relationship_father, ApplicantsParents::relationship_mother])): ?>
        <td class="td-pdg-lft"><?= $form->field($parent, 'gender', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmBaseEnums::genders(), ['prompt' => '-- Gender --']) ?></td>
        <?php endif ?>
        <td class="td-pdg-lft"><?= $form->field($parent, 'birth_cert_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-child"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($parent, 'id_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($parent, 'kra_pin', ['addon' => ['prepend' => ['content' => '<i class="fa fa-certificate"></i>']]])->textInput(['maxlength' => true]) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($parent, 'phone', ['addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-phone"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($parent, 'email', ['addon' => ['prepend' => ['content' => '<i class="fa fa-at"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($parent, 'postal_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-envelope-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($parent, 'postal_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-building-o"></i>']]])->dropDownList($postalCodes = StaticMethods::modelsToArray(PostalCodes::allCodes(), 'id', 'town', false), ['prompt' => '-- Select Town --']) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($parent, 'county', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(Counties::allCounties(), 'id', 'name', false), ['prompt' => '-- Select County --', 'onchange' => "countyChanged($(this).val(), $('#applicantsparents-sub_county').val(), $('#applicantsparents-sub_county'), '$pre/dynamic-subcounties', $('#applicantsparents-constituency').val(), $('#applicantsparents-constituency'), '$pre/dynamic-constituencies')"]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($parent, 'sub_county', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(SubCounties::subcountiesForCounty($parent->county), 'id', 'name', false), ['prompt' => '-- Select Subcounty --']) ?></td>
        <td class="td-pdg-lft"><?= $form->field($parent, 'constituency', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(Constituencies::constituenciesForCounty($parent->county), 'id', 'name', false), ['prompt' => '-- Select Constituency --', 'onchange' => "dynamicWards($(this).val(), $('#applicantsparents-ward').val(), $('#applicantsparents-ward'), '$pre/dynamic-wards')"]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($parent, 'ward', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(Wards::wardsForConstituency($parent->constituency), 'id', 'name', false), ['prompt' => '-- Select Ward --']) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($parent, 'location', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($parent, 'sub_location', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($parent, 'village', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <?php if ($parent->canPayFees()): ?>
            <td class="td-pdg-lft"><?= $form->field($parent, 'pays_fees', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->dropDownList(ApplicantsParents::paysFees()) ?></td>
        <?php endif; ?>
    </tr>
</table>

<?php if ($parent->isNewRecord || $parent->canPayFees() || is_object($parent->isGuarantor())): ?>
    <div id="oth-prt-det">

        <div class="gnrl-frm-divider"><?= $relationship ?>'s Employment Details</div>

        <table>
            <tr>
                <td class="td-pdg-lft"><?= $form->field($parent, 'education_level', ['addon' => ['prepend' => ['content' => '<i class="fa fa-graduation-cap"></i>']]])->dropDownList(LmBaseEnums::studyLevels()) ?></td>
                <td class="td-pdg-lft"><?= $form->field($parent, 'occupation', ['addon' => ['prepend' => ['content' => '<i class="fa fa-hand-grab-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                <td class="td-pdg-lft"><?= $form->field($parent, 'employed', ['addon' => ['prepend' => ['content' => '<i class="fa fa-bank"></i>']]])->dropDownList(LmBaseEnums::yesNo()) ?></td>
                <td class="td-pdg-lft"><?= $form->field($parent, 'staff_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-badge"></i>']]])->textInput(['maxlength' => true]) ?></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="td-pdg-lft"><?= $form->field($parent, 'employer_name', ['addon' => ['prepend' => ['content' => '<i class="fa fa-bank"></i>']]])->textInput(['maxlength' => true]) ?></td>
                <td class="td-pdg-lft"><?= $form->field($parent, 'employer_phone', ['addon' => ['prepend' => ['content' => '<i class="fa fa-phone"></i>']]])->textInput(['maxlength' => true]) ?></td>
                <td class="td-pdg-lft"><?= $form->field($parent, 'employer_email', ['addon' => ['prepend' => ['content' => '<i class="fa fa-envelope"></i>']]])->textInput(['maxlength' => true]) ?></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="td-pdg-lft"><?= $form->field($parent, 'employer_postal_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-envelope-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                <td class="td-pdg-lft"><?= $form->field($parent, 'employer_postal_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-building-o"></i>']]])->dropDownList($postalCodes, ['prompt' => '-- Select Town --']) ?></td>
                <td class="td-pdg-lft"><?= $form->field($parent, 'gross_monthly_salary', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
            </tr>
        </table>

        <div class="gnrl-frm-divider"><?= $relationship ?>'s Income Details</div>

        <?php $parent->totalAnnualIncome() ?>
        
        <table>
            <tr>
                <td class="td-pdg-lft"><?= $form->field($parent, 'total_annual_income', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true, 'readonly' => true]) ?></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="td-pdg-lft" style="width: 33.3333%"><?= $form->field($parent, 'farming_annual', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
                <td class="td-pdg-lft" style="width: 33.3333%"><?= $form->field($parent, 'business_annual', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
                <td class="td-pdg-lft" style="width: 33.3333%"><?= $form->field($parent, 'other_annual', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
            </tr>
            <tr>
                <td class="td-pdg-lft"><?= $form->field($parent, 'govt_support_annual', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
                <td class="td-pdg-lft"><?= $form->field($parent, 'relief_annual', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
                <td class="td-pdg-lft"><?= $form->field($parent, 'monthly_pension', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
            </tr>
        </table>

    </div>
<?php endif; ?>

<?= Html::submitButton('Update', ['id' => 'prts-btn-inner', 'class' => 'btn btn-primary pull-right', 'style' => 'display: none', 'name' => 'parents-button-inner']) ?>

<?php ActiveForm::end(); ?>
