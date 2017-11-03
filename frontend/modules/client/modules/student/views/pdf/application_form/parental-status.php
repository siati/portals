<?php
/* @var $this yii\web\View */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */

use frontend\modules\client\modules\student\models\Applicants;
?>

<div class="part-container">
    <legend class="part-legend-2">Marital Status</legend>

    <table class="part-table">
        <tbody>
            <tr>
                <td class="part-table-label">Marital Status</td>

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