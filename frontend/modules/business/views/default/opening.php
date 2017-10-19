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

<div class="gnrl-frm-divider">Application Opening and Closing Dates</div>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($opening, 'academic_year', ['addon' => ['prepend' => ['content' => '<i class="fa fa-sort"></i>']]])->dropDownList(ProductOpening::academicYears()) ?></td>
        <td class="td-pdg-lft"><?= $form->field($opening, 'subsequent', ['addon' => ['prepend' => ['content' => '<i class="fa fa-sort"></i>']]])->dropDownList(common\models\LmBaseEnums::applicantTypes()) ?></td>
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
        <td class="td-pdg-lft"><?= Html::button('Save', ['id' => 'opng-sv', 'class' => 'btn btn-primary pull-right', 'name' => 'opening-save-button']) ?></td>
    </tr>
</table>

<?php ActiveForm::end(); ?>