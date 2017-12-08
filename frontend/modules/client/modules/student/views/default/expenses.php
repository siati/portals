<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $applicant integer */
/* @var $family_expenses \frontend\modules\client\modules\student\models\ApplicantsFamilyExpenses */
/* @var $sibling_expenses \frontend\modules\client\modules\student\models\ApplicantsSiblingEducationExpenses */
/* @var $saved boolean */
/* @var $save_attempt boolean */
/* @var $saved2 boolean */
/* @var $save_attempt2 boolean */

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = 'Family And Sibling Education Expenses';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="gnrl-frm stdt-exps">

    <div class="gnrl-frm-cont">

        <?php ActiveForm::begin(['id' => 'form-sblg-nvgt']); ?>

        <input type="hidden" name="applicant" value="<?= $applicant ?>">

        <input type="hidden" name="ApplicantsSiblingEducationExpenses[id]" id="id">

        <?php ActiveForm::end(); ?>


        <?php $form = ActiveForm::begin(['id' => 'form-stdt-exps', 'enableAjaxValidation' => true, 'validateOnSubmit' => false]); ?>

        <input type="hidden" name="applicant" value="<?= $applicant ?>">

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy fit-in-pn">

            <?= $this->render('family-expenses', ['form' => $form, 'models' => $family_expenses]) ?>

            <?= $this->render('sibling-expenses', ['form' => $form, 'sibling_expenses' => $sibling_expenses, 'model' => empty($sibling_expense) ? end($sibling_expenses) : $sibling_expense]) ?>

        </div>

        <div class="gnrl-frm-ftr">

            <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right', 'name' => 'expenses-button']) ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$swals = [false, false, false];

if ($save_attempt || $save_attempt2) {
    if ($save_attempt && $save_attempt2)
        if ($saved && $saved2)
            $swals = ['Success', 'Family Expenses<br/><br/>and<br/><br/>Sibling Education Expenses<br/><br/>Saved', 'success'];
        elseif ($saved)
            $swals = ['Partial Save', 'Family Expenses Saved<br/><br/><br/><b>Sibling Education Expenses Not Saved</b>', 'success'];
        elseif ($saved2)
            $swals = ['Partial Save', 'Sibling Education Expenses Saved<br/><br/><br/><b>Family Expenses Not Saved</b>', 'success'];
        else
            $swals = ['Not Saved', 'Family Expenses<br/><br/>and<br/><br/>Sibling Education Expenses<br/><br/>Not Saved', 'error'];
    elseif ($save_attempt)
        $swals = $saved ? ['Success', 'Family Expenses Saved', 'success'] : ['Not Saved', 'Family Expenses Not Saved', 'error'];
    else
        $swals = $saved2 ? ['Success', 'Sibling Education Expenses Saved', 'success'] : ['Not Saved', 'Sibling Education Expenses Not Saved', 'error'];
}
?>

<?php
$this->registerJs(
        "
            function loadSibling(id) {
                $('#id').val(id);
                $('#form-sblg-nvgt').submit();
            }
            
            function dynamicInstTypes() {
                $.post('dynamic-inst-types', {'level_of_study': $('#applicantssiblingeducationexpenses-study_level').val(), 'institution_type': $('#applicantssiblingeducationexpenses-institution_type').val(), 'pri_sec': true},
                    function (institution_types) {
                        $('#applicantssiblingeducationexpenses-institution_type').html(institution_types).blur();
                    }
                );
            }

        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            /* load selected sibling */
                $('.sblg-slct').click(
                    function () {
                        loadSibling($(this).attr('sblg'));
                    }
                );
            /* load selected sibling */

            /* study level affects institution types */
                $('#applicantssiblingeducationexpenses-study_level').change(
                    function () {
                        dynamicInstTypes();
                    }
                );
            /* study level affects institution types */
            
            /* annual fees required against institution type and helb beneficiary */
                $('#applicantssiblingeducationexpenses-helb_beneficiary, #applicantssiblingeducationexpenses-institution_type').change(
                    function () {
                        $('#applicantssiblingeducationexpenses-annual_fees').blur();
                    }
                );
            /* annual fees required against institution type and helb beneficiary */

            /* well, just some css here */
                $('.sbln-exps-lft').css('max-height', '350px').css('overflow-x', 'hidden');
            /* well, just some css here */
            
            /* is saved */
               '$swals[0]' ? dataSaved('$swals[0]', '$swals[1]', '$swals[2]') : '';
            /* is saved */
        "
        , \yii\web\VIEW::POS_READY
)
?>