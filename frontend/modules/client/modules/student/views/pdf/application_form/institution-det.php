<?php
/* @var $this yii\web\View */
/* @var $institution \frontend\modules\client\modules\student\models\ApplicantsInstitution */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use common\models\LmInstitution;
use common\models\LmInstitutionBranches;
use common\models\LmCourses;
use common\models\LmBaseEnums;
use common\models\StaticMethods;
use frontend\modules\business\models\ApplicationParts;
use frontend\modules\business\models\ApplicationPartElements;
?>

<div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
    <legend class="part-legend"><?= $part->title ?></legend>

    <?php if (!empty($part->intro)): ?>
        <pre class="part-element-narration"><?= $part->intro ?></pre>
    <?php endif; ?>

    <table class="part-table">
        <tbody>
            <tr>
                <td class="part-table-label">Institution Name</td>
                <td class="part-table-label">Branch</td>
                <td class="part-table-label">Institution Type</td>
                <td class="part-table-label">Country</td>
            </tr>
            <tr>
                <td class="part-table-data"><?= LmInstitution::byInstitutionCode($institution->institution_code)->INSTITUTIONNAME ?></td>
                <td class="part-table-data"><?= LmInstitutionBranches::byInstitutionAndBrachCode($institution->institution_code, $institution->institution_branch_code)->INSTITUTIONBRANCHNAME ?></td>
                <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::institution_type, $institution->institution_type)->LABEL ?></td>
                <td class="part-table-data"><?= LmBaseEnums::countries()[$institution->country] ?></td>
            </tr>
        </tbody>
    </table>

    <table class="part-table">
        <tbody>
            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label">Faculty</td>
                <td class="part-table-label">Department</td>
                <td class="part-table-label">Study Level</td>
            </tr>

            <tr>
                <td class="part-table-data"><?= $institution->faculty ?></td>
                <td class="part-table-data"><?= $institution->department ?></td>
                <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::study_level, $institution->level_of_study)->LABEL ?></td>
            </tr>
        </tbody>
    </table>

    <table class="part-table">
        <tbody>
            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label" colspan="2">Course Name</td>
                <td class="part-table-label">Course Category</td>
                <td class="part-table-label">Course Type</td>
            </tr>

            <tr>
                <td class="part-table-data" colspan="2"><?= LmCourses::byInstitutionAndCourseCodes($institution->institution_code, $institution->course_code)->COURSEDESCRIPTION ?></td>
                <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::course_category, $institution->course_category)->LABEL ?></td>
                <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::course_type, $institution->course_type)->LABEL ?></td>
            </tr>
        </tbody>
    </table>

    <table class="part-table">
        <tbody>
            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label">Admission Category</td>
                <td class="part-table-label">Admission No.</td>
                <td class="part-table-label">Admission Year</td>
                <td class="part-table-label">Admission Month</td>
            </tr>

            <tr>
                <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::admission_category, $institution->admission_category)->LABEL ?></td>
                <td class="part-table-data"><?= $institution->registration_no ?></td>
                <td class="part-table-data"><?= $institution->year_of_admission ?></td>
                <td class="part-table-data"><?= StaticMethods::months()[$institution->admission_month] ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label">Course Duration</td>
                <td class="part-table-label">Year of Study</td>
                <td class="part-table-label">Completion Year</td>
                <td class="part-table-label">Completion Month</td>
            </tr>

            <tr>
                <td class="part-table-data"><?= LmBaseEnums::courseDurations(LmBaseEnums::studyLevel(LmBaseEnums::study_level_degree)->VALUE)[$institution->duration] ?></td>
                <td class="part-table-data"><?= LmBaseEnums::studyYears(LmBaseEnums::studyLevel(LmBaseEnums::study_level_degree)->VALUE)[$institution->year_of_study] ?></td>
                <td class="part-table-data"><?= $institution->year_of_completion ?></td>
                <td class="part-table-data"><?= StaticMethods::months()[$institution->completion_month] ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label" colspan="4">Society</td>
            </tr>

            <tr>
                <td class="part-table-data" colspan="4" style="text-align: justify"><?= $institution->narration ?></td>
            </tr>
        </tbody>
    </table>

    <?php foreach (ApplicationPartElements::forPart($part->id, ApplicationPartElements::active_yes) as $element): ?>

        <br/>

        <div class="part-container">
            <legend class="part-legend-2"><?= $element->title ?></legend>

            <?php if (!empty($element->narration)): ?>
                <pre class="part-element-narration"><?= $element->narration ?></pre>
            <?php endif; ?>

        </div>
        
    <?php endforeach; ?>
</div>