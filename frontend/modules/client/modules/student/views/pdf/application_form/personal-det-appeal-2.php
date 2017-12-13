<?php
/* @var $this yii\web\View */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */
/* @var $personal_appeal_element \frontend\modules\business\models\ApplicationPartElements */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use frontend\modules\client\modules\student\models\Applicants;
?>

<div class="part-container">
    <legend class="part-legend-2"><?= $personal_appeal_element->title ?></legend>

    <?php if (!empty($personal_appeal_element->narration)): ?>
        <pre class="part-element-narration"><?= $personal_appeal_element->narration ?></pre>
    <?php endif; ?>

    <table class="part-table">
        <tbody>
            <tr>
                <td class="part-table-label">Disability</td>
                <td class="part-table-label">Narration</td>
            </tr>

            <tr>
                <td class="part-table-data"><?= Applicants::disabilities()[$applicant->disability] ?></td>
                <td class="part-table-data"><?= $applicant->other_disability ?></td>
            </tr>
        </tbody>
    </table>

    <table class="part-table">
        <tbody>
            <tr>
                <td class="part-table-label">Parents' Marital Status</td>

                <?php if ($applicant->parents == Applicants::parents_mother_alive): ?>
                    <td class="part-table-label">Father's Death Cert. No.</td>
                <?php endif; ?>

                <?php if ($applicant->parents == Applicants::parents_father_alive): ?>
                    <td class="part-table-label">Mother's Death Cert. No.</td>
                <?php endif; ?>
            </tr>

            <tr>
                <td class="part-table-data"><?= Applicants::parentalStatuses()[$applicant->parents] ?></td>

                <?php if ($applicant->parents == Applicants::parents_mother_alive): ?>
                    <td class="part-table-data"><?= $applicant->father_death_cert_no ?></td>
                <?php endif; ?>

                <?php if ($applicant->parents == Applicants::parents_father_alive): ?>
                    <td class="part-table-data"><?= $applicant->mother_death_cert_no ?></td>
                <?php endif; ?>
            </tr>
        </tbody>
    </table>
</div>