<?php
/* @var $this yii\web\View */
/* @var $user \common\models\User */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */
/* @var $institution \frontend\modules\client\modules\student\models\ApplicantsInstitution */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use common\models\LmInstitution;
use frontend\modules\business\models\ProductOpeningSettings;
use frontend\modules\business\models\ApplicationParts;
?>

<?php $product_setting = ProductOpeningSettings::hasSocietyNarration($part->application) ?>

<?php if ($part->new_page != ApplicationParts::new_page_yes): ?> <br/> <?php endif; ?>

<div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
    <legend class="part-legend"><?= $part->title ?></legend>

    <?php if (!empty($part->intro)): ?>
        <pre class="part-element-narration"><?= $part->intro ?></pre>
    <?php endif; ?>

    <table class="part-table">
        <tbody>
            <tr>
                <td class="part-table-data align-left">Name</td>
                <td class="part-table-data align-left"><?= "$applicant->fname $applicant->mname $applicant->lname" ?></td>
            </tr>
            
            <tr>
                <td class="part-table-data align-left"><?= empty($user->id_no) ? 'Birth Cert. No.' : 'Nat. ID. No.' ?> - Reg. No</td>
                <td class="part-table-data align-left"><?= empty($user->id_no) ? $user->birth_cert_no : $user->id_no ?> - <?= $institution->registration_no ?></td>
            </tr>
            
            <tr>
                <td class="part-table-data align-left">Institution</td>
                <td class="part-table-data align-left"><?= LmInstitution::byInstitutionCode($institution->institution_code)->INSTITUTIONNAME ?></td>
            </tr>
            
            <tr>
                <td class="part-table-data align-left">Email Address</td>
                <td class="part-table-data align-left"><?= $user->email ?></td>
            </tr>
            
            <tr>
                <td class="part-table-data align-left">Phone No.</td>
                <td class="part-table-data align-left"><?= $user->phone ?></td>
            </tr>
            
            <tr>
                <td class="part-table-data align-left">Amount Applied</td>
                <td class="part-table-data align-left">KShs. <?= number_format($institution->amount_applied, 0) ?></td>
            </tr>
        </tbody>
    </table>
</div>