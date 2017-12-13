<?php
/* @var $this yii\web\View */
/* @var $institution \frontend\modules\client\modules\student\models\ApplicantsInstitution */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use common\models\LmBaseEnums;
use frontend\modules\business\models\ProductSettings;
use frontend\modules\business\models\ProductOpeningSettings;
use frontend\modules\business\models\ApplicationParts;
?>

<?php $tuition_or_upkeep = ProductOpeningSettings::tuitionOrUpkeep($part->application) ?>

<?php $bursary = ProductOpeningSettings::hasBursary($part->application) ?>

<?php if ($part->new_page != ApplicationParts::new_page_yes): ?> <br/> <?php endif; ?>

<div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
    <legend class="part-legend"><?= $part->title ?></legend>

    <?php if (!empty($part->intro)): ?>
        <pre class="part-element-narration"><?= $part->intro ?></pre>
    <?php endif; ?>

    <table class="part-table">
        <tbody>
            <tr>
                <?php if ($tuition = !empty($tuition_or_upkeep[ProductSettings::yes])): ?>
                    <td class="part-table-label">Annual Fees</td>
                <?php endif; ?>

                <?php if ($upkeep = !empty($tuition_or_upkeep[ProductSettings::no])): ?>
                    <td class="part-table-label">Annual Upkeep</td>
                <?php endif; ?>

                <td class="part-table-label">Can Raise</td>
                <td class="part-table-label">Amount Applied</td>

                <?php if ($bursary): ?>
                    <td class="part-table-label">Need Bursary</td>
                <?php endif; ?>
            </tr>

            <tr>
                <?php if ($tuition): ?>
                    <td class="part-table-data"><?= number_format($institution->annual_fees) ?></td>
                <?php endif; ?>

                <?php if ($upkeep): ?>
                    <td class="part-table-data"><?= number_format($institution->annual_upkeep) ?></td>
                <?php endif; ?>

                <td class="part-table-data"><?= number_format($institution->amount_can_raise) ?></td>
                <td class="part-table-data"><?= number_format($institution->amount_applied) ?></td>

                <?php if ($bursary): ?>
                    <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::yes_no, $institution->need_bursary)->LABEL ?></td>
                <?php endif; ?>
            </tr>
        </tbody>
    </table>
</div>