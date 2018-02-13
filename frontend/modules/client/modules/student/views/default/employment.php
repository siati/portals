<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\modules\client\modules\student\models\ApplicantsEmployment */
/* @var $saved boolean */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use common\models\StaticMethods;
use common\models\LmBaseEnums;
use common\models\LmEmployers;
use common\models\Counties;
use common\models\PostalCodes;

$this->title = 'Employent Details';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $initial_employer = LmEmployers::searchEmployers($model->employer_name, null, LmEmployers::one) ?>

<?php $pre = Yii::$app->request->isAjax ? 'client/student/default/' : '' ?>

<div class="gnrl-frm stdt-eplmt">

    <div class="gnrl-frm-cont">

        <?php $form = ActiveForm::begin(['id' => 'form-stdt-eplmt', 'enableAjaxValidation' => true]); ?>

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy fit-in-pn">

            <?= Html::activeHiddenInput($model, 'id') ?>

            <?= Html::activeHiddenInput($model, 'applicant') ?>

            <div class="gnrl-frm-divider">Employment Details</div>

            <table>
                <tr>
                    <td class="td-pdg-lft" style="width: 40%">
                        <div class="form-group field-search_employer_name">
                            <label class="control-label" for="search_employer_name">Search Employer's Name</label>

                            <div class="input-group">
                                <input id="search_employer_name" class="form-control" name="search_employer_name" maxlength="10" aria-required="true" type="text" value="<?= empty($initial_employer->NAME) ? '' : $initial_employer->NAME ?>">
                                <span id="search_employer_name-btn" class="input-group-btn">
                                    <button type="button" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="td-pdg-lft" style="width: 60%">
                        <div class="form-group field-employer_name">
                            <label class="control-label" for="employer_name">Employers List</label>

                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-institution"></i>
                                </span>

                                <select id="employer_name" class="form-control" name="employer_name" aria-required="true">
                                    <?php if (!empty($initial_employer->ACCOUNTNUM) && !empty($initial_employer->NAME)): ?>
                                        <option>-- Select Employer --</option>
                                        <option value="<?= empty($initial_employer->ACCOUNTNUM) ? '' : $initial_employer->ACCOUNTNUM ?>" selected="selected"><?= empty($initial_employer->NAME) ? '' : $initial_employer->NAME ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'employer_name', ['addon' => ['prepend' => ['content' => '<i class="fa fa-globe"></i>']]])->dropDownList(empty($initial_employer->ACCOUNTNUM) || empty($initial_employer->NAME) ? [] : [empty($initial_employer->ACCOUNTNUM) ? '' : $initial_employer->ACCOUNTNUM => empty($initial_employer->NAME) ? '' : $initial_employer->NAME]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'county', ['addon' => ['prepend' => ['content' => '<i class="fa fa-graduation-cap"></i>']]])->dropDownList(StaticMethods::modelsToArray(Counties::allCounties(), 'id', 'name', false), ['prompt' => '-- County --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'town', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'kra_pin', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'employment_terms', ['addon' => ['prepend' => ['content' => '<i class="fa fa-graduation-cap"></i>']]])->dropDownList(LmBaseEnums::employmentTerms(), ['prompt' => '-- Terms --']) ?></td>
                    <td class="td-pdg-lft">
                        <?=
                        $form->field($model, 'employment_date', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar"></i>']]])->widget(DatePicker::className(), [
                            'options' => ['placeholder' => 'yyyy-mm-dd', 'maxlength' => true],
                            'type' => DatePicker::TYPE_INPUT,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                                ]
                        );
                        ?>
                    </td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'employment_period', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->dropDownList(\frontend\modules\client\modules\student\models\ApplicantsEmployment::employmentPeriod($model->employment_terms), ['prompt' => '-- Duration --']) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'department', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'division', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'section', ['addon' => ['prepend' => ['content' => '<i class="fa fa-book"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'email', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'postal_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-badge"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'postal_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-clock-o"></i>']]])->dropDownList(StaticMethods::modelsToArray(PostalCodes::allCodes(), 'id', 'town', false), ['prompt' => '-- Postal Town --']) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'phone', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'pf_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'basic_salary', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'net_salary', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

        </div>

        <div class="gnrl-frm-ftr">

            <?php if (Yii::$app->request->isAjax): ?>

                <?= Html::button('Update', ['class' => 'btn btn-primary pull-left', 'name' => 'employment-button']) ?>

                <div class="btn btn-danger pull-right" onclick="closeDialog()"><b>Close</b></div>

            <?php else: ?>

                <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right', 'name' => 'employment-button']) ?>

            <?php endif; ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$this->registerJs(
        "
            function dynamicEmployers() {
                $.post('$pre' + 'dynamic-employers', {'search_name': $('#search_employer_name').val(), 'selected': $('#employer_name').val()},
                    function (employers) {
                        $('#employer_name').html(employers);
                    }
                );
            }
            
            function employmentPeriods() {
                $.post('$pre' + 'employment-periods', {'terms': $('#applicantsemployment-employment_terms').val(), 'period': $('#applicantsemployment-employment_period').val()},
                    function (periods) {
                        $('#applicantsemployment-employment_period').html(periods).blur();
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
            /* load desired employers dynamically */
                $('#search_employer_name-btn').click(
                    function () {
                        dynamicEmployers();
                    }
                );
                
                $('#search_employer_name').keyup(
                    function () {
                        $(this).val().length > 9 ? $('#search_employer_name-btn').click() : '';
                    }
                );
            /* load desired employers dynamically */

            /* adopt selected employer */
                $('#employer_name').change(
                    function () {
                        $('#applicantsemployment-employer_name').html($(this).find('[value=' + $(this).val() + ']').clone()).blur();
                    }
                );
            /* adopt selected employer */

            /* employment terms affect employment preiods */
                $('#applicantsemployment-employment_terms').change(
                    function () {
                        employmentPeriods();
                    }
                );
            /* employment terms affect employment preiods */
            
            /* is saved */
               '$saved' ? dataSaved('Success', 'Employment Details Saved', 'success') : '';
            /* is saved */
        "
        , \yii\web\VIEW::POS_READY
)
?>

<?php if (Yii::$app->request->isAjax): ?>

    <?php
    $this->registerJs(
            "
                function saveEmployment() {
                    form = $('#form-stdt-eplmt');
                    
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
                
                $('[name=employment-button]').click(
                    function() {
                        saveEmployment();
                    }
                );
            "
            , \yii\web\VIEW::POS_READY)
    ?>

<?php endif; ?>