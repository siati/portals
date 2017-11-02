<?php
/* @var $this yii\web\View */
/* @var $parents \frontend\modules\client\modules\student\models\ApplicantsParents */

use frontend\modules\client\modules\student\models\ApplicantsParents;
use common\models\LmBaseEnums;
use common\models\PostalCodes;
use common\models\Counties;
use common\models\SubCounties;
use common\models\Constituencies;
use common\models\Wards;
?>

<div class="part-container">
    <legend class="part-legend">Parents' Details</legend>

    <?php foreach ($parents as $parent): ?>

        <div class="part-container">

            <legend class="part-legend-2"><?= ApplicantsParents::relationships()[$parent->relationship] ?>'s Details</legend>

            <table class="part-table">
                <tbody>

                    <tr>
                        <td class="part-table-label">First Name</td>
                        <td class="part-table-label">Middle Name</td>
                        <td class="part-table-label">Last Name</td>
                        <td class="part-table-label">Nat. ID. No.</td>
                    </tr>

                    <tr>
                        <td class="part-table-data"><?= $parent->fname ?></td>
                        <td class="part-table-data"><?= $parent->mname ?></td>
                        <td class="part-table-data"><?= $parent->lname ?></td>
                        <td class="part-table-data"><?= $parent->id_no ?></td>
                    </tr>

                    <tr><td class="part-table-divider"></td></tr>

                    <tr>
                        <td class="part-table-label">Year of Birth</td>
                        <td class="part-table-label">Birth. Cert. No.</td>
                        <td class="part-table-label">Gender</td>
                        <td class="part-table-label">Phone No.</td>
                    </tr>

                    <tr>
                        <td class="part-table-data"><?= $parent->yob ?></td>
                        <td class="part-table-data"><?= $parent->birth_cert_no ?></td>
                        <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::gender, $parent->gender)->LABEL ?></td>
                        <td class="part-table-data"><?= $parent->phone ?></td>
                    </tr>

                    <tr><td class="part-table-divider"></td></tr>

                    <tr>
                        <td class="part-table-label">Email Address</td>
                        <td class="part-table-label">Postal Address</td>
                        <td class="part-table-label">Pays Fees</td>
                        <td class="part-table-label">KRA PIN</td>
                    </tr>

                    <?php $postal_code = PostalCodes::returnCode($parent->postal_code) ?>

                    <tr>
                        <td class="part-table-data"><?= $parent->email ?></td>
                        <td class="part-table-data"><?= is_object($postal_code) && !empty($parent->postal_no) ? "$parent->postal_no, $postal_code->town - $postal_code->code" : '' ?></td>
                        <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::yes_no, $parent->pays_fees)->LABEL ?></td>
                        <td class="part-table-data"><?= $parent->kra_pin ?></td>
                    </tr>

                    <tr><td class="part-table-divider"></td></tr>

                    <tr>
                        <td class="part-table-label"><b>County</b></td>
                        <td class="part-table-label"><b>Sub-County</b></td>
                        <td class="part-table-label"><b>Constituency</b></td>
                        <td class="part-table-label"><b>Ward</b></td>
                    </tr>

                    <tr>
                        <td class="part-table-data"><?= is_object($county = Counties::returnCounty($parent->county)) ? $county->name : '' ?></td>
                        <td class="part-table-data"><?= is_object($sub_county = SubCounties::returnSubcounty($parent->sub_county)) ? $sub_county->name : '' ?></td>
                        <td class="part-table-data"><?= is_object($constituency = Constituencies::returnConstituency($parent->constituency)) ? $constituency->name : '' ?></td>
                        <td class="part-table-data"><?= is_object($ward = Wards::returnWard($parent->ward)) ? $ward->name : '' ?></td>
                    </tr>

                    <tr><td class="part-table-divider"></td></tr>

                    <tr>
                        <td class="part-table-label"><b>Location</b></td>
                        <td class="part-table-label"><b>Sub-Location</b></td>
                        <td class="part-table-label"><b>Village / Estate</b></td>
                        <td class="part-table-label"><b>Highest Education</b></td>
                    </tr>

                    <tr>
                        <td class="part-table-data"><?= $parent->location ?></td>
                        <td class="part-table-data"><?= $parent->sub_location ?></td>
                        <td class="part-table-data"><?= $parent->village ?></td>
                        <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::study_level, $parent->education_level)->LABEL ?></td>
                    </tr>

                    <?php $yesNo = LmBaseEnums::byNameAndValue(LmBaseEnums::yes_no, $parent->employed) ?>

                    <?php if (strtolower($yesNo->ELEMENT) == strtolower(LmBaseEnums::yes)): ?>

                        <tr><td class="part-table-divider"></td></tr>

                        <tr>
                            <td class="part-table-label">Employed</td>
                            <td class="part-table-label">Employer Name</td>
                            <td class="part-table-label">Employer Phone</td>
                            <td class="part-table-label">Employer Email</td>
                        </tr>

                        <tr>
                            <td class="part-table-data"><?= $yesNo->LABEL ?></td>
                            <td class="part-table-data"><?= $parent->employer_name ?></td>
                            <td class="part-table-data"><?= $parent->employer_phone ?></td>
                            <td class="part-table-data"><?= $parent->employer_email ?></td>
                        </tr>

                        <tr><td class="part-table-divider"></td></tr>

                        <tr>
                            <td class="part-table-label">Employer Address</td>
                            <td class="part-table-label">Parent's Occupation</td>
                            <td class="part-table-label">Staff No.</td>
                            <td class="part-table-label">Gross Pay, KShs.</td>
                        </tr>

                        <?php $employer_postal_code = PostalCodes::returnCode($parent->employer_postal_code) ?>

                        <tr>
                            <td class="part-table-data"><?= is_object($employer_postal_code) && !empty($parent->employer_postal_no) ? "$parent->employer_postal_no, $employer_postal_code->town - $employer_postal_code->code" : '' ?></td>
                            <td class="part-table-data"><?= $parent->occupation ?></td>
                            <td class="part-table-data"><?= $parent->staff_no ?></td>
                            <td class="part-table-data"><?= number_format($parent->gross_monthly_salary, 2) ?></td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>

            <div class="part-container">

                <legend class="part-legend-3">Income, KShs.</legend>

                <table class="part-table">
                    <tbody>
                        <tr>
                            <td class="part-table-label">Annual Farming</td>
                            <td class="part-table-label">Annual Business</td>
                            <td class="part-table-label">Other Annual</td>
                        </tr>

                        <tr>
                            <td class="part-table-data"><?= number_format($parent->farming_annual, 2) ?></td>
                            <td class="part-table-data"><?= number_format($parent->business_annual, 2) ?></td>
                            <td class="part-table-data"><?= number_format($parent->other_annual, 2) ?></td>
                        </tr>

                        <tr><td class="part-table-divider"></td></tr>

                        <tr>
                            <td class="part-table-label">Annual Govt. Support</td>
                            <td class="part-table-label">Annual Relief</td>
                            <td class="part-table-label">Monthly Pension</td>
                        </tr>

                        <tr>
                            <td class="part-table-data"><?= number_format($parent->govt_support_annual, 2) ?></td>
                            <td class="part-table-data"><?= number_format($parent->relief_annual, 2) ?></td>
                            <td class="part-table-data"><?= number_format($parent->monthly_pension, 2) ?></td>
                        </tr>
                    </tbody>
                </table>
                
            </div>

        </div>

    <?php endforeach; ?>
</div>