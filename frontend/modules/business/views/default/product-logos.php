<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $product \frontend\modules\business\models\Products */

use kartik\form\ActiveForm;
?>

<?php $form = ActiveForm::begin(['id' => 'form-prdct-lg', 'enableAjaxValidation' => false, 'action' => 'product-logos', 'options' => ['enctype' => 'multipart/form-data']]); ?>

<div class="gnrl-frm-divider">Product's Logos</div>

<table>
    <tr>
        <td class="td-center td-pdg-bth">
            <label class="btn btn-xs btn-<?= $product->logo('logo_owner') ? 'success' : 'primary' ?> blk-fl-btn full-width" for="products-logo_owner"><?= $product->getAttributeLabel('logo_owner') ?></label>
            <?= $form->field($product, 'logo_owner')->fileInput(['placeholder' => 'owner\'s logo', 'logo' => 'logo', 'field' => 'logo_owner', 'style' => 'text-align: center; font-weight: bold; display: none'])->label(false) ?>
        </td>

        <td class="td-center td-pdg-bth">
            <label class="btn btn-xs btn-<?= $product->logo('logo_partner') ? 'success' : 'primary' ?> blk-fl-btn full-width" for="products-logo_partner"><?= $product->getAttributeLabel('logo_partner') ?></label>
            <?= $form->field($product, 'logo_partner')->fileInput(['placeholder' => 'partner\'s logo', 'logo' => 'logo', 'field' => 'logo_partner', 'style' => 'text-align: center; font-weight: bold; display: none'])->label(false) ?>
        </td>

        <td class="td-center td-pdg-bth">
            <label class="btn btn-xs btn-<?= $product->logo('watermark') ? 'success' : 'primary' ?> blk-fl-btn full-width" for="products-watermark"><?= $product->getAttributeLabel('watermark') ?></label>
            <?= $form->field($product, 'watermark')->fileInput(['placeholder' => 'watermark', 'logo' => 'logo', 'field' => 'watermark', 'style' => 'text-align: center; font-weight: bold; display: none'])->label(false) ?>
        </td>

        <td class="td-center td-pdg-bth">
            <label class="btn btn-xs btn-<?= $product->logo('logo_header') ? 'success' : 'primary' ?> blk-fl-btn full-width" for="products-logo_header"><?= $product->getAttributeLabel('logo_header') ?></label>
            <?= $form->field($product, 'logo_header')->fileInput(['placeholder' => 'header section logo', 'logo' => 'logo', 'field' => 'logo_header', 'style' => 'text-align: center; font-weight: bold; display: none'])->label(false) ?>
        </td>
    </tr>
</table>

<?php ActiveForm::end(); ?>