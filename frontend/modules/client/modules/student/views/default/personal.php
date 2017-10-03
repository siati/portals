<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */
/* @var $user \frontend\models\User */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use common\models\Counties;
use common\models\SubCounties;
use common\models\Constituencies;
use common\models\Wards;
use common\models\PostalCodes;
use common\models\LmBanks;
use common\models\LmBankBranch;
use common\models\StaticMethods;
use common\models\LmBaseEnums;
use frontend\modules\client\modules\student\models\Applicants;

$this->title = 'Personal Details';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="gnrl-frm stdt-psnl">

    <div class="gnrl-frm-cont">

        <?php $form = ActiveForm::begin(['id' => 'form-stdt-psnl', 'enableAjaxValidation' => true]); ?>

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy fit-in-pn">

            <?= Html::activeHiddenInput($applicant, 'id') ?>

            <?= Html::activeHiddenInput($user, 'id') ?>

            <div class="gnrl-frm-divider">Personal Details</div>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'fname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'mname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'lname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
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
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'gender', ['addon' => ['prepend' => ['content' => '<i class="fa fa-intersex"></i>']]])->dropDownList(LmBaseEnums::genders(), ['prompt' => '-- Gender --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($user, 'birth_cert_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-child"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($user, 'id_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($user, 'kra_pin', ['addon' => ['prepend' => ['content' => '<i class="fa fa-certificate"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($user, 'phone', ['addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-phone"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'postal_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-envelope-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'postal_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-building-o"></i>']]])->dropDownList(StaticMethods::modelsToArray(PostalCodes::allCodes(), 'id', 'town', false), ['prompt' => '-- Select Town --']) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'married', ['addon' => ['prepend' => ['content' => '<i class="fa fa-heart"></i>']]])->dropDownList(Applicants::marrieds()) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'disability', ['addon' => ['prepend' => ['content' => '<i class="fa fa-wheelchair"></i>']]])->dropDownList(Applicants::disabilities()) ?></td>
                    <td class="td-pdg-lft" style="width: 50%"><?= $form->field($applicant, 'other_disability', ['addon' => ['prepend' => ['content' => '<i class="fa fa-align-justify"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'county', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(Counties::allCounties(), 'id', 'name', false), ['prompt' => '-- Select County --', 'onchange' => "countyChanged($(this).val(), $('#applicants-sub_county').val(), $('#applicants-sub_county'), '../../../site/dynamic-subcounties', $('#applicants-constituency').val(), $('#applicants-constituency'), '../../../site/dynamic-constituencies')"]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'sub_county', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(SubCounties::subcountiesForCounty($applicant->county), 'id', 'name', false), ['prompt' => '-- Select Subcounty --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'constituency', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(Constituencies::constituenciesForCounty($applicant->county), 'id', 'name', false), ['prompt' => '-- Select Constituency --', 'onchange' => "dynamicWards($(this).val(), $('#applicants-ward').val(), $('#applicants-ward'), '../../../site/dynamic-wards')"]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'ward', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(Wards::wardsForConstituency($applicant->constituency), 'id', 'name', false), ['prompt' => '-- Select Ward --']) ?></td>
                </tr>
            </table>

            <table><tr><td>&nbsp;</td></tr></table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'location', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'sub_location', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'village', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

            <div class="gnrl-frm-divider">Bank Details <small>This section is optional</small></div>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'bank', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->dropDownList(StaticMethods::modelsToArray(LmBanks::searchBanks(null, LmBanks::online, 'all'), 'BANKCODE', 'NAME', true), ['prompt' => '-- Bank Name --', 'onchange' => 'bankBranches()']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'bank_branch', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->dropDownList(StaticMethods::modelsToArray(LmBankBranch::searchBranches($applicant->bank, null, 'all'), 'BRANCHCODE', 'BRANCHNAME', true), ['prompt' => '-- Bank Branch --']) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'account_number', ['addon' => ['prepend' => ['content' => '<i class="fa fa-vcard"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($applicant, 'smart_card_number', ['addon' => ['prepend' => ['content' => '<i class="fa fa-vcard-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
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
