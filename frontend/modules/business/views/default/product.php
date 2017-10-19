<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $product \frontend\modules\business\models\Products */

use yii\helpers\Html;
use kartik\form\ActiveForm;
?>

<?php $form = ActiveForm::begin(['id' => 'form-prdct-stng', 'enableAjaxValidation' => true, 'action' => 'products', 'validationUrl' => 'save-product']); ?>

<?= Html::activeHiddenInput($product, 'id') ?>

<div class="gnrl-frm-divider"><?= $product->isNewRecord ? 'New Product' : "$product->name Settings" ?></div>

<table>
    <tr>
        <td class="td-pdg-lft"><?= $form->field($product, 'name', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($product, 'code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($product, 'helb_code', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->textInput(['maxlength' => true]) ?></td>
        <td class="td-pdg-lft"><?= $form->field($product, 'active', ['addon' => ['prepend' => ['content' => '<i class="fa fa-map-pin"></i>']]])->dropDownList(\frontend\modules\business\models\Products::actives()) ?></td>
        <td class="td-pdg-lft"><?= Html::button('Save', ['id' => 'prdct-sv', 'class' => 'btn btn-primary pull-right', 'name' => 'product-save-button']) ?></td>
    </tr>
</table>

<?= Html::submitButton('Save', ['id' => 'prdct-sv-btn', 'class' => 'btn btn-success pull-right', 'name' => 'product-button', 'style' => 'display: none']) ?>

<?php ActiveForm::end(); ?>