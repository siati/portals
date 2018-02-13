<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\modules\client\modules\student\models\ApplicantsResidence */
/* @var $saved boolean */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\models\StaticMethods;
use common\models\Counties;
use common\models\SubCounties;
use common\models\Constituencies;
use common\models\Wards;

$this->title = 'Current Residence Details';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $pre = Yii::$app->request->isAjax ? 'site' : '../../../site' ?>

<div class="gnrl-frm stdt-rsdc">

    <div class="gnrl-frm-cont">

        <?php $form = ActiveForm::begin(['id' => 'form-stdt-rsdc', 'enableAjaxValidation' => true]); ?>

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy fit-in-pn">

            <?= Html::activeHiddenInput($model, 'id') ?>

            <?= Html::activeHiddenInput($model, 'applicant') ?>

            <div class="gnrl-frm-divider">Current Residence Details</div>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'county', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(Counties::allCounties(), 'id', 'name', false), ['prompt' => '-- Select County --', 'onchange' => "countyChanged($(this).val(), $('#applicantsresidence-sub_county').val(), $('#applicantsresidence-sub_county'), '$pre/dynamic-subcounties', $('#applicantsresidence-constituency').val(), $('#applicantsresidence-constituency'), '$pre/dynamic-constituencies')"]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'sub_county', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(SubCounties::subcountiesForCounty($model->county), 'id', 'name', false), ['prompt' => '-- Select Subcounty --']) ?></td>
                </tr>
            </table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'constituency', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(Constituencies::constituenciesForCounty($model->county), 'id', 'name', false), ['prompt' => '-- Select Constituency --', 'onchange' => "dynamicWards($(this).val(), $('#applicantsresidence-ward').val(), $('#applicantsresidence-ward'), '$pre/dynamic-wards')"]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'ward', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-marker"></i>']]])->dropDownList(StaticMethods::modelsToArray(Wards::wardsForConstituency($model->constituency), 'id', 'name', false), ['prompt' => '-- Select Ward --']) ?></td>
                </tr>
            </table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'location', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'sub_location', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'village', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'apartment', ['addon' => ['prepend' => ['content' => '<i class="fa fa-home"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

            <table>
                <tr>
                    <td class="td-pdg-lft"><?= $form->field($model, 'town', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
                    <td class="td-pdg-lft"><?= $form->field($model, 'nearest_primary', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->textInput(['maxlength' => true]) ?></td>
                </tr>
            </table>

        </div>

        <div class="gnrl-frm-ftr">

            <?php if (Yii::$app->request->isAjax): ?>

                <?= Html::button('Update', ['class' => 'btn btn-primary pull-left', 'name' => 'residence-button']) ?>

                <div class="btn btn-danger pull-right" onclick="closeDialog()"><b>Close</b></div>

            <?php else: ?>

                <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right', 'name' => 'residence-button']) ?>

            <?php endif; ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>


<?php $this->registerJs("'$saved' ? dataSaved('Success', 'Residence Details Saved', 'success') : '';", yii\web\View::POS_READY) ?>

<?php if (Yii::$app->request->isAjax): ?>

    <?php
    $this->registerJs(
            "
                function saveResidenceDetails() {
                    form = $('#form-stdt-rsdc');
                    
                    post = form.serializeArray();
                    
                    post.push({'name': 'sbmt', 'value': true});

                    $.post(form.attr('action'), post,
                        function (frm) {
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
                
                $('[name=residence-button]').click(
                    function() {
                        saveResidenceDetails();
                    }
                );
            "
            , \yii\web\VIEW::POS_READY)
    ?>

<?php endif; ?>