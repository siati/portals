<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\modules\client\modules\student\models\ApplicantsSpouse */
/* @var $saved boolean */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\models\LmBaseEnums;
use common\models\LmEmployers;

$this->title = 'Spouse Details';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $initial_employer = LmEmployers::searchEmployers($model->employer_name, null, LmEmployers::one) ?>

<div class="gnrl-frm stdt-sps">

    <div class="gnrl-frm-cont">

        <?php $form = ActiveForm::begin(['id' => 'form-stdt-sps', 'enableAjaxValidation' => true]); ?>

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy fit-in-pn">

            <?= Html::activeHiddenInput($model, 'id') ?>

            <?= Html::activeHiddenInput($model, 'applicant') ?>

            <div class="gnrl-frm-divider">Spouse Details</div>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'fname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'mname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'lname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'id_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'phone', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'email', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'employed', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->dropDownList(LmBaseEnums::yesNo()) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'staff_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'kra_pin', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'employer_name', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'employer_phone', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'employer_email', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

        </div>

        <div class="gnrl-frm-ftr">

            <?php if (Yii::$app->request->isAjax): ?>

                <?= Html::button('Update', ['class' => 'btn btn-primary pull-left', 'name' => 'spouse-button']) ?>

                <div class="btn btn-danger pull-right" onclick="closeDialog()"><b>Close</b></div>

            <?php else: ?>

                <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right', 'name' => 'spouse-button']) ?>

            <?php endif; ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>


<?php $this->registerJs("'$saved' ? dataSaved('Success', 'Spouse Details Saved', 'success') : '';", yii\web\View::POS_READY) ?>

<?php if (Yii::$app->request->isAjax): ?>

    <?php
    $this->registerJs(
            "
                function saveSpouse() {
                    form = $('#form-stdt-sps');
                    
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
                
                $('[name=spouse-button]').click(
                    function() {
                        saveSpouse();
                    }
                );
            "
            , \yii\web\VIEW::POS_READY)
    ?>

<?php endif; ?>