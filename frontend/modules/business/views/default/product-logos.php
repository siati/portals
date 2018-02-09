<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $product \frontend\modules\business\models\Products */

use yii\helpers\Html;
use kartik\form\ActiveForm;
?>

<?php $form = ActiveForm::begin(['id' => 'form-prdct-lg', 'enableAjaxValidation' => false, 'action' => 'product-logos', 'options' => ['enctype' => 'multipart/form-data']]); ?>

<div class="gnrl-frm-divider">Product's Logos</div>

<table>
    <tr>
        <td class="td-center">
            <label class="btn btn-xs btn-primary blk-fl-btn full-width" for="products-logo_owner"><?= ($label = $product->getAttributeLabel('logo_owner')) . ($product->logo('logo_owner') ? ": $product->logo_owner" : '') ?></label>
            <?= $form->field($product, 'logo_owner')->fileInput(['placeholder' => 'owner\'s logo', 'logo' => 'logo', 'field' => 'logo_owner', 'label' => $label, 'style' => 'text-align: center; font-weight: bold; display: none'])->label(false) ?>
        </td>

        <td class="td-center">
            <label class="btn btn-xs btn-primary blk-fl-btn full-width" for="products-logo_partner"><?= ($label = $product->getAttributeLabel('logo_partner')) . ($product->logo('logo_partner') ? ": $product->logo_partner" : '') ?></label>
            <?= $form->field($product, 'logo_partner')->fileInput(['placeholder' => 'partner\'s logo', 'logo' => 'logo', 'field' => 'logo_partner', 'label' => $label, 'style' => 'text-align: center; font-weight: bold; display: none'])->label(false) ?>
        </td>

        <td class="td-center">
            <label class="btn btn-xs btn-primary blk-fl-btn full-width" for="products-watermark"><?= ($label = $product->getAttributeLabel('watermark')) . ($product->logo('watermark') ? ": $product->watermark" : '') ?></label>
            <?= $form->field($product, 'watermark')->fileInput(['placeholder' => 'watermark', 'logo' => 'logo', 'field' => 'watermark', 'label' => $label, 'style' => 'text-align: center; font-weight: bold; display: none'])->label(false) ?>
        </td>

        <td class="td-center">
            <label class="btn btn-xs btn-primary blk-fl-btn full-width" for="products-logo_header"><?= ($label = $product->getAttributeLabel('logo_header')) . ($product->logo('logo_header') ? ": $product->logo_header" : '') ?></label>
            <?= $form->field($product, 'logo_header')->fileInput(['placeholder' => 'header section logo', 'logo' => 'logo', 'field' => 'logo_header', 'label' => $label, 'style' => 'text-align: center; font-weight: bold; display: none'])->label(false) ?>
        </td>
    </tr>
</table>

<?php ActiveForm::end(); ?>