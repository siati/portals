<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\modules\client\modules\student\models\ApplicantsInstitution */
/* @var $saved boolean */

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

<?php $pre = Yii::$app->request->isAjax ? 'client/student/default/' : '' ?>

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
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'course_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-book"></i>']]])->dropDownList(LmCourses::courses($model->institution_code, null, $model->level_of_study, null, null, $model->course_category, $active), ['prompt' => '-- Course --']) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'year_of_admission', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar-o"></i>']]])->dropDownList(StaticMethods::ranges($yr = date('Y'), $yr - 6, 1, true), ['prompt' => '-- Year --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'admission_month', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar-o"></i>']]])->dropDownList(StaticMethods::months(), ['prompt' => '-- Month --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'registration_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-badge"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'duration', ['addon' => ['prepend' => ['content' => '<i class="fa fa-clock-o"></i>']]])->dropDownList(LmBaseEnums::courseDurations($model->level_of_study), ['prompt' => '-- Duration --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'year_of_study', ['addon' => ['prepend' => ['content' => '<i class="fa fa-hourglass-half"></i>']]])->dropDownList(LmBaseEnums::studyYears($model->level_of_study), ['prompt' => '-- Study Year --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'year_of_completion', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar-o"></i>']]])->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?></td>
                </tr>
            </table>

        </div>

        <div class="gnrl-frm-ftr">

            <?php if (Yii::$app->request->isAjax): ?>

                <?= Html::button('Update', ['class' => 'btn btn-primary pull-left', 'name' => 'institution-button']) ?>

                <div class="btn btn-danger pull-right" onclick="closeDialog()"><b>Close</b></div>

            <?php else: ?>

                <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right', 'name' => 'institution-button']) ?>

            <?php endif; ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$this->registerJs(
        "
            function dynamicInstitutions() {
                $.post('$pre' + 'dynamic-institutions', {'country': $('#applicantsinstitution-country').val(), 'level_of_study': $('#applicantsinstitution-level_of_study').val(), 'institution_type': $('#applicantsinstitution-institution_type').val(), 'admission_category': $('#applicantsinstitution-admission_category').val(), 'institution_code': $('#applicantsinstitution-institution_code').val()},
                    function (institutions) {
                        $('#applicantsinstitution-institution_code').html(institutions).change().blur();
                    }
                );
            }

            function dynamicInstitutionBranches() {
                $.post('$pre' + 'dynamic-institution-branches', {'institution_code': $('#applicantsinstitution-institution_code').val(), 'institution_branch_code': $('#applicantsinstitution-institution_branch_code').val()},
                    function (institution_branches) {
                        $('#applicantsinstitution-institution_branch_code').html(institution_branches).change().blur();
                    }
                );
            }

            function dynamicCourses() {
                $.post('$pre' + 'dynamic-courses', {'institution_code': $('#applicantsinstitution-institution_code').val(), 'institution_branch_code': '', 'level_of_study': $('#applicantsinstitution-level_of_study').val(), 'faculty': '', 'course_type': '', 'course_category': $('#applicantsinstitution-course_category').val(), 'course_code': $('#applicantsinstitution-course_code').val()},
                    function (courses) {
                        $('#applicantsinstitution-course_code').html(courses).blur();
                    }
                );
            }
            
            function dynamicInstTypes() {
                $.post('$pre' + 'dynamic-inst-types', {'level_of_study': $('#applicantsinstitution-level_of_study').val(), 'institution_type': $('#applicantsinstitution-institution_type').val()},
                    function (institution_types) {
                        $('#applicantsinstitution-institution_type').html(institution_types).blur();
                    }
                );
            }
            
            function dynamicAdmissionCategories() {
                $.post('$pre' + 'dynamic-admission-categories', {'level_of_study': $('#applicantsinstitution-level_of_study').val(), 'admission_category': $('#applicantsinstitution-admission_category').val()},
                    function (admission_categories) {
                        $('#applicantsinstitution-admission_category').html(admission_categories).blur();
                    }
                );
            }
            
            function courseDurations() {
                $.post('$pre' + 'dynamic-course-durations', {'level_of_study': $('#applicantsinstitution-level_of_study').val(), 'duration': $('#applicantsinstitution-duration').val()},
                    function (durations) {
                        $('#applicantsinstitution-duration').html(durations).change().blur();
                    }
                );
            }
            
            function studyYears() {
                $.post('$pre' + 'dynamic-study-years', {'level_of_study': $('#applicantsinstitution-level_of_study').val(), 'year_of_study': $('#applicantsinstitution-year_of_study').val()},
                    function (years_of_study) {
                        $('#applicantsinstitution-year_of_study').html(years_of_study).blur();
                    }
                );
            }
            
            function completionYear() {
                $.post('$pre' + 'completion-year', {'year_of_admission': $('#applicantsinstitution-year_of_admission').val(), 'admission_month': $('#applicantsinstitution-admission_month').val(), 'duration': $('#applicantsinstitution-duration').val()},
                    function (year_of_completion) {
                        $('#applicantsinstitution-year_of_completion').val(year_of_completion).blur();
                    }
                );
            }
            
            function dataSaved() {
                customSwal('Success', 'Institution Details Saved', '2500', 'success', false, true, 'ok', '#a5dc86', false, 'cancel');
            }
        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            /* load institutions dynamically */
                $('#applicantsinstitution-country, #applicantsinstitution-level_of_study, #applicantsinstitution-institution_type, #applicantsinstitution-admission_category').change(
                    function () {
                        dynamicInstitutions();
                    }
                );
            /* load institutions dynamically */

            /* load institution branches dynamically */
                $('#applicantsinstitution-institution_code').change(
                    function () {
                        dynamicInstitutionBranches();
                    }
                );
            /* load institution branches dynamically */

            /* level of study change has a number of effects */
                $('#applicantsinstitution-level_of_study').change(
                    function () {
                        dynamicInstTypes();
                        dynamicAdmissionCategories();
                        courseDurations();
                        studyYears();
                    }
                );
            /* level of study change has a number of effects */

            /* load courses dynamically */
                $('#applicantsinstitution-institution_code, #applicantsinstitution-institution_branch_code, #applicantsinstitution-level_of_study, #applicantsinstitution-faculty, #applicantsinstitution-course_type, #applicantsinstitution-course_category').change(
                    function () {
                        dynamicCourses();
                    }
                );
            /* load courses dynamically */

            /* completion years vary on a number of circumstances */
                $('#applicantsinstitution-year_of_admission, #applicantsinstitution-admission_month, #applicantsinstitution-duration').change(
                    function () {
                        completionYear();
                    }
                );
            /* completion years vary on a number of circumstances */
            
            /* is saved */
               '$saved' ? dataSaved('Success', 'Institution Details Saved', 'success') : '';
            /* is saved */
        "
        , \yii\web\VIEW::POS_READY
)
?>

<?php if (Yii::$app->request->isAjax): ?>

    <?php
    $this->registerJs(
            "
                function saveInstitution() {
                    form = $('#form-stdt-inst');
                    
                    post = form.serializeArray();

                    post.push({'name': 'sbmt', 'value': true});

                   $.post(form.attr('action'), post,
                        function(frm) {
                            $('#yii-modal-cnt').html(frm);
                        }
                    );
                }

            ", yii\web\View::POS_HEAD
    )
    ?>

    <?php
    $this->registerJs(
            "
                $('.fit-in-pn').css('max-height', $('#yii-modal-cnt').height() * 0.84 + 'px');
                
                $('[name=institution-button]').click(
                    function() {
                        saveInstitution();
                    }
                );
            "
            , \yii\web\VIEW::POS_READY)
    ?>

<?php endif; ?>