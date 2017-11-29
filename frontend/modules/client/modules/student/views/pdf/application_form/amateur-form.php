<?php

/* @var $this yii\web\View */
/* @var $application \frontend\modules\business\models\Applications */


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
?>

<?php $user = User::returnUser($application->applicant) ?>

<?php $applicant = Applicants::returnApplicant($application->applicant) ?>

<?php foreach (ApplicationParts::forApplication($application->application, 1) as $part): ?>

    <?php if ($part->part == ApplicationPartCheckers::part_caution): ?>

        <?= $this->render('../application_form/caution', ['part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_personal): ?>

        <?= $this->render('../application_form/personal-det', ['user' => $user, 'applicant' => $applicant, 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_residence): ?>

        <?= $this->render('../application_form/residence-det', ['residence' => ApplicantsResidence::forApplicant($application->applicant), 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_institution): ?>

        <?= $this->render('../application_form/institution-det', ['institution' => empty($institution) ? $institution = ApplicantsInstitution::forApplicant($application->applicant) : $institution, 'part' => $part]) ?>

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

    <?php elseif ($part->part == ApplicationPartCheckers::part_guarantors): ?>

        <?= $this->render('../application_form/guarantors-det', ['guarantors' => ApplicantsGuarantors::forApplicant($application->applicant), 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_bank): ?>

        <?= $this->render('../application_form/bank-det', ['applicant' => $applicant, 'part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_terms_and_conditions): ?>

        <?= $this->render('../application_form/terms-conditions', ['part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_check_list): ?>

        <?= $this->render('../application_form/check-list', ['part' => $part]) ?>

    <?php elseif ($part->part == ApplicationPartCheckers::part_submission): ?>

        <?= $this->render('../application_form/submission', ['part' => $part]) ?>

    <?php endif; ?>

<?php endforeach; ?>
