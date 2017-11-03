<?php
/* @var $this yii\web\View */
/* @var $institution \frontend\modules\client\modules\student\models\ApplicantsInstitution */

use common\models\LmBaseEnums;
?>

<div class="part-container">
    <legend class="part-legend">Loan Details</legend>

    <table class="part-table">
        <tbody>
            <tr>
                <td class="part-table-label">Annual Fees</td>
                <td class="part-table-label">Annual Upkeep</td>
                <td class="part-table-label">Can Raise</td>
                <td class="part-table-label">Amount Applied</td>
                <td class="part-table-label">Need Bursary</td>
            </tr>
            
            <tr>
                <td class="part-table-data"><?= number_format($institution->annual_fees) ?></td>
                <td class="part-table-data"><?= number_format($institution->annual_upkeep) ?></td>
                <td class="part-table-data"><?= number_format($institution->amount_can_raise) ?></td>
                <td class="part-table-data"><?= number_format($institution->amount_applied) ?></td>
                <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::yes_no, $institution->need_bursary)->LABEL ?></td>
            </tr>
        </tbody>
    </table>
</div>