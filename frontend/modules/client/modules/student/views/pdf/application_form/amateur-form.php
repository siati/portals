<?php
/* @var $this yii\web\View */
/* @var $application \frontend\modules\business\models\Applications */
/* @var $is_appeal integer */

use frontend\modules\business\models\Products;
use frontend\modules\business\models\ProductOpening;
use common\models\User;
use frontend\modules\client\modules\student\models\Applicants;
use frontend\modules\client\modules\student\models\ApplicantsResidence;
use frontend\modules\client\modules\student\models\ApplicantsParents;
use frontend\modules\client\modules\student\models\EducationBackground;
use frontend\modules\client\modules\student\models\ApplicantsGuarantors;
use frontend\modules\client\modules\student\models\ApplicantsInstitution;
use frontend\modules\client\modules\student\models\ApplicantsFamilyExpenses;
use frontend\modules\client\modules\student\models\ApplicantsSiblingEducationExpenses;
use frontend\modules\client\modules\student\models\ApplicantsEmployment;
use frontend\modules\client\modules\student\models\ApplicantsSpouse;
use frontend\modules\client\modules\student\models\ApplicantSponsors;
use frontend\modules\business\models\ApplicationParts;
use frontend\modules\business\models\ApplicationPartCheckers;
use frontend\modules\business\models\ProductOpeningSettings;
use common\models\StaticMethods;
?>

<?php $user = User::returnUser($application->applicant) ?>

<?php $applicant = Applicants::returnApplicant($application->applicant) ?>

<?php $opening = ProductOpening::returnOpening($application->application) ?>

<?php $product = Products::returnProduct($opening->product) ?>

<htmlpageheader name='otherpagesheader' style='display: none'>
    <div class="part-element-narration-sm heda">
        <?= "$applicant->fname $applicant->mname $applicant->lname; ID. No.: $user->id_no; Application: $product->code - $opening->academic_year; Serial No.: $application->serial_no" ?>
        <img src="<?= str_replace('frontend/web', 'common', Yii::$app->homeUrl) ?>assets/logos/helb-logo.jpg" height="90" style="margin-top: -20px">
    </div>
</htmlpageheader>

<htmlpagefooter name="pagefooter" style="display:none">
    <div class="part-element-narration-sm futa">
        <div class="pull-left-pdf align-center" style="width: 30%">contactcentre@helb.co.ke</div>
        <div class="pull-left-pdf align-center" style="width: 40%"><?= StaticMethods::dateString(StaticMethods::today(), StaticMethods::longest) ?></div>
        <div class="pull-right-pdf align-center" style="width: 30%">Page {PAGENO} of {nb}</div>
    </div>
</htmlpagefooter>

<?= $this->render('../application_form/header', ['application' => $application, 'is_appeal' => $is_appeal, 'opening' => $opening, 'product' => $product]) ?>

<?php foreach (ApplicationParts::forApplication($application->application, $is_appeal, 1) as $part): ?>

    <?php if ($part->part == ApplicationPartCheckers::part_caution): ?>

        <?= $this->render('../application_form/caution', ['part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_personal): ?>

        <?= $this->render('../application_form/personal-det', ['user' => $user, 'applicant' => $applicant, 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_personal_subsequent): ?>

        <?= $this->render('../application_form/personal-det-subsequent', ['user' => $user, 'applicant' => $applicant, 'institution' => empty($institution) ? $institution = ApplicantsInstitution::forApplicant($application->applicant) : $institution, 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_personal_appeal): ?>

        <?= $this->render('../application_form/personal-det-appeal', ['user' => $user, 'applicant' => $applicant, 'application' => $application, 'institution' => empty($institution) ? $institution = ApplicantsInstitution::forApplicant($application->applicant) : $institution, 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_appeal): ?>

        <?= $this->render('../application_form/appeal-det', ['applicant' => $applicant, 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_residence): ?>

        <?= $this->render('../application_form/residence-det', ['residence' => ApplicantsResidence::forApplicant($application->applicant), 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_institution): ?>

        <?= $this->render('../application_form/institution-det', ['institution' => empty($institution) ? $institution = ApplicantsInstitution::forApplicant($application->applicant) : $institution, 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_institution_subsequent): ?>

        <?= $this->render('../application_form/institution-det-subsequent', ['part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_loan): ?>

        <?= $this->render('../application_form/loan-det', ['institution' => empty($institution) ? $institution = ApplicantsInstitution::forApplicant($application->applicant) : $institution, 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_siblings): ?>

        <?= $this->render('../application_form/sibling-expenses', ['sibling_educations' => ApplicantsSiblingEducationExpenses::expensesForApplicant($application->applicant), 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_education): ?>

        <?= $this->render('../application_form/education-backgrounds', ['education_backgrounds' => EducationBackground::searchEducations($application->applicant, null), 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_parents): ?>

        <?= $this->render('../application_form/parents-det', ['applicant' => $applicant, 'parents' => ApplicantsParents::forApplicant($application->applicant), 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_guardians): ?>

        <?= $this->render('../application_form/sponsor-det', ['sponsors' => ApplicantSponsors::sponsorsForApplicant($application->applicant), 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_expenses): ?>

        <?= $this->render('../application_form/family-expenses', ['family_expenses' => ApplicantsFamilyExpenses::expensesForApplicant($application->applicant), 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_spouse): ?>

        <?= $this->render('../application_form/spouse-det', ['spouse' => ApplicantsSpouse::forApplicant($application->applicant), 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_employment): ?>

        <?= $this->render('../application_form/employment-det', ['employed' => $applicant->employed, 'employment' => ApplicantsEmployment::forApplicant($application->applicant), 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_declaration): ?>

        <?= $this->render('../application_form/declarations', ['part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_declaration_appeal): ?>

        <?= $this->render('../application_form/declarations-appeal', ['part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_guarantors): ?>

        <?php if (ProductOpeningSettings::noOfGuarantors($application->application) > 0): ?>

            <?= $this->render('../application_form/guarantors-det', ['guarantors' => ApplicantsGuarantors::forApplicant($application->applicant), 'part' => $part]) ?>

        <?php endif; ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_bank): ?>

        <?= $this->render('../application_form/bank-det', ['applicant' => $applicant, 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_terms_and_conditions): ?>

        <?= $this->render('../application_form/terms-conditions', ['part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_terms_and_conditions_subsequent): ?>

        <?= $this->render('../application_form/terms-conditions-subsequent', ['part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_check_list): ?>

        <?= $this->render('../application_form/check-list', ['part' => $part]) ?>

    <?php elseif (in_array($part->part, [ApplicationPartCheckers::part_submission, ApplicationPartCheckers::part_submission_subsequent, ApplicationPartCheckers::part_submission_appeal])): ?>

        <?= $this->render('../application_form/submission', ['part' => $part]) ?>

    <?php endif; ?>

<?php endforeach; ?>
