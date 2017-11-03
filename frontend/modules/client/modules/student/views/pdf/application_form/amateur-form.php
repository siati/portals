<?php
/* @var $this yii\web\View */
/* @var $user \common\models\User */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */
/* @var $residence \frontend\modules\client\modules\student\models\ApplicantsResidence */
/* @var $institution \frontend\modules\client\modules\student\models\ApplicantsInstitution */
/* @var $sibling_educations \frontend\modules\client\modules\student\models\ApplicantsSiblingEducationExpenses */
/* @var $education_backgrounds \frontend\modules\client\modules\student\models\EducationBackground */
/* @var $parents \frontend\modules\client\modules\student\models\ApplicantsParents */
/* @var $sponsors \frontend\modules\client\modules\student\models\ApplicantSponsors */
/* @var $family_expenses \frontend\modules\client\modules\student\models\ApplicantsFamilyExpenses */
/* @var $spouse \frontend\modules\client\modules\student\models\ApplicantsSpouse */
?>

<?= $this->render('../application_form/personal-det', ['user' => $user, 'applicant' => $applicant]) ?>

<?= $this->render('../application_form/residence-det', ['residence' => $residence]) ?>

<?= $this->render('../application_form/institution-det', ['institution' => $institution]) ?>

<?= $this->render('../application_form/loan-det', ['institution' => $institution]) ?>

<?= $this->render('../application_form/sibling-expenses', ['sibling_educations' => $sibling_educations]) ?>

<?= $this->render('../application_form/education-backgrounds', ['education_backgrounds' => $education_backgrounds]) ?>

<?= $this->render('../application_form/parents-det', ['applicant' => $applicant, 'parents' => $parents]) ?>

<?= $this->render('../application_form/sponsor-det', ['sponsors' => $sponsors]) ?>

<?= $this->render('../application_form/family-expenses', ['family_expenses' => $family_expenses]) ?>

<?= $this->render('../application_form/spouse-det', ['spouse' => $spouse]) ?>
