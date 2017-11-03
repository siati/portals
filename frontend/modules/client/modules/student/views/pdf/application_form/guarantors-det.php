<?php
/* @var $this yii\web\View */
/* @var $guarantors \frontend\modules\client\modules\student\models\ApplicantsGuarantors */

use common\models\LmBaseEnums;
use common\models\PostalCodes;
use common\models\Counties;
use common\models\SubCounties;
use common\models\Constituencies;
use common\models\Wards;
?>

<?php $i = 0 ?>

<div class="part-container">
    <legend class="part-legend">Guarantors' Details</legend>

    <?php foreach ($guarantors as $guarantor): ?>

        <div class="part-container">

            <legend class="part-legend-2">Guarantor <?= ++$i ?></legend>

            <table class="part-table">
                <tbody>

                    <tr>
                        <td class="part-table-label">First Name</td>
                        <td class="part-table-label">Middle Name</td>
                        <td class="part-table-label">Last Name</td>
                    </tr>

                    <tr>
                        <td class="part-table-data"><?= $guarantor->fname ?></td>
                        <td class="part-table-data"><?= $guarantor->mname ?></td>
                        <td class="part-table-data"><?= $guarantor->lname ?></td>
                    </tr>

                    <tr><td class="part-table-divider"></td></tr>

                    <tr>
                        <td class="part-table-label">Nat. ID. No.</td>
                        <td class="part-table-label">Year of Birth</td>
                        <td class="part-table-label">Gender</td>
                    </tr>

                    <tr>
                        <td class="part-table-data"><?= $guarantor->id_no ?></td>
                        <td class="part-table-data"><?= $guarantor->yob ?></td>
                        <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::gender, $guarantor->gender)->LABEL ?></td>
                    </tr>

                    <tr><td class="part-table-divider"></td></tr>

                    <tr>
                        <td class="part-table-label">Phone No.</td>
                        <td class="part-table-label">Email Address</td>
                        <td class="part-table-label">Postal Address</td>
                    </tr>

                    <?php $postal_code = PostalCodes::returnCode($guarantor->postal_code) ?>

                    <tr>
                        <td class="part-table-data"><?= $guarantor->phone ?></td>
                        <td class="part-table-data"><?= $guarantor->email ?></td>
                        <td class="part-table-data"><?= is_object($postal_code) && !empty($guarantor->postal_no) ? "$guarantor->postal_no, $postal_code->town - $postal_code->code" : '' ?></td>
                    </tr>

                    <tr><td class="part-table-divider"></td></tr>

                    <tr>
                        <td class="part-table-label">County</td>
                        <td class="part-table-label">Sub-County</td>
                        <td class="part-table-label">Constituency</td>
                    </tr>

                    <tr>
                        <td class="part-table-data"><?= is_object($county = Counties::returnCounty($guarantor->county)) ? $county->name : '' ?></td>
                        <td class="part-table-data"><?= is_object($sub_county = SubCounties::returnSubcounty($guarantor->sub_county)) ? $sub_county->name : '' ?></td>
                        <td class="part-table-data"><?= is_object($constituency = Constituencies::returnConstituency($guarantor->constituency)) ? $constituency->name : '' ?></td>
                    </tr>

                    <tr><td class="part-table-divider"></td></tr>

                    <?php $yesNo = LmBaseEnums::byNameAndValue(LmBaseEnums::yes_no, $guarantor->employed) ?>

                    <tr>
                        <td class="part-table-label">Ward</td>
                        <td class="part-table-label">Employed</td>
                        <td class="part-table-label">KRA PIN</td>
                    </tr>

                    <tr>
                        <td class="part-table-data"><?= is_object($ward = Wards::returnWard($guarantor->ward)) ? $ward->name : '' ?></td>
                        <td class="part-table-data"><?= $yesNo->LABEL ?></td>
                        <td class="part-table-data"><?= $guarantor->kra_pin ?></td>
                    </tr>

                    <tr><td class="part-table-divider"></td></tr>

                    <tr>
                        <td class="part-table-label">Location</td>
                        <td class="part-table-label">Sub-Location</td>
                        <td class="part-table-label">Village / Estate</td>
                    </tr>

                    <tr>
                        <td class="part-table-data"><?= $guarantor->location ?></td>
                        <td class="part-table-data"><?= $guarantor->sub_location ?></td>
                        <td class="part-table-data"><?= $guarantor->village ?></td>
                    </tr>

                    <?php if (strtolower($yesNo->ELEMENT) == strtolower(LmBaseEnums::yes)): ?>

                        <tr><td class="part-table-divider"></td></tr>

                        <tr>
                            <td class="part-table-label">Employer Name</td>
                            <td class="part-table-label">Employer Phone</td>
                            <td class="part-table-label">Employer Email</td>
                        </tr>

                        <tr>
                            <td class="part-table-data"><?= $guarantor->employer_name ?></td>
                            <td class="part-table-data"><?= $guarantor->employer_phone ?></td>
                            <td class="part-table-data"><?= $guarantor->employer_email ?></td>
                        </tr>

                        <tr><td class="part-table-divider"></td></tr>

                        <tr>
                            <td class="part-table-label">Employer Address</td>
                            <td class="part-table-label">Occupation</td>
                            <td class="part-table-label">Staff No.</td>
                        </tr>

                        <?php $employer_postal_code = PostalCodes::returnCode($guarantor->employer_postal_code) ?>

                        <tr>
                            <td class="part-table-data"><?= is_object($employer_postal_code) && !empty($guarantor->employer_postal_no) ? "$guarantor->employer_postal_no, $employer_postal_code->town - $employer_postal_code->code" : '' ?></td>
                            <td class="part-table-data"><?= $guarantor->occupation ?></td>
                            <td class="part-table-data"><?= $guarantor->staff_no ?></td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>

        </div>

    <?php endforeach; ?>
</div>