<?php

/* @var $this yii\web\View */
/* @var $application \frontend\modules\business\models\Applications */
/* @var $settings \frontend\modules\business\models\ProductOpeningSettings */

use frontend\modules\business\models\ProductOpeningSettings;
use frontend\modules\client\modules\student\models\ApplicantsInstitution;
use frontend\modules\business\models\ProductSettings;
?>

<?php

foreach ($settings as $setting):
    if ($setting->setting == ProductSettings::has_bursary):
        $bursary = ProductOpeningSettings::hasBursary($application->application);
    elseif ($setting->setting == ProductSettings::has_society_narration):
        $narration = ProductOpeningSettings::hasSocietyNarration($application->application);
    elseif ($setting->setting == ProductSettings::tuition_or_upkeep && is_array($tuition_or_upkeep = ProductOpeningSettings::tuitionOrUpkeep($application->application))):
        $tuition = !empty($tuition_or_upkeep[ProductSettings::yes]);
        $upkeep = !empty($tuition_or_upkeep[ProductSettings::no]);
    elseif ($setting->setting == ProductSettings::has_financial_literacy):
        $financial = ProductOpeningSettings::hasFInancialLiteracy($application->application);
    endif;
endforeach;
?>

<?php

if (!empty($tuition) || !empty($upkeep) || !empty($bursary) || !empty($narration)):

    echo $this->render('institution-partial', [
        'model' => ApplicantsInstitution::institutionToLoad(null, $application->applicant),
        'tuition' => !empty($tuition),
        'upkeep' => !empty($upkeep),
        'bursary' => !empty($bursary),
        'narration' => !empty($narration)
            ]
    );

endif;
?>