<?php
/* @var $this yii\web\View */
/* @var $education_backgrounds \frontend\modules\client\modules\student\models\EducationBackground */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use frontend\modules\client\modules\student\models\EducationBackground;
use frontend\modules\business\models\ApplicationParts;
?>

<div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
    <legend class="part-legend"><?= $part->title ?></legend>

    <?php if (!empty($part->intro)): ?>
        <pre class="part-element-narration"><?= $part->intro ?></pre>
    <?php endif; ?>

    <?php foreach ($education_backgrounds as $education_background): ?>

        <div class="part-container">

            <legend class="part-legend-2"><?= EducationBackground::studyLevels()[$education_background->study_level] ?></legend>

            <table class="part-table">
                <tbody>
                    <tr>
                        <td class="part-table-label" colspan="2">Institution Name</td>
                        <td class="part-table-label">Institution Type</td>
                        <td class="part-table-label">Annual Fees</td>
                    </tr>

                    <tr>
                        <td class="part-table-data" colspan="2"><?= $education_background->institution_name ?></td>
                        <td class="part-table-data"><?= EducationBackground::institutionTypes()[$education_background->institution_type] ?></td>
                        <td class="part-table-data">KShs. <?= number_format($education_background->annual_fees, 0) ?></td>
                    </tr>
                    
                    <tr>
                        <td class="part-table-label">Sponsored</td>
                        <td class="part-table-label">Admission Year</td>
                        <td class="part-table-label">Exam Year</td>
                        <td class="part-table-label">Grade</td>
                    </tr>

                    <tr>
                        <td class="part-table-data"><?= EducationBackground::sponsorshipReasons()[$education_background->sponsorship_reason] ?></td>
                        <td class="part-table-data"><?= $education_background->since ?></td>
                        <td class="part-table-data"><?= $education_background->till ?></td>
                        <td class="part-table-data"><?= "$education_background->score ($education_background->grade)" ?></td>
                    </tr>

                </tbody>
            </table>

        </div>

    <?php endforeach; ?>
</div>