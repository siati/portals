<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $models \frontend\modules\client\modules\student\models\ApplicantsFamilyExpenses */

use yii\helpers\Html;
?>

<?php $i = 0 ?>

<?php $columns = 3 ?>

<div class="gnrl-frm-divider">Family Expenses</div>

<table>
    <?php foreach ($models as $expense_type => $model): ?>
        <?php if (++$i % $columns == 1): ?> <tr> <?php endif; ?>

            <?= Html::activeHiddenInput($model, "[$expense_type]id") ?>

            <?= Html::activeHiddenInput($model, "[$expense_type]applicant") ?>

            <td class="td-pdg-lft"><?= $form->field($model, "[$expense_type]amount", ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>

            <?php if (++$i % $columns == 0 || $i == count($models)): ?> </tr> <?php endif; ?>
    <?php endforeach; ?>
</table>