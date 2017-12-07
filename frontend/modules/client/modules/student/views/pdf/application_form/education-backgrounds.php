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

    <table class="part-table">
        <tbody>
            <tr>
                <td class="part-table-label"><small>Institution Name</small></td>
                <td class="part-table-label"><small>Study Level</small></td>
                <td class="part-table-label"><small>Inst. Type</small></td>
                <td class="part-table-label"><small>Adm. Yr</small></td>
                <td class="part-table-label"><small>Exam Yr</small></td>
                <td class="part-table-label"><small>Grade</small></td>
                <td class="part-table-label"><small>Sponsored</small></td>
            </tr>

            <?php foreach ($education_backgrounds as $education_background): ?>
                <tr>
                    <td class="part-table-data"><small><?= $education_background->institution_name ?></small></td>
                    <td class="part-table-data"><small><?= EducationBackground::studyLevels()[$education_background->study_level] ?></small></td>
                    <td class="part-table-data"><small><?= EducationBackground::institutionTypes()[$education_background->institution_type] ?></small></td>
                    <td class="part-table-data"><small><?= $education_background->since ?></small></td>
                    <td class="part-table-data"><small><?= $education_background->till ?></small></td>
                    <td class="part-table-data"><small><?= "$education_background->score ($education_background->grade)" ?></small></td>
                    <td class="part-table-data"><small><?= EducationBackground::sponsorshipReasons()[$education_background->sponsorship_reason] ?></small></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>