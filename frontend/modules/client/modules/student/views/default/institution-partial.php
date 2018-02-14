<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\modules\client\modules\student\models\ApplicantsInstitution */
/* @var $tuition boolean */
/* @var $upkeep boolean */
/* @var $bursary boolean */
/* @var $narration boolean */
/* @var $disabled boolean */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\models\LmBaseEnums;
?>

<?php $form = ActiveForm::begin(['id' => 'form-stdt-inst-prtl', 'enableAjaxValidation' => true, 'action' => 'client/student/default/institution-partial', 'fieldConfig' => ['options' => ['class' => 'form-group-sm']]]); ?>

<?= Html::activeHiddenInput($model, 'id') ?>

<?= Html::activeHiddenInput($model, 'applicant') ?>

<div class="gnrl-frm-divider">Institution Details</div>

<?php if ($tuition || $upkeep): ?>

    <table>
        <tr>
            <?php if ($tuition): ?>
                <td class="td-pdg-lft"><?= $form->field($model, 'annual_fees', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true, 'disabled' => $disabled]) ?></td>
            <?php endif; ?>

            <?php if ($upkeep): ?>
                <td class="td-pdg-lft"><?= $form->field($model, 'annual_upkeep', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true, 'disabled' => $disabled]) ?></td>
            <?php endif; ?>
        </tr>
    </table>

    <table><tr><td>&nbsp;</td></tr></table>

<?php endif; ?>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'amount_can_raise', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true, 'disabled' => $disabled]) ?></td>

        <td class="td-pdg-lft"><?= $form->field($model, 'amount_applied', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true, 'disabled' => $disabled, 'readonly' => !$disabled]) ?></td>

        <?php if ($bursary): ?>
            <td class="td-pdg-lft"><?= $form->field($model, 'need_bursary', ['addon' => ['prepend' => ['content' => '<i class="fa fa-question"></i>']]])->dropDownList(LmBaseEnums::yesNo(), ['prompt' => '-- Bursary --', 'disabled' => $disabled]) ?></td>
        <?php endif; ?>
    </tr>
</table>

<?php if ($narration): ?>

    <table><tr><td>&nbsp;</td></tr></table>

    <table>
        <tr>
            <td class="td-pdg-lft"><?= $form->field($model, 'narration', ['addon' => ['prepend' => ['content' => '<i class="fa fa-align-justify"></i>']]])->textarea(['rows' => 20, 'maxlength' => true, 'disabled' => $disabled, 'style' => 'resize: none']) ?></td>
        </tr>
    </table>

<?php endif; ?>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(
        "
            function amountApplied() {
                $('#applicantsinstitution-amount_applied').val((val = ($('#applicantsinstitution-annual_fees').length ? $('#applicantsinstitution-annual_fees').val() * 1 : 0) + ($('#applicantsinstitution-annual_upkeep').length ? $('#applicantsinstitution-annual_upkeep').val() * 1 : 0) - $('#applicantsinstitution-amount_can_raise').val() * 1) > 0 ? val : null).blur();
            }
        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            /* auto compute amount applied */
                $('#applicantsinstitution-annual_fees, #applicantsinstitution-annual_upkeep, #applicantsinstitution-amount_can_raise').change(
                    function () {
                        amountApplied();
                    }
                );
                
                '$disabled' ? '' : $('#applicantsinstitution-annual_fees, #applicantsinstitution-annual_upkeep').change();
            /* auto compute amount applied */
        "
        , \yii\web\VIEW::POS_READY
)
?>