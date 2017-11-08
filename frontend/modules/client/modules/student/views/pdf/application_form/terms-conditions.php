<?php
/* @var $this yii\web\View */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use frontend\modules\business\models\ApplicationParts;
use frontend\modules\business\models\ApplicationPartElements;
?>

<div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
    <legend class="part-legend"><?= $part->title ?></legend>

    <?php if (!empty($part->intro)): ?>
        <div class="part-element-narration"><?= $part->intro ?></div>
    <?php endif; ?>

    <br/>

    <?php $i = 0 ?>

    <table class="part-table">

        <?php foreach (ApplicationPartElements::forPart($part->id, ApplicationPartElements::active_yes) as $element): ?>

            <?php if (!empty($element->narration) && $element->active == ApplicationPartElements::active_yes): ?>

                <tr><td class="text-top"><?= ++$i ?>. </td><td class="text-top"><?= $element->narration ?></td></tr>

            <?php endif; ?>

        <?php endforeach; ?>

    </table>

    <br/>

    <div class="part-container">
        <legend class="part-legend-2">I agree to abide by these Terms and Conditions</legend>

        <div class="part-element-narration">
            <br/>

            <pre class="part-element-narration">Applicant`s Signature: ................................            ID No: .......................                Date: <?= date('d/m/Y') ?></pre>

            <br/>

            <span class="part-element-narration-sm"><b>PLEASE NOTE THAT IF YOU DO NOT SIGN THIS AGREEMENT FORM, YOUR APPLICATION SHALL NOT BE PROCESSED</b></span>
        </div>
    </div>

    <br/>

    <div class="part-container">
        <legend class="part-legend-2">Official Verification</legend>

        <pre class="part-element-narration">Authorized Signature (HELB): ..............................................................                        Date: <?= date('d/m/Y') ?></pre>
    </div>

</div>