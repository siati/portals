<?php
/* @var $this yii\web\View */
/* @var $education_backgrounds \frontend\modules\client\modules\student\models\EducationBackground */

use frontend\modules\client\modules\student\models\EducationBackground;
?>

<div class="part-container">
    <legend class="part-legend">Education Background</legend>

    <table class="part-table">
        <tbody>
            <tr>
                <td class="part-table-label"><small>Institution Name</small></td>
                <td class="part-table-label"><small>Study Level</small></td>
                <td class="part-table-label"><small>Inst. Type</small></td>
                <td class="part-table-label"><small>Adm. Year</small></td>
                <td class="part-table-label"><small>Exam Year</small></td>
                <td class="part-table-label"><small>Score, Grade</small></td>
            </tr>

            <?php foreach ($education_backgrounds as $education_background): ?>
                <tr>
                    <td class="part-table-data"><small><?= $education_background->institution_name ?></small></td>
                    <td class="part-table-data"><small><?= EducationBackground::studyLevels()[$education_background->study_level] ?></small></td>
                    <td class="part-table-data"><small><?= EducationBackground::institutionTypes()[$education_background->institution_type] ?></small></td>
                    <td class="part-table-data"><small><?= $education_background->since ?></small></td>
                    <td class="part-table-data"><small><?= $education_background->till ?></small></td>
                    <td class="part-table-data"><small><?= "$education_background->score ($education_background->grade)" ?></small></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>