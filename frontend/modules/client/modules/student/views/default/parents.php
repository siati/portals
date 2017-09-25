<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $applicant integer */
/* @var $relations array */
/* @var $relationships array */
/* @var $form_content string */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use frontend\modules\client\modules\student\models\Applicants;
use frontend\modules\client\modules\student\models\ApplicantsParents;

$this->title = 'Parent\'s Details';
$this->params['breadcrumbs'][] = $this->title;
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

<?php $relationship_father = ApplicantsParents::relationship_father ?>
<?php $relationship_mother = ApplicantsParents::relationship_mother ?>
<?php $relationship_guardian = ApplicantsParents::relationship_guardian ?>
<?php $relationship_guardian_to_father = ApplicantsParents::relationship_guardian_to_father ?>
<?php $relationship_guardian_to_mother = ApplicantsParents::relationship_guardian_to_mother ?>

<?php $paysFees = ApplicantsParents::pays_fees_yes ?>

<div class="gnrl-frm prnt-det">

    <div class="gnrl-frm-cont">

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy gnrl-frm-pdg-top-0 gnrl-frm-pdg-rgt-0 gnrl-frm-pdg-btm-0 fit-in-pn" style="height: 85%">

            <div class="prt-tab-pn pull-left rlnshp-lst">
                <?php ActiveForm::begin(['id' => 'form-prntl-stts-btn']); ?>
                <input name='Applicants[id]' type='hidden' value='<?= $applicant ?>'>
                <?= Html::submitButton('Parental Status', ['class' => 'prtl-clk-btn btn btn-primary prnt-nav-btn', 'name' => 'prntl-stts-btn-btn']) ?>
                <?php ActiveForm::end(); ?>


                <?php ActiveForm::begin(['id' => 'form-prnt-det-btn']); ?>
                <input name='ApplicantsParents[applicant]' type='hidden' value='<?= $applicant ?>'>
                <input name='ApplicantsParents[relationship]' type='hidden' id="relationship">
                <?php foreach ($relations as $relationship => $relation): ?>
                    <?php if (empty($relationships) || in_array($relationship, $relationships)): ?>
                        <?= Html::button($relation, ['class' => "prt-rln rln-$relationship btn btn-primary prnt-nav-btn", 'name' => 'prnt-det-btn-btn', 'rltn' => $relationship]) ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php ActiveForm::end(); ?>
            </div>

            <div class="prt-frm-pn pull-right prnt-frm-hr">
                <?= $form_content ?>
            </div>

        </div>

        <div class="gnrl-frm-ftr">

            <?= Html::button('Update', ['id' => 'prts-btn', 'class' => 'btn btn-primary pull-right', 'name' => 'parents-button']) ?>

        </div>

    </div>
</div>

