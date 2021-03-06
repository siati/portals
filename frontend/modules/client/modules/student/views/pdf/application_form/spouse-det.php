<?php
/* @var $this yii\web\View */
/* @var $spouse \frontend\modules\client\modules\student\models\ApplicantsSpouse */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use frontend\modules\client\modules\student\models\ApplicantsSpouse;
use common\models\LmBaseEnums;
use frontend\modules\business\models\ApplicationParts;
?>

<?php if (is_object($spouse)): ?>

    <?php if ($part->new_page != ApplicationParts::new_page_yes): ?> <br/> <?php endif; ?>

    <div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
        <legend class="part-legend"><?= $part->title ?> (<?= ApplicantsSpouse::relatioships()[$spouse->relationship] ?>)</legend>

        <?php if (!empty($part->intro)): ?>
            <pre class="part-element-narration"><?= $part->intro ?></pre>
        <?php endif; ?>

        <table class="part-table">
            <tbody>
                <tr>
                    <td class="part-table-label">First Name</td>
                    <td class="part-table-label">Middle Name</td>
                    <td class="part-table-label">Last Name</td>
                </tr>
                <tr>
                    <td class="part-table-data"><?= $spouse->fname ?></td>
                    <td class="part-table-data"><?= $spouse->mname ?></td>
                    <td class="part-table-data"><?= $spouse->lname ?></td>
                </tr>

                <tr><td class="part-table-divider"></td></tr>

                <tr>
                    <td class="part-table-label">National. ID. No.</td>
                    <td class="part-table-label">Phone No.</td>
                    <td class="part-table-label">Email</td>
                </tr>
                <tr>
                    <td class="part-table-data"><?= $spouse->id_no ?></td>
                    <td class="part-table-data"><?= $spouse->phone ?></td>
                    <td class="part-table-data"><?= $spouse->email ?></td>
                </tr>

                <?php $yesNo = LmBaseEnums::byNameAndValue(LmBaseEnums::yes_no, $spouse->employed) ?>

                <?php if (strtolower($yesNo->ELEMENT) == strtolower(LmBaseEnums::yes)): ?>

                    <tr><td class="part-table-divider"></td></tr>

                    <tr>
                        <td class="part-table-label">Employed</td>
                        <td class="part-table-label">Employee No.</td>
                        <td class="part-table-label">KRA PIN</td>
                    </tr>

                    <tr>
                        <td class="part-table-data"><?= $yesNo->LABEL ?></td>
                        <td class="part-table-data"><?= $spouse->staff_no ?></td>
                        <td class="part-table-data"><?= $spouse->kra_pin ?></td>
                    </tr>

                    <tr><td class="part-table-divider"></td></tr>

                    <tr>
                        <td class="part-table-label">Employer Name</td>
                        <td class="part-table-label">Employer Phone</td>
                        <td class="part-table-label">Employer Email</td>
                    </tr>

                    <tr>
                        <td class="part-table-data"><?= $spouse->employer_name ?></td>
                        <td class="part-table-data"><?= $spouse->employer_phone ?></td>
                        <td class="part-table-data"><?= $spouse->employer_email ?></td>
                    </tr>

                <?php endif; ?>

            </tbody>
        </table>
    </div>

<?php endif; ?>