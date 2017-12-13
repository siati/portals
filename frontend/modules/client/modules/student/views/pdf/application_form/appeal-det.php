<?php
/* @var $this yii\web\View */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use frontend\modules\client\modules\student\models\EducationBackground;
use frontend\modules\business\models\ProductOpeningSettings;
use frontend\modules\business\models\ApplicationParts;
use frontend\modules\business\models\ApplicationPartElements;
use frontend\modules\business\models\ApplicationPartCheckers;
?>

<?php $product_setting = ProductOpeningSettings::hasSocietyNarration($part->application) ?>

<?php if ($part->new_page != ApplicationParts::new_page_yes): ?> <br/> <?php endif; ?>

<div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
    <legend class="part-legend"><?= $part->title ?></legend>

    <?php if (!empty($part->intro)): ?>
        <pre class="part-element-narration"><?= $part->intro ?></pre>
    <?php endif; ?>

    <?php if (is_object($personal_appeal_element = ApplicationPartElements::byPartAndElement($part->id, ApplicationPartCheckers::part_appeal_personal)) && $personal_appeal_element->active == ApplicationPartElements::active_yes): ?>
        <?= $this->render('personal-det-appeal-2', ['applicant' => $applicant, 'personal_appeal_element' => $personal_appeal_element, 'part' => $part]) ?>
    <?php endif; ?>

    <?php if (is_object($education_background_element = ApplicationPartElements::byPartAndElement($part->id, ApplicationPartCheckers::part_appeal_education)) && $education_background_element->active == ApplicationPartElements::active_yes): ?>
        <?=
        $this->render('education-backgrounds-appeal', [
            'part' => $part,
            'education_background_element' => $education_background_element,
            'education_backgrounds' => [
                EducationBackground::educationToLoad(null, $applicant->id, EducationBackground::study_level_primary),
                EducationBackground::educationToLoad(null, $applicant->id, EducationBackground::study_level_secondary)
            ]
                ]
        )
        ?>
    <?php endif; ?>

</div>