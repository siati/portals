<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\modules\client\modules\student\models\ApplicantsInstitution */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\models\StaticMethods;
use common\models\LmBaseEnums;
use common\models\LmInstitution;
use common\models\LmInstitutionBranches;
use common\models\LmCourses;

$this->title = 'Institution Details';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="gnrl-frm stdt-inst">

    <div class="gnrl-frm-cont">

        <?php $form = ActiveForm::begin(['id' => 'form-stdt-inst', 'enableAjaxValidation' => true]); ?>

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy fit-in-pn">

            <?= Html::activeHiddenInput($model, 'id') ?>

            <?= Html::activeHiddenInput($model, 'applicant') ?>

            <div class="gnrl-frm-divider">Institution Details</div>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'country', ['addon' => ['prepend' => ['content' => '<i class="fa fa-globe"></i>']]])->dropDownList(LmBaseEnums::countries(), ['prompt' => '-- Country --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'level_of_study', ['addon' => ['prepend' => ['content' => '<i class="fa fa-graduation-cap"></i>']]])->dropDownList(LmBaseEnums::studyLevels(), ['prompt' => '-- Study Level --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'institution_type', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->dropDownList(LmBaseEnums::institutionTypes($level_of_study = LmBaseEnums::byNameAndValue(LmBaseEnums::study_level, $model->level_of_study)->ELEMENT, false), ['prompt' => '-- Institution Type --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'admission_category', ['addon' => ['prepend' => ['content' => '<i class="fa fa-book"></i>']]])->dropDownList(LmBaseEnums::admissionCategories($level_of_study), ['prompt' => '-- Admission Category --']) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'institution_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->dropDownList(LmInstitution::institutions($model->country, $model->institution_type, LmBaseEnums::schoolTypeFromAdmissionCategory($model->admission_category)->VALUE, $active = LmBaseEnums::yesOrNo(LmBaseEnums::yes)->VALUE), ['prompt' => '-- Institution --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'institution_branch_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->dropDownList(LmInstitutionBranches::branches($model->institution_code, $active), ['prompt' => '-- Branch --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'course_category', ['addon' => ['prepend' => ['content' => '<i class="fa fa-book"></i>']]])->dropDownList(LmBaseEnums::courseCategories(), ['prompt' => '-- Course Category --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'course_type', ['addon' => ['prepend' => ['content' => '<i class="fa fa-book"></i>']]])->dropDownList(LmBaseEnums::courseTypes(), ['prompt' => '-- Course Type --']) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'faculty', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'department', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'course_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-book"></i>']]])->dropDownList(LmCourses::courses($model->institution_code, null, $model->level_of_study, null, null, $model->course_category, $active), ['prompt' => '-- Course --']) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'year_of_admission', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar-o"></i>']]])->dropDownList(StaticMethods::ranges($yr = date('Y'), $yr - 6, 1, true), ['prompt' => '-- Year --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'admission_month', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar-o"></i>']]])->dropDownList(StaticMethods::months(), ['prompt' => '-- Month --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'registration_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-badge"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'duration', ['addon' => ['prepend' => ['content' => '<i class="fa fa-clock-o"></i>']]])->dropDownList(LmBaseEnums::courseDurations($model->level_of_study), ['prompt' => '-- Duration --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'year_of_completion', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar-o"></i>']]])->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'year_of_study', ['addon' => ['prepend' => ['content' => '<i class="fa fa-hourglass-half"></i>']]])->dropDownList(LmBaseEnums::studyYears($model->level_of_study), ['prompt' => '-- Study Year --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'annual_fees', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'annual_upkeep', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'amount_can_raise', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'amount_applied', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'need_bursary', ['addon' => ['prepend' => ['content' => '<i class="fa fa-question"></i>']]])->dropDownList(LmBaseEnums::yesNo(), ['prompt' => '-- Bursary --']) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'narration', ['addon' => ['prepend' => ['content' => '<i class="fa fa-align-justify"></i>']]])->textarea(['rows' => 4, 'maxlength' => true, 'style' => 'resize: none']) ?></td>
                </tr>
            </table>

        </div>

        <div class="gnrl-frm-ftr">

            <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right', 'name' => 'institution-button']) ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$this->registerJs(
        "
            function dynamicInstitutions() {
                $.post('dynamic-institutions', {'country': $('#applicantsinstitution-country').val(), 'level_of_study': $('#applicantsinstitution-level_of_study').val(), 'institution_type': $('#applicantsinstitution-institution_type').val(), 'admission_category': $('#applicantsinstitution-admission_category').val(), 'institution_code': $('#applicantsinstitution-institution_code').val()},
                    function (institutions) {
                        $('#applicantsinstitution-institution_code').html(institutions).change().blur();
                    }
                );
            }

            function dynamicInstitutionBranches() {
                $.post('dynamic-institution-branches', {'institution_code': $('#applicantsinstitution-institution_code').val(), 'institution_branch_code': $('#applicantsinstitution-institution_branch_code').val()},
                    function (institution_branches) {
                        $('#applicantsinstitution-institution_branch_code').html(institution_branches).change().blur();
                    }
                );
            }

            function dynamicCourses() {
                $.post('dynamic-courses', {'institution_code': $('#applicantsinstitution-institution_code').val(), 'institution_branch_code': '', 'level_of_study': $('#applicantsinstitution-level_of_study').val(), 'faculty': '', 'course_type': '', 'course_category': $('#applicantsinstitution-course_category').val(), 'course_code': $('#applicantsinstitution-course_code').val()},
                    function (courses) {
                        $('#applicantsinstitution-course_code').html(courses).blur();
                    }
                );
            }
            
            function dynamicInstTypes() {
                $.post('dynamic-inst-types', {'level_of_study': $('#applicantsinstitution-level_of_study').val(), 'institution_type': $('#applicantsinstitution-institution_type').val()},
                    function (institution_types) {
                        $('#applicantsinstitution-institution_type').html(institution_types).blur();
                    }
                );
            }
            
            function dynamicAdmissionCategories() {
                $.post('dynamic-admission-categories', {'level_of_study': $('#applicantsinstitution-level_of_study').val(), 'admission_category': $('#applicantsinstitution-admission_category').val()},
                    function (admission_categories) {
                        $('#applicantsinstitution-admission_category').html(admission_categories).blur();
                    }
                );
            }
            
            function courseDurations() {
                $.post('dynamic-course-durations', {'level_of_study': $('#applicantsinstitution-level_of_study').val(), 'duration': $('#applicantsinstitution-duration').val()},
                    function (durations) {
                        $('#applicantsinstitution-duration').html(durations).change().blur();
                    }
                );
            }
            
            function studyYears() {
                $.post('dynamic-study-years', {'level_of_study': $('#applicantsinstitution-level_of_study').val(), 'year_of_study': $('#applicantsinstitution-year_of_study').val()},
                    function (years_of_study) {
                        $('#applicantsinstitution-year_of_study').html(years_of_study).blur();
                    }
                );
            }
            
            function completionYear() {
                $.post('completion-year', {'year_of_admission': $('#applicantsinstitution-year_of_admission').val(), 'admission_month': $('#applicantsinstitution-admission_month').val(), 'duration': $('#applicantsinstitution-duration').val()},
                    function (year_of_completion) {
                        $('#applicantsinstitution-year_of_completion').val(year_of_completion).blur();
                    }
                );
            }

            function amountApplied() {
                $('#applicantsinstitution-amount_applied').val((val = $('#applicantsinstitution-annual_fees').val() * 1 + ($('#applicantsinstitution-annual_upkeep').length ? $('#applicantsinstitution-annual_upkeep').val() * 1 : 0) - $('#applicantsinstitution-amount_can_raise').val() * 1) > 0 ? val : null).blur();
            }

        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            $('#applicantsinstitution-country, #applicantsinstitution-level_of_study, #applicantsinstitution-institution_type, #applicantsinstitution-admission_category').change(
                function () {
                    dynamicInstitutions();
                }
            );

            $('#applicantsinstitution-institution_code').change(
                function () {
                    dynamicInstitutionBranches();
                }
            );

            $('#applicantsinstitution-level_of_study').change(
                function () {
                    dynamicInstTypes();
                    dynamicAdmissionCategories();
                    courseDurations();
                    studyYears();
                }
            );
            
            $('#applicantsinstitution-institution_code, #applicantsinstitution-institution_branch_code, #applicantsinstitution-level_of_study, #applicantsinstitution-faculty, #applicantsinstitution-course_type, #applicantsinstitution-course_category').change(
                function () {
                    dynamicCourses();
                }
            );

            $('#applicantsinstitution-year_of_admission, #applicantsinstitution-admission_month, #applicantsinstitution-duration').change(
                function () {
                    completionYear();
                }
            );
            
            $('#applicantsinstitution-annual_fees, #applicantsinstitution-annual_upkeep, #applicantsinstitution-amount_can_raise').change(
                function () {
                    amountApplied();
                }
            );
        "
        , \yii\web\VIEW::POS_READY
)
?>