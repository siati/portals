<?php
/* @var $this yii\web\View */
/* @var $user \common\models\User */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */

use frontend\modules\client\modules\student\models\Applicants;
use common\models\StaticMethods;
use common\models\LmBaseEnums;
use common\models\PostalCodes;
use common\models\Counties;
use common\models\SubCounties;
use common\models\Constituencies;
use common\models\Wards;
?>

<?php $postal_code = PostalCodes::returnCode($applicant->postal_code) ?>

<div class="part-container">
    <legend class="part-legend">Personal Details</legend>
    
    <table class="part-table">
        <tbody>
            <tr>
                <td class="part-table-label"><b>First Name</b></td>
                <td class="part-table-label"><b>Middle Name</b></td>
                <td class="part-table-label"><b>Last Name</b></td>
                <td class="part-table-label"><b>Gender</b></td>
            </tr>
            <tr>
                <td class="part-table-data"><?= $applicant->fname ?></td>
                <td class="part-table-data"><?= $applicant->mname ?></td>
                <td class="part-table-data"><?= $applicant->lname ?></td>
                <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::gender, $applicant->gender)->LABEL ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label"><b>Birth Cert. No.</b></td>
                <td class="part-table-label"><b>National. ID. No.</b></td>
                <td class="part-table-label"><b>Date Of Birth</b></td>
                <td class="part-table-label"><b>Married</b></td>
            </tr>
            <tr>
                <td class="part-table-data"><?= $user->birth_cert_no ?></td>
                <td class="part-table-data"><?= $user->id_no ?></td>
                <td class="part-table-data"><?= StaticMethods::dateString($applicant->dob, StaticMethods::long) ?></td>
                <td class="part-table-data"><?=Applicants::marrieds()[$applicant->married] ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label"><b>Email Address</b></td>
                <td class="part-table-label"><b>Mobile No.</b></td>
                <td class="part-table-label"><b>Postal Address</b></td>
                <td class="part-table-label"><b>KRA PIN</b></td>
            </tr>

            <tr>
                <td class="part-table-data"><?= $user->email ?></td>
                <td class="part-table-data"><?= $user->phone ?></td>
                <td class="part-table-data"><?= is_object($postal_code) && !empty($applicant->postal_no) ? "$applicant->postal_no, $postal_code->town - $postal_code->code" : '' ?></td>
                <td class="part-table-data"><?= $user->kra_pin ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label"><b>County</b></td>
                <td class="part-table-label"><b>Sub-County</b></td>
                <td class="part-table-label"><b>Constituency</b></td>
                <td class="part-table-label"><b>Ward</b></td>
            </tr>

            <tr>
                <td class="part-table-data"><?= is_object($county = Counties::returnCounty($applicant->county)) ? $county->name : '' ?></td>
                <td class="part-table-data"><?= is_object($sub_county = SubCounties::returnSubcounty($applicant->sub_county)) ? $sub_county->name : '' ?></td>
                <td class="part-table-data"><?= is_object($constituency = Constituencies::returnConstituency($applicant->constituency)) ? $constituency->name : '' ?></td>
                <td class="part-table-data"><?= is_object($ward = Wards::returnWard($applicant->ward)) ? $ward->name : '' ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label"><b>Location</b></td>
                <td class="part-table-label"><b>Sub-Location</b></td>
                <td class="part-table-label"><b>Village / Estate</b></td>
                <td class="part-table-label"><b>Employed</b></td>
            </tr>

            <tr>
                <td class="part-table-data"><?= $applicant->location ?></td>
                <td class="part-table-data"><?= $applicant->sub_location ?></td>
                <td class="part-table-data"><?= $applicant->village ?></td>
                <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::yes_no, $applicant->employed)->LABEL ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label"><b>Disability</b></td>
                <td class="part-table-label" colspan="3"><b>Narration</b></td>
            </tr>

            <tr>
                <td class="part-table-data"><?= Applicants::disabilities()[$applicant->disability] ?></td>
                <td class="part-table-data" colspan="3"><?= $applicant->other_disability ?></td>
            </tr>
        </tbody>
    </table>
</div>