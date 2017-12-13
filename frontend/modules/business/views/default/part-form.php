<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\modules\business\models\ApplicationParts */
/* @var $count integer */
/* @var $has_items boolean */

use yii\helpers\Html;
use kartik\form\ActiveForm;
?>

<div class="prts-dtl-no-itms-fm-prnt">

    <?php $form = ActiveForm::begin(['id' => $form_id = "form-prt-$model->part", 'enableAjaxValidation' => true, 'action' => 'save-application-part', 'fieldConfig' => ['options' => ['class' => 'form-group-sm']]]); ?>

    <?= Html::activeHiddenInput($model, "[$model->part]id") ?>
    <?= Html::activeHiddenInput($model, "[$model->part]application") ?>
    <?= Html::activeHiddenInput($model, "[$model->part]appeal") ?>
    <?= Html::activeHiddenInput($model, "[$model->part]part") ?>
    <?= Html::activeHiddenInput($model, "[$model->part]title") ?>

    <table>
        <tr><td class="td-pdg-bth"><?= $form->field($model, "[$model->part]order")->dropDownList(frontend\modules\business\models\ApplicationPartCheckers::partOrders($count)) ?></td></tr>

        <tr><td class="td-pdg-bth"><?= $form->field($model, "[$model->part]new_page")->dropDownList(frontend\modules\business\models\ApplicationParts::newPage()) ?></td></tr>

        <tr><td class="td-pdg-bth"><?= $form->field($model, "[$model->part]intro")->textArea(['rows' => 25, 'style' => 'text-align: justify; resize: none']) ?></td></tr>

        <tr><td class="td-pdg-bth"><?= $form->field($model, "[$model->part]order_elements")->dropDownList(frontend\modules\business\models\ApplicationParts::orderElements(), ['chg_prt' => 'yeap', 'prt' => $model->part]) ?></td></tr>
    </table>

    <?php ActiveForm::end(); ?>
</div>

<div class="prts-dtl-no-itms-fm-sv-btn">
    <div class="btn btn-sm btn-primary pull-<?= $has_items ? 'right' : 'left' ?> aplctn-prt-sv" prt="<?= $model->part ?>">Save</div>
    <?php if (!$has_items): ?>
        <div class="btn btn-sm btn-danger pull-right" onclick="closeDialog()"><b>Close</b></div>
    <?php endif; ?>
</div>