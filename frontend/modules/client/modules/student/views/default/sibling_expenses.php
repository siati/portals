<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $sibling_expenses \frontend\modules\client\modules\student\models\ApplicantsSiblingEducationExpenses */
/* @var $model \frontend\modules\client\modules\student\models\ApplicantsSiblingEducationExpenses */

use yii\helpers\Html;
use common\models\LmBaseEnums
?>

<?php $i = 0 ?>

<?php $columns = 3 ?>

<div class="gnrl-frm-divider">Sibling Education Expenses</div>

<div>
    <div class="sbln-exps-lft pull-left">

        <table>
            <?php foreach ($sibling_expenses as $sibling_expense): ?>

                <tr><td><div class="btn btn-sm btn-<?= $sibling_expense->id == $model->id ? 'success' : 'primary' ?> sblg-slct" sblg="<?= $sibling_expense->id ?>"><?= "$sibling_expense->fname $sibling_expense->mname $sibling_expense->lname" ?></div></td></tr>

            <?php endforeach; ?>
            
            <tr><td><div class="btn btn-sm btn-<?= $model->isNewRecord ? 'success' : 'primary' ?> sblg-slct" sblg="">New Sibling</div></td></tr>
        </table>

    </div>

    <div class="sbln-exps-rgt pull-left">

        <?= Html::activeHiddenInput($model, 'id') ?>

        <?= Html::activeHiddenInput($model, 'applicant') ?>

        <div class="gnrl-frm-divider-sm"><?= $model->isNewRecord ? 'New Sibling' : "$model->fname $model->mname $model->lname" ?></div>

        <table>
            <tr>
                <td class="td-pdg-lft"><?= $form->field($model, 'fname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                <td class="td-pdg-lft"><?= $form->field($model, 'mname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
                <td class="td-pdg-lft"><?= $form->field($model, 'lname', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card-o"></i>']]])->textInput(['maxlength' => true]) ?></td>
            </tr>

            <tr>
                <td class="td-pdg-lft"><?= $form->field($model, 'birth_cert_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-child"></i>']]])->textInput(['maxlength' => true]) ?></td>
                <td class="td-pdg-lft"><?= $form->field($model, 'id_no', ['addon' => ['prepend' => ['content' => '<i class="fa fa-id-card"></i>']]])->textInput(['maxlength' => true]) ?></td>
                <td class="td-pdg-lft"><?= $form->field($model, 'helb_beneficiary', ['addon' => ['prepend' => ['content' => '<i class="fa fa-question"></i>']]])->dropDownList(LmBaseEnums::yesNo()) ?></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="td-pdg-lft"><?= $form->field($model, 'institution_name', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->textInput(['maxlength' => true]) ?></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="td-pdg-lft"><?= $form->field($model, 'study_level', ['addon' => ['prepend' => ['content' => '<i class="fa fa-graduation-cap"></i>']]])->dropDownList(LmBaseEnums::studyLevels()) ?></td>
                <td class="td-pdg-lft"><?= $form->field($model, 'institution_type', ['addon' => ['prepend' => ['content' => '<i class="fa fa-institution"></i>']]])->dropDownList(LmBaseEnums::institutionTypes(LmBaseEnums::byNameAndValue(LmBaseEnums::study_level, $model->study_level)->ELEMENT, true)) ?></td>
                <td class="td-pdg-lft"><?= $form->field($model, 'annual_fees', ['addon' => ['prepend' => ['content' => '<i class="fa fa-money"></i>']]])->textInput(['maxlength' => true]) ?></td>
            </tr>
        </table>
    </div>
</div>