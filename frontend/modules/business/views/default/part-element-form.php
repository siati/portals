<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $model \frontend\modules\business\models\ApplicationPartElements */
/* @var $order boolean */
/* @var $part string */
/* @var $count integer */

use yii\helpers\Html;
use kartik\form\ActiveForm;
?>

<div style="width: 100%; height: 92.5%; border-bottom: 1px solid #ddd;; overflow-x: hidden">

    <?php $form = ActiveForm::begin(['id' => $form_id = "form-prt-elmnt-$part$model->element", 'enableAjaxValidation' => true, 'action' => 'save-application-part', 'fieldConfig' => ['options' => ['class' => 'form-group-sm']]]); ?>

    <?= Html::activeHiddenInput($model, "[$part$model->element]id") ?>
    <?= Html::activeHiddenInput($model, "[$part$model->element]part") ?>
    <?= Html::activeHiddenInput($model, "[$part$model->element]element") ?>
    <?= Html::activeHiddenInput($model, "[$part$model->element]title") ?>

    <table>
        <tr>
            <td class="td-pdg-bth"><?= $form->field($model, "[$part$model->element]active")->dropDownList(frontend\modules\business\models\ApplicationPartElements::actives()) ?></td>
            <?php if ($order): ?>
                <td class="td-pdg-bth"><?= $form->field($model, "[$part$model->element]order")->dropDownList(\common\models\StaticMethods::ranges(1, $count, 1, false)) ?></td>
            <?php endif; ?>
        </tr>

        <tr><td class="td-pdg-bth"<?php if ($order): ?> colspan="2"><?php endif; ?><?= $form->field($model, "[$part$model->element]narration")->textArea(['rows' => 23, 'style' => 'text-align: justify; resize: none']) ?></td></tr>
    </table>

    <?php ActiveForm::end(); ?>
</div>

<div style="width: 100%; height: 7.5%; overflow: hidden; padding: 5px 10px 0 10px">
    <div class="btn btn-sm btn-primary pull-left aplctn-prt-sv" prt-elmnt="<?= "$part$model->element" ?>">Save</div>
    <div class="btn btn-sm btn-danger pull-right" onclick="closeDialog()"><b>Close</b></div>
</div>