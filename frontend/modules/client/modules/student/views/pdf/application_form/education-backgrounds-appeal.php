<?php
/* @var $this yii\web\View */
/* @var $education_backgrounds \frontend\modules\client\modules\student\models\EducationBackground */
/* @var $education_background_element \frontend\modules\business\models\ApplicationPartElements */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use frontend\modules\client\modules\student\models\EducationBackground;
?>

<?php foreach ($education_backgrounds as $education_background): ?>

    <div class="part-container">

        <legend class="part-legend-2"><?= EducationBackground::studyLevels()[$education_background->study_level] ?></legend>

        <?php if (!empty($education_background_element->narration)): ?>
            <pre class="part-element-narration"><?= $education_background_element->narration ?></pre>
        <?php endif; ?>

        <table class="part-table">
            <tbody>

                <tr>
                    <td class="part-table-label">School Type</td>
                    <td class="part-table-label">Annual Fees</td>
                    <td class="part-table-label">Sponsored</td>
                    <td class="part-table-label">Sponsorship Reason</td>
                </tr>

                <tr>
                    <td class="part-table-data"><?= EducationBackground::schoolTypes()[$education_background->school_type] ?></td>
                    <td class="part-table-data">KShs. <?= number_format($education_background->annual_fees, 0) ?></td>
                    <td class="part-table-data"><?= EducationBackground::sponsoreds()[$education_background->sponsored] ?></td>
                    <td class="part-table-data"><?= EducationBackground::sponsorshipReasons()[$education_background->sponsorship_reason] ?></td>
                </tr>

            </tbody>
        </table>

    </div>

<?php endforeach; ?>