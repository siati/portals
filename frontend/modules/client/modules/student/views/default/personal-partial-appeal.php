<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\modules\client\modules\student\models\Applicants */
/* @var $disabled boolean */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use frontend\modules\client\modules\student\models\Applicants;
?>

<?php $parents_both_alive = Applicants::parents_both_alive ?>
<?php $parents_father_alive = Applicants::parents_father_alive ?>
<?php $parents_mother_alive = Applicants::parents_mother_alive ?>
<?php $parents_neither_alive = Applicants::parents_neither_alive ?>
<?php $parents_divorced = Applicants::parents_divorced ?>
<?php $parents_separated = Applicants::parents_separated ?>
<?php $parents_single = Applicants::parents_single ?>
<?php $parents_abandoned = Applicants::parents_abandoned ?>
<?php $parents_not_applicable = Applicants::parents_not_applicable ?>

<?php $form = ActiveForm::begin(['id' => 'form-prsnl-prtl-apl', 'enableAjaxValidation' => true, 'action' => 'personal-partial', 'fieldConfig' => ['options' => ['class' => 'form-group-sm']]]); ?>

<?= Html::activeHiddenInput($model, 'id') ?>

<div class="gnrl-frm-divider">Personal Details</div>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'married', ['addon' => ['prepend' => ['content' => '<i class="fa fa-heart"></i>']]])->dropDownList(Applicants::marrieds(), ['disabled' => $disabled]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'disability', ['addon' => ['prepend' => ['content' => '<i class="fa fa-wheelchair"></i>']]])->dropDownList(Applicants::disabilities(), ['disabled' => $disabled]) ?></td>
    </tr>
    
    <tr>
        <td class="td-pdg-lft" colspan="2"><?= $form->field($model, 'other_disability', ['addon' => ['prepend' => ['content' => '<i class="fa fa-align-justify"></i>']]])->textInput(['maxlength' => true, 'disabled' => $disabled]) ?></td>
    </tr>
</table>


<div class="gnrl-frm-divider">Parental Status</div>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($model, 'parents', ['addon' => ['prepend' => ['content' => '<i class="fa fa-group"></i>']]])->dropDownList(Applicants::parentalStatuses(), ['disabled' => $disabled]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'father_death_cert_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-file-text"></i>']]])->textInput(['maxlength' => true, 'disabled' => $disabled]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($model, 'mother_death_cert_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-file-text"></i>']]])->textInput(['maxlength' => true, 'disabled' => $disabled]) ?></td>
    </tr>
</table>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(
        "
            function parentalStatusFields(stts) {
                $('#applicants-father_death_cert_no, #applicants-mother_death_cert_no').blur();
                
                if (stts === '$parents_not_applicable' || stts === '$parents_abandoned' || stts === '$parents_divorced' || stts === '$parents_separated' || stts === '$parents_single')
                    $('#applicants-father_death_cert_no, #applicants-mother_death_cert_no').parent().parent().hide();
                else
                if (stts === '$parents_neither_alive')
                    $('#applicants-father_death_cert_no, #applicants-mother_death_cert_no').parent().parent().show();
                else
                if (stts === '$parents_mother_alive') {
                    $('#applicants-mother_death_cert_no').val(null).parent().parent().hide();
                    $('#applicants-father_death_cert_no').parent().parent().show();
                } else
                if (stts === '$parents_father_alive') {
                    $('#applicants-father_death_cert_no').val(null).parent().parent().hide();
                    $('#applicants-mother_death_cert_no').parent().parent().show();
                } else
                if (stts === '$parents_both_alive')
                    $('#applicants-father_death_cert_no, #applicants-mother_death_cert_no').val(null).parent().parent().hide();
            }
            
            function bankBranches() {
                $.post('bank-branches', {'bank': $('#applicants-bank').val(), 'branch': $('#applicants-bank_branch').val()},
                    function (branches) {
                        $('#applicants-bank_branch').html(branches).blur();
                    }
                );
            }
        ", yii\web\View::POS_HEAD
)
?>


<?php
$this->registerJs(
        "
            /* show or hide parents death cert fields */
                $('#applicants-parents').change(
                    function () {
                        parentalStatusFields($(this).val())
                    }
                );
                
                $('#applicants-parents').change();
            /* show or hide parents death cert fields */

        ", yii\web\View::POS_READY
)
?>