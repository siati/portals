<?php
/* @var $this yii\web\View */
/* @var $sibling_educations \frontend\modules\client\modules\student\models\ApplicantsSiblingEducationExpenses */

use common\models\LmBaseEnums;
?>

<div class="part-container">
    <legend class="part-legend">Sibling Education Expenses, KShs.</legend>

    <table class="part-table">
        <tbody>
            <tr>
                <td class="part-table-label"><small>Names</small></td>
                <td class="part-table-label"><small>Nat. ID. No.</small></td>
                <td class="part-table-label"><small>Birth Cert.</small></td>
                <td class="part-table-label"><small>Institution Name</small></td>
                <td class="part-table-label"><small>Study Level</small></td>
                <td class="part-table-label"><small>Annual Fees</small></td>
                <td class="part-table-label"><small>Beneficiary</small></td>
            </tr>

            <?php foreach ($sibling_educations as $sibling_education): ?>
                <tr>
                    <td class="part-table-data"><small><?= "$sibling_education->fname $sibling_education->mname $sibling_education->lname" ?></small></td>
                    <td class="part-table-data"><small><?= $sibling_education->id_no ?></small></td>
                    <td class="part-table-data"><small><?= $sibling_education->birth_cert_no ?></small></td>
                    <td class="part-table-data"><small><?= $sibling_education->institution_name ?></small></td>
                    <td class="part-table-data"><small><?= LmBaseEnums::byNameAndValue(LmBaseEnums::study_level, $sibling_education->study_level)->LABEL ?></small></td>
                    <td class="part-table-data"><small><?= number_format($sibling_education->annual_fees) ?></small></td>
                    <td class="part-table-data"><small><?= LmBaseEnums::byNameAndValue(LmBaseEnums::yes_no, $sibling_education->helb_beneficiary)->LABEL ?></small></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>