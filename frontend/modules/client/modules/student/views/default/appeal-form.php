<?php

/* @var $this yii\web\View */
/* @var $application \frontend\modules\business\models\Applications */

use frontend\modules\client\modules\student\models\Applicants;
use frontend\modules\client\modules\student\models\EducationBackground;
?>

<?=

$this->render('personal-partial-appeal', [
    'model' => Applicants::returnApplicant($application->applicant),
    'disabled' => $disabled = $application->appealPrintOutExists()
        ]
);
?>

<?=

$this->render('education-partial-appeal', [
    'models' => [
    EducationBackground::educationToLoad(null, $application->applicant, EducationBackground::study_level_primary),
    EducationBackground::educationToLoad(null, $application->applicant, EducationBackground::study_level_secondary)
    ],
    'disabled' => $disabled
        ]
);
?>