<?php
/* @var $this yii\web\View */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use frontend\modules\client\modules\student\models\Applicants;
use frontend\modules\business\models\ApplicationPartElements;
use frontend\modules\business\models\ApplicationPartCheckers;
?>

<?php if (is_object($parental_status_element = ApplicationPartElements::byPartAndElement($part->id, ApplicationPartCheckers::part_parents_marital)) && $parental_status_element->active == ApplicationPartElements::active_yes): ?>

    <div class="part-container">
        <legend class="part-legend-2"><?= $parental_status_element->title ?></legend>

        <?php if (!empty($parental_status_element->narration)): ?>
            <div class="part-element-narration"><?= $parental_status_element->narration ?></div>
        <?php endif; ?>

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

<?php endif; ?>