<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $models \frontend\modules\client\modules\student\models\EducationBackground */
/* @var $disabled boolean */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use frontend\modules\client\modules\student\models\EducationBackground;
?>

<?php foreach ($models as $model): ?>

    <?php if (!$model->isNewRecord): ?>

        <?php $form = ActiveForm::begin(['id' => "form-edctn-prtl-apl-$model->study_level", 'enableAjaxValidation' => true, 'action' => 'education-partial', 'fieldConfig' => ['options' => ['class' => 'form-group-sm']]]); ?>

        <?= Html::activeHiddenInput($model, 'id') ?>

        <div class="gnrl-frm-divider"><?= EducationBackground::studyLevels()[$model->study_level] ?> Details</div>

        <table>
            <tr>
                <td class="td-pdg-lft"><?= $form->field($model, 'school_type', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->dropDownList(EducationBackground::schoolTypes(), ['disabled' => $disabled]) ?></td>
                <td class="td-pdg-lft"><?= $form->field($model, 'annual_fees', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true, 'disabled' => $disabled]) ?></td>
            </tr>

            <tr>
                <td class="td-pdg-lft"><?= $form->field($model, 'sponsored', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->dropDownList(EducationBackground::sponsoreds(), ['disabled' => $disabled]) ?></td>
                <td class="td-pdg-lft"><?= $form->field($model, 'sponsorship_reason', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->dropDownList(EducationBackground::sponsorshipReasons(), ['disabled' => $disabled]) ?></td>
            </tr>
        </table>

        <?php ActiveForm::end(); ?>

    <?php endif; ?>

<?php endforeach; ?>