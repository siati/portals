<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $compilations array */

use frontend\modules\client\modules\student\models\Applicants;
?>

<div class="tmln-cmpl-lst-div-hdr">
    <div class="tmln-cmpl-lst-div-hdr-txt">
        <?= Yii::$app->user->identity->username ?>, click on the sections below in order to update your profile accordingly
    </div>
</div>

<?php $i = 0 ?>

<div class="tmln-cmpl-lst-div-bdy">
    <table>
        <?php foreach ($compilations as $item => $compilation): ?>
            <tr>
                <td class="td-pdg-rnd"><b><?= ++$i ?>.</b></td>
                <td class="td-pdg-rnd" onclick="<?= Applicants::correctionAction($item) ?>"><div class="cmpl-itm"><?= $compilation[Applicants::narration] ?></div></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>