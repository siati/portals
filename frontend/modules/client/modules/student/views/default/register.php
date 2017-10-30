<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */
/* @var $user \frontend\models\User */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use common\models\LmBaseEnums;

$this->title = 'Student Registration';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="gnrl-frm stdt-rgst">

    <div class="gnrl-frm-cont">

        <?php $form = ActiveForm::begin(['id' => 'form-stdt-rgst', 'enableAjaxValidation' => true]); ?>

        <div class="gnrl-frm-hdr"><?= $this->title ?> Form</div>

        <div class="gnrl-frm-bdy fit-in-pn">

            <?= Html::activeHiddenInput($applicant, 'id') ?>

            <?= Html::activeHiddenInput($user, 'id') ?>

            <table>
                
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'fname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'mname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'lname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>

                <tr>
                    <td class="td-pdg-lft">
                        <?=
                        $form->field($applicant, 'dob', ['addon' => ['prepend' => ['content' => '<i class="fa fa-birthday-cake"></i>']]])->widget(DatePicker::className(), [
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
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'gender', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmBaseEnums::genders(), ['prompt' => '-- Gender --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($user, 'birth_cert_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-child"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
                
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($user, 'id_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($user, 'phone', ['addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-phone"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($user, 'email', ['addon' => ['prepend' => ['content' => '<i class="fa fa-at"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
                
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($user, 'username', ['addon' => ['prepend' => ['content' => '<i class="fa fa-user"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($user, 'password', ['addon' => ['prepend' => ['content' => '<i class="fa fa-key"></i>']]])->passwordInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($user, 'confirm_password', ['addon' => ['prepend' => ['content' => '<i class="fa fa-lock"></i>']]])->passwordInput(['maxlength' => true]) ?></td>
                </tr>
                
            </table>
        </div>

        <div class="gnrl-frm-ftr">

            <?= Html::submitButton('Register', ['class' => 'btn btn-primary pull-right', 'name' => 'register-button']) ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
