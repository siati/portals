<?php
/* @var $this yii\web\View */
/* @var $user \common\models\User */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */
/* @var $residence \frontend\modules\client\modules\student\models\ApplicantsResidence */
/* @var $institution \frontend\modules\client\modules\student\models\ApplicantsInstitution */
?>

<?= $this->render('../application_form/personal-det', ['user' => $user, 'applicant' => $applicant]) ?>

<?= $this->render('../application_form/residence-det', ['residence' => $residence]) ?>

<?= $this->render('../application_form/institution-det', ['institution' => $institution]) ?>

<?= $this->render('../application_form/loan-det', ['institution' => $institution]) ?>
