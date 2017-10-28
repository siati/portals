<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\modules\business\models\ProductAccessProperties */
/* @var $section string */
/* @var $selectedSection string */
/* @var $i string */
/* @var $selectedItem string */

use yii\helpers\Html;
use kartik\form\ActiveForm;
?>

<?php $bcg = $section == $selectedSection && $i == $selectedItem ? '#31b0d5' : 'inherit' ?>

<?php $clr = $section == $selectedSection && $i == $selectedItem ? '#fff' : '#555' ?>

<?php $form = ActiveForm::begin(['id' => $form_id = "form-$section-$model->property", 'enableAjaxValidation' => true, 'action' => 'save-access-property', 'fieldConfig' => ['options' => ['class' => 'form-group-sm']]]); ?>

<li class="ld-stng-sctn-itm kasa-pointa" prnt-sctn="<?= $section ?>"<?php if ($section != $selectedSection): ?> hidden="hidden" <?php endif; ?>style="padding: 0 3px">
    <?= Html::activeHiddenInput($model, "[$model->property]table") ?>
    <?= Html::activeHiddenInput($model, "[$model->property]column") ?>
    <?= Html::activeHiddenInput($model, "[$model->property]model_class") ?>
    <?= Html::activeHiddenInput($model, "[$model->property]attribute") ?>
    <?= Html::activeHiddenInput($model, "[$model->property]operation") ?>
    <?= Html::activeHiddenInput($model, "[$model->property]active") ?>

    <?=
    $form->field($model, "[$model->property]name")->textInput(
            [
                'property' => $model->property,
                'form' => $form_id,
                'item' => "$section-$i",
                'maxlength' => true,
                'stng-fld' => 'property',
                'style' => "border: none; background-color: $bcg; color: $clr"
            ]
    )->label(false)
    ?>
</li>

<?php ActiveForm::end(); ?>