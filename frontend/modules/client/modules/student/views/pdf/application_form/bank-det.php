<?php
/* @var $this yii\web\View */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use common\models\LmBanks;
use common\models\LmBankBranch;
use frontend\modules\business\models\ApplicationParts;
use frontend\modules\business\models\ApplicationPartElements;
?>

<?php $bank = LmBanks::byBankCode($applicant->bank) ?>

<?php $bank_branch = LmBankBranch::byBankAndBranchCode($applicant->bank, $applicant->bank_branch) ?>

<div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
    <legend class="part-legend"><?= $part->title ?></legend>

    <?php if (!empty($part->intro)): ?>
        <div class="part-element-narration"><?= $part->intro ?></div>
    <?php endif; ?>

    <table class="part-table">
        <tbody>
            <tr>
                <td class="part-table-label">Bank Name</td>
                <td class="part-table-label">Branch Name</td>
                <td class="part-table-label">Account No.</td>
                <td class="part-table-label">Smart Card No.</td>
            </tr>

            <tr>
                <td class="part-table-data"><?= $bank->NAME ?></td>
                <td class="part-table-data"><?= $bank_branch->BRANCHNAME ?></td>
                <td class="part-table-data"><?= $applicant->account_number ?></td>
                <td class="part-table-data"><?= $applicant->smart_card_number ?></td>
            </tr>
        </tbody>
    </table>

    <?php foreach (ApplicationPartElements::forPart($part->id, ApplicationPartElements::active_yes) as $element): ?>

        <br/>

        <div class="part-container">
            <legend class="part-legend-2"><?= $element->title ?></legend>

            <?php if (!empty($element->narration)): ?>
                <pre class="part-element-narration"><?= $element->narration ?></pre>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

</div>