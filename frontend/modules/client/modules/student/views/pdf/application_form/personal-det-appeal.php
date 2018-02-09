<?php
/* @var $this yii\web\View */
/* @var $user \common\models\User */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */
/* @var $application \frontend\modules\business\models\Applications */
/* @var $institution \frontend\modules\client\modules\student\models\ApplicantsInstitution */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use common\models\LmInstitution;
use frontend\modules\business\models\ApplicationParts;
?>

<?php if ($part->new_page != ApplicationParts::new_page_yes): ?> <br/> <?php endif; ?>

<div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
    <legend class="part-legend"><?= $part->title ?></legend>

    <?php if (!empty($part->intro)): ?>
        <pre class="part-element-narration"><?= $part->intro ?></pre>
    <?php endif; ?>

    <table class="part-table">
        <tbody>
            <tr>
                <td class="part-table-label">Full Name :</td>
                <td class="part-table-data align-left"><?= "$applicant->fname $applicant->mname $applicant->lname" ?></td>
                <td class="part-table-label">Loan Serial No. :</td>
                <td class="part-table-data align-left"><?= $application->serial_no ?></td>
            </tr>
            
            <tr>
                <td class="part-table-label"><?= empty($user->id_no) ? 'Birth Cert. No.' : 'Nat. ID. No.' ?> :</td>
                <td class="part-table-data align-left"><?= empty($user->id_no) ? $user->birth_cert_no : $user->id_no ?></td>
                <td class="part-table-label">Institution Name :</td>
                <td class="part-table-data align-left"><?= LmInstitution::byInstitutionCode($institution->institution_code)->INSTITUTIONNAME ?></td>
            </tr>
            
            <tr>
                <td class="part-table-label">Phone No. :</td>
                <td class="part-table-data align-left"><?= $user->phone ?></td>
                <td class="part-table-label">Email Address :</td>
                <td class="part-table-data align-left"><?= $user->email ?></td>
            </tr>
            
            <tr>
                <td class="part-table-label">Loan Awarded :</td>
                <td class="part-table-data align-left">KShs. 0.00</td>
                <td class="part-table-label">Bursary Awarded :</td>
                <td class="part-table-data align-left">KShs. 0.00</td>
            </tr>
        </tbody>
    </table>
</div>