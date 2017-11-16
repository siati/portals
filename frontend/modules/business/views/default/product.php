<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $product \frontend\modules\business\models\Products */

use yii\helpers\Html;
use kartik\form\ActiveForm;
?>

<?php $form = ActiveForm::begin(['id' => 'form-prdct-nm', 'enableAjaxValidation' => true, 'action' => 'products', 'validationUrl' => 'save-product']); ?>

<?= Html::activeHiddenInput($product, 'id') ?>

<div class="gnrl-frm-divider"><?= $product->isNewRecord ? 'New Product' : "$product->name Settings" ?></div>

<table>
    <tr>
        <td class="td-pdg-lft" colspan="3"><?= $form->field($product, 'name', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft" rowspan="2"><?= Html::button('Save', ['id' => 'prdct-sv', 'class' => 'btn btn-sm btn-primary pull-right', 'name' => 'product-save-button']) ?></td>
    </tr>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($product, 'code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($product, 'helb_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($product, 'active', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->dropDownList(\frontend\modules\business\models\Products::actives()) ?></td>
    </tr>
    <tr>
        <td class="td-pdg-lft" colspan="4"><?= $form->field($product, 'description', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textarea(['maxlength' => true, 'rows' => 1, 'style' => 'resize: none']) ?></td>
    </tr>
</table>

<?= Html::submitButton('Save', ['id' => 'prdct-sv-btn', 'class' => 'btn btn-sm btn-success pull-right', 'name' => 'product-button', 'style' => 'display: none']) ?>

<?php ActiveForm::end(); ?>