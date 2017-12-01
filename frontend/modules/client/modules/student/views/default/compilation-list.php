<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $compilations array */

use frontend\modules\client\modules\student\models\Applicants;
?>

<table>
    <?php foreach ($compilations as $item => $compilation): ?>
        <tr>
            <td><?= $compilation[Applicants::narration] ?></td>
        </tr>
    <?php endforeach; ?>
</table>