<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $settings \frontend\modules\business\models\ProductOpeningSettings */
?>

<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
?>

<?php if (!empty($settings)): ?>

    <?php $form = ActiveForm::begin(['id' => 'form-prdct-stng', 'enableAjaxValidation' => true, 'action' => 'save-settings']); ?>

    <div class="gnrl-frm-divider">Application Default Settings</div>

    <?php $i = 0 ?>

    <table>
        <?php foreach ($settings as $setting): ?>

        <?php if (++$i % ($cols = 5) == 1): ?><tr><?php endif; ?>

                <td class="td-pdg-lft"><?= $form->field($setting, "[$setting->setting]value", ['addon' => ['prepend' => ['content' => '<i class="fa fa-question"></i>']]])->dropDownList($setting->values)->label($setting->name) ?></td>

            <?php if ($i % $cols == 0): ?></tr><?php endif; ?>

        <?php endforeach; ?>

    <?php if ($i % $cols == 0): ?><tr><?php endif; ?><td class="td-pdg-lft" colspan="<?= $cols - $i % $cols ?>"><?= Html::button('Save', ['id' => 'stngs-sv', 'class' => 'btn btn-sm btn-primary pull-right', 'name' => 'settings-save-button']) ?></td><?php if ($i % $cols == 0): ?></tr><?php endif; ?>
    </table>

    <?php ActiveForm::end(); ?>

<?php endif; ?>