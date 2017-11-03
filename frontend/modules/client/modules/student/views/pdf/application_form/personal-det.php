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
                <td class="part-table-label">First Name</td>
                <td class="part-table-label">Middle Name</td>
                <td class="part-table-label">Last Name</td>
                <td class="part-table-label">Gender</td>
            </tr>
            <tr>
                <td class="part-table-data"><?= $applicant->fname ?></td>
                <td class="part-table-data"><?= $applicant->mname ?></td>
                <td class="part-table-data"><?= $applicant->lname ?></td>
                <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::gender, $applicant->gender)->LABEL ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label">Birth Cert. No.</td>
                <td class="part-table-label">National. ID. No.</td>
                <td class="part-table-label">Date Of Birth</td>
                <td class="part-table-label">Married</td>
            </tr>
            <tr>
                <td class="part-table-data"><?= $user->birth_cert_no ?></td>
                <td class="part-table-data"><?= $user->id_no ?></td>
                <td class="part-table-data"><?= StaticMethods::dateString($applicant->dob, StaticMethods::long) ?></td>
                <td class="part-table-data"><?=Applicants::marrieds()[$applicant->married] ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label">Email Address</td>
                <td class="part-table-label">Mobile No.</td>
                <td class="part-table-label">Postal Address</td>
                <td class="part-table-label">KRA PIN</td>
            </tr>

            <tr>
                <td class="part-table-data"><?= $user->email ?></td>
                <td class="part-table-data"><?= $user->phone ?></td>
                <td class="part-table-data"><?= is_object($postal_code) && !empty($applicant->postal_no) ? "$applicant->postal_no, $postal_code->town - $postal_code->code" : '' ?></td>
                <td class="part-table-data"><?= $user->kra_pin ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label">County</td>
                <td class="part-table-label">Sub-County</td>
                <td class="part-table-label">Constituency</td>
                <td class="part-table-label">Ward</td>
            </tr>

            <tr>
                <td class="part-table-data"><?= is_object($county = Counties::returnCounty($applicant->county)) ? $county->name : '' ?></td>
                <td class="part-table-data"><?= is_object($sub_county = SubCounties::returnSubcounty($applicant->sub_county)) ? $sub_county->name : '' ?></td>
                <td class="part-table-data"><?= is_object($constituency = Constituencies::returnConstituency($applicant->constituency)) ? $constituency->name : '' ?></td>
                <td class="part-table-data"><?= is_object($ward = Wards::returnWard($applicant->ward)) ? $ward->name : '' ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label">Location</td>
                <td class="part-table-label">Sub-Location</td>
                <td class="part-table-label">Village / Estate</td>
                <td class="part-table-label">Employed</td>
            </tr>

            <tr>
                <td class="part-table-data"><?= $applicant->location ?></td>
                <td class="part-table-data"><?= $applicant->sub_location ?></td>
                <td class="part-table-data"><?= $applicant->village ?></td>
                <td class="part-table-data"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::yes_no, $applicant->employed)->LABEL ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label">Disability</td>
                <td class="part-table-label" colspan="3">Narration</td>
            </tr>

            <tr>
                <td class="part-table-data"><?= Applicants::disabilities()[$applicant->disability] ?></td>
                <td class="part-table-data" colspan="3"><?= $applicant->other_disability ?></td>
            </tr>
        </tbody>
    </table>
</div>