<?php
$this->registerJs(
        "
            function manageRelationshipButtons(stts) {
                if (stts === '$parents_not_applicable')
                    $('.prt-rln').hide();
                else
                if (stts === '$parents_neither_alive' || stts === '$parents_abandoned') {
                    $('.rln-$relationship_father, .rln-$relationship_mother, .rln-$relationship_guardian_to_father, .rln-$relationship_guardian_to_mother').hide();
                    $('.rln-$relationship_guardian').show();
                } else
                if (stts === '$parents_mother_alive') {
                    $('.rln-$relationship_father, .rln-$relationship_guardian, .rln-$relationship_guardian_to_father').hide();
                    $('.rln-$relationship_mother, .rln-$relationship_guardian_to_mother').show();
                } else
                if (stts === '$parents_father_alive') {
                    $('.rln-$relationship_mother, .rln-$relationship_guardian, .rln-$relationship_guardian_to_mother').hide();
                    $('.rln-$relationship_father, .rln-$relationship_guardian_to_father').show();
                } else
                if (stts === '$parents_both_alive' || stts === '$parents_divorced' || stts === '$parents_separated' || stts === '$parents_single') {
                    $('.rln-$relationship_guardian').hide();
                    $('.rln-$relationship_father, .rln-$relationship_mother, .rln-$relationship_guardian_to_father, .rln-$relationship_guardian_to_mother').show();
                }
            }
            
            function parentalStatusFields(stts) {
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

            function checkParentStatus() {
                post = $('#form-prntl-stts').length ? $('#form-prntl-stts').serialize() : {'Applicants[id]': '$applicant'};

                $.post('check-parent-status', post,
                    function (statuses) {
                        if (statuses[0] === false)
                            $('.prt-rln').hide();
                        else {
                            $('.prt-rln').show();
                            $('#applicants-parents').length ? manageRelationshipButtons($('#applicants-parents').val()) : '';
                            statuses[1] ? '' : $('.rln-$relationship_guardian_to_father').hide();
                            statuses[2] ? '' : $('.rln-$relationship_guardian_to_mother').hide();
                        }
                        
                        $('#applicants-parents').length ? parentalStatusFields($('#applicants-parents').val()) : '';
                    }
                );
            }
            
            function parentIsGuarantor(opn) {
                opn ? $('#oth-prt-det').show() :
                    $.post('parent-is-guarantor', {'ApplicantsParents[applicant]': $('#applicantsparents-applicant').val(), 'ApplicantsParents[id_no]': $('#applicantsparents-id_no').val()},
                        function(opn) {
                            opn ? $('#oth-prt-det').show() : $('#oth-prt-det').hide();
                        }
                    );
            }
            
            function highlightActiveParentTab() {
                btn = $('#form-prntl-stts').length ? $('.prtl-clk-btn') : $('.rln-' + $('#applicantsparents-relationship').val());
                
                btn.removeClass('btn-primary').addClass('btn-success');
            }
            
            function totalAnnualIncome() {
                ttl = $('#applicantsparents-farming_annual').val() * 1 + $('#applicantsparents-business_annual').val() * 1 + $('#applicantsparents-other_annual').val() * 1 + $('#applicantsparents-govt_support_annual').val() * 1 + $('#applicantsparents-relief_annual').val() * 1 + $('#applicantsparents-monthly_pension').val() * 12 + $('#applicantsparents-gross_monthly_salary').val() * 12;
                $('#applicantsparents-total_annual_income').val(ttl > 0 ? ttl : '').blur();
            }

        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            /* highlight the active tab */
                highlightActiveParentTab();
            /* highlight the active tab */
            
            /* perform relevant checks when parental status changes */
                $('#applicants-parents, #applicants-father_death_cert_no, #applicants-mother_death_cert_no').change(
                    function () {
                        checkParentStatus();
                    }
                );
            /* perform relevant checks when parental status changes */
            
            /* show and hide employment and income details appropriately */
                $('#applicantsparents-id_no, #applicantsparents-pays_fees').change(
                    function() {
                        parentIsGuarantor($('#applicantsparents-pays_fees').val() === '$paysFees');
                    }
                );
            /* show and hide employment and income details appropriately */
            
            /* trigger show or hide employment and income details appropriately */
                $('#applicantsparents-pays_fees').change();
            /* trigger show or hide employment and income details appropriately */
            
            /* compute parents annual income */
                $('#applicantsparents-pays_fees, #applicantsparents-farming_annual, #applicantsparents-business_annual, #applicantsparents-other_annual, #applicantsparents-govt_support_annual, #applicantsparents-relief_annual, #applicantsparents-monthly_pension, #applicantsparents-gross_monthly_salary').change(
                    function() {
                        totalAnnualIncome();
                    }
                );
            /* compute parents annual income */
            
            /* hide unrequired parents tabs and death certificate no. fields */
                checkParentStatus();
            /* hide unrequired parents tabs and death certificate no. fields */
            
            /* load various parents details on the view */
                $('.prt-rln').click(
                    function () {
                        $('#relationship').val($(this).attr('rltn'));
                        $('#form-prnt-det-btn').submit();
                    }
                );
            /* load various parents details on the view */

            /* hide the update button on parental status form */
                $('#form-prntl-stts').length ? $('#prts-btn').hide() : '';
            /* hide the update button on parental status form */

            /* the real submit button is hidden */
                $('#prts-btn').click(
                    function (event) {
                        event.preventDefault();
                        $('#prts-btn-inner').click();
                    }
                );
            /* the real submit button is hidden */

        "
        , \yii\web\VIEW::POS_READY
)
?>
