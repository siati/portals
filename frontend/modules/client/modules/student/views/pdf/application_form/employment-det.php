<?php
/* @var $this yii\web\View */
/* @var $employed string */
/* @var $employment \frontend\modules\client\modules\student\models\ApplicantsEmployment */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use frontend\modules\client\modules\student\models\ApplicantsEmployment;
use common\models\LmBaseEnums;
use common\models\LmEmployers;
use common\models\PostalCodes;
use common\models\Counties;
use common\models\StaticMethods;
use frontend\modules\business\models\ApplicationParts;
?>

<?php if (is_object($employment) && LmBaseEnums::byNameAndElement(LmBaseEnums::yes_no, LmBaseEnums::yes)->VALUE == $employed): ?>

    <?php $postal_code = PostalCodes::returnCode($employment->postal_code) ?>

    <div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
        <legend class="part-legend"><?= $part->title ?></legend>

        <?php if (!empty($part->intro)): ?>
            <pre class="part-element-narration"><?= $part->intro ?></pre>
        <?php endif; ?>

        <table class="part-table">
            <tbody>
                <tr>
                    <td class="part-table-label" colspan="2">Employer Name</td>
                    <td class="part-table-label" colspan="2">Department</td>
                </tr>
                <tr>
                    <td class="part-table-data" colspan="2"><?= LmEmployers::searchEmployers($employment->employer_name, null, yii\db\ActiveRecord::one)->NAME ?></td>
                    <td class="part-table-data" colspan="2"><?= $employment->department ?></td>
                </tr>

                <tr><td class="part-table-divider"></td></tr>

                <tr>
                    <td class="part-table-label" colspan="2">Division</td>
                    <td class="part-table-label" colspan="2">Section</td>
                </tr>

                <tr>
                    <td class="part-table-data" colspan="2"><?= $employment->division ?></td>
                    <td class="part-table-data" colspan="2"><?= $employment->section ?></td>
                </tr>

                <tr><td class="part-table-divider"></td></tr>

                <tr>
                    <td class="part-table-label">Employment Terms</td>
                    <td class="part-table-label">Employment Date</td>
                    <td class="part-table-label">Employment Period</td>
                    <td class="part-table-label">Staff No.</td>
                </tr>

                <tr>
                    <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::employement_terms, $employment->employment_terms)->LABEL ?></td>
                    <td class="part-table-data"><?= StaticMethods::dateString($employment->employment_date, StaticMethods::long) ?></td>
                    <td class="part-table-data"><?= ApplicantsEmployment::employmentPeriod($employment->employment_terms)[$employment->employment_period] ?></td>
                    <td class="part-table-data"><?= $employment->pf_no ?></td>
                </tr>

                <tr><td class="part-table-divider"></td></tr>

                <tr>
                    <td class="part-table-label">KRA PIN</td>
                    <td class="part-table-label">Phone</td>
                    <td class="part-table-label">Email</td>
                    <td class="part-table-label">Town</td>
                </tr>

                <tr>
                    <td class="part-table-data"><?= $employment->kra_pin ?></td>
                    <td class="part-table-data"><?= $employment->phone ?></td>
                    <td class="part-table-data"><?= $employment->email ?></td>
                    <td class="part-table-data"><?= $employment->town ?></td>
                </tr>

                <tr><td class="part-table-divider"></td></tr>

                <tr>
                    <td class="part-table-label">County</td>
                    <td class="part-table-label">Postal Address</td>
                    <td class="part-table-label">Basic Pay, KShs.</td>
                    <td class="part-table-label">Net Pay, KShs.</td>
                </tr>

                <tr>
                    <td class="part-table-data"><?= is_object($county = Counties::returnCounty($employment->county)) ? $county->name : '' ?></td>
                    <td class="part-table-data"><?= is_object($postal_code) && !empty($employment->postal_no) ? "$employment->postal_no, $postal_code->town - $postal_code->code" : '' ?></td>
                    <td class="part-table-data"><?= $employment->basic_salary ?></td>
                    <td class="part-table-data"><?= $employment->net_salary ?></td>
                </tr>
            </tbody>
        </table>

        <?php foreach (ApplicationPartElements::forPart($part->id, ApplicationPartElements::active_yes) as $element): ?>

            <br/>

            <div class="part-container">
                <legend class="part-legend-2"><?= $element->title ?></legend>

                <?php if (!empty($element->narration)): ?>
                    <div class="part-element-narration"><?= $element->narration ?></div>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>

    </div>

<?php endif; ?>