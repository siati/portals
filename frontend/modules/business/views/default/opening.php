<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $opening \frontend\modules\business\models\ProductOpening */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use frontend\modules\business\models\ProductOpening;
?>

<?php $form = ActiveForm::begin(['id' => 'form-prdct-opng', 'enableAjaxValidation' => true, 'action' => 'save-opening']); ?>

<div class="gnrl-frm-divider">Application Opening and Closing Dates<div class="btn btn-xs btn-warning pull-right" id="advnc-stngs"><small><i class="fa fa-gears"></i> Advanced Settings</small></div></div>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($opening, 'academic_year', ['addon' => ['prepend' => ['content' => '<i class="fa fa-sort"></i>']]])->dropDownList(ProductOpening::academicYears()) ?></td>
        <td class="td-pdg-lft"><?= $form->field($opening, 'subsequent', ['addon' => ['prepend' => ['content' => '<i class="fa fa-sort"></i>']]])->dropDownList(common\models\LmBaseEnums::applicantTypes()) ?></td>
        <td class="td-pdg-lft"><?= Html::button('Save', ['id' => 'opng-sv', 'class' => 'btn btn-sm btn-primary pull-right', 'name' => 'opening-save-button']) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft">
            <?=
            $form->field($opening, 'since', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar"></i>']]])->widget(DatePicker::className(), [
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

        <td class="td-pdg-lft">
            <?=
            $form->field($opening, 'till', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar"></i>']]])->widget(DatePicker::className(), [
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

        <td class="td-pdg-lft">
            <?=
            $form->field($opening, 'grace', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar"></i>']]])->widget(DatePicker::className(), [
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
        
        <td class="td-pdg-lft"><?= $form->field($opening, 'consider_counts', ['addon' => ['prepend' => ['content' => '<i class="fa fa-question"></i>']]])->dropDownList(ProductOpening::considerCounts()) ?></td>
    </tr>
</table>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($opening, 'min_apps', ['addon' => ['prepend' => ['content' => '<i class="fa fa-sort"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($opening, 'max_apps', ['addon' => ['prepend' => ['content' => '<i class="fa fa-sort"></i>']]])->textInput(['maxlength' => true]) ?></td>
        
        <td class="td-pdg-lft">
            <?=
            $form->field($opening, 'appeal_since', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar"></i>']]])->widget(DatePicker::className(), [
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

        <td class="td-pdg-lft">
            <?=
            $form->field($opening, 'appeal_till', ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar"></i>']]])->widget(DatePicker::className(), [
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

<?php ActiveForm::end(); ?>