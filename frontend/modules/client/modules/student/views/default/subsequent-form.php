<?php
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */
/* @var $institution \frontend\modules\client\modules\student\models\ApplicantsInstitution */
/* @var $upkeep boolean */

use common\models\LmBanks;
use common\models\LmBankBranch;
?>
<div class="full-dim" style="display: table">
    <div class="full-dim" style="display: table-cell; vertical-align: middle; text-align: center">

        <div><b>Hi <?= Yii::$app->user->identity->username ?>,</b></div>

        <p><strong>You may want to update your profile:</strong></p>

        <?php $i = 0 ?>

        <ol>
            <li class="td-pdg-rnd"><b>Update your bank details</b></li>

            <?php if ($upkeep): ?>
                <?php $bank = LmBanks::byBankCode($applicant->bank) ?>
            
                <?php $bank_branch = LmBankBranch::byBankAndBranchCode($applicant->bank, $applicant->bank_branch) ?>
            
            <li class="td-pdg-rnd"><b>Bank Details: Bank - <?= empty($bank->NAME) ? 'Undefined' : $bank->NAME ?>, Branch - <?= empty($bank_branch->BRANCHNAME) ? 'Undefined' : $bank_branch->BRANCHNAME ?>, Account No. - <?= $applicant->account_number ?>, Smart Card No.<?= $applicant->smart_card_number ?></b></li>
            <?php endif; ?>
        </ol>

    </div>
</div>