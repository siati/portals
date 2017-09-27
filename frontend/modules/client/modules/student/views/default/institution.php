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

<div class="gnrl-frm stdt-psnl stdt-inst">

    <div class="gnrl-frm-cont">

        <?php $form = ActiveForm::begin(['id' => 'form-stdt-inst', 'enableAjaxValidation' => true]); ?>

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy fit-in-pn">

            <?= Html::activeHiddenInput($model, 'id') ?>

            <?= Html::activeHiddenInput($model, 'applicant') ?>

            <div class="gnrl-frm-divider">Institution Details</div>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'country', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmBaseEnums::countries(), ['prompt' => '-- Country --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'level_of_study', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmBaseEnums::studyLevels(), ['prompt' => '-- Study Level --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'institution_type', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmBaseEnums::institutionTypes(), ['prompt' => '-- Institution Type --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'admission_category', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmBaseEnums::admissionCategories(), ['prompt' => '-- Admission Category --']) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'institution_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmInstitution::institutions($model->country, $model->institution_type, LmBaseEnums::schoolTypeFromAdmissionCategory($model->admission_category)->VALUE, $active = LmBaseEnums::yesOrNo(LmBaseEnums::yes)->VALUE), ['prompt' => '-- Institution --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'institution_branch_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmInstitutionBranches::branches($model->institution_code, $active), ['prompt' => '-- Branch --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'course_category', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmBaseEnums::courseCategories(), ['prompt' => '-- Course Category --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'course_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmCourses::courses($model->institution_code, null, $model->level_of_study, null, $model->course_type, $model->course_category, $active), ['prompt' => '-- Course --']) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'faculty', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'department', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'course_type', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmBaseEnums::courseTypes(), ['prompt' => '-- Course Type --']) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'year_of_admission', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(StaticMethods::ranges($yr = date('Y'), $yr - 6, 1, true), ['prompt' => '-- Year --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'admission_month', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(StaticMethods::months(), ['prompt' => '-- Month --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'duration', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmBaseEnums::courseDurations(false), ['prompt' => '-- Duration --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'year_of_study', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmBaseEnums::studyYears(), ['prompt' => '-- Study Year --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'year_of_completion', ['addon' => ['prepend' => ['content' => '<i class="fa fa-envelope-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'annual_fees', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'amount_can_raise', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'amount_applied', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'need_bursary', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmBaseEnums::yesNo(), ['prompt' => '-- Bursary --']) ?></td>
                </tr>
            </table>

        </div>

        <div class="gnrl-frm-ftr">

            <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right', 'name' => 'personal-button']) ?>

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

        "
        , \yii\web\VIEW::POS_READY
)
?>