<?php
/* @var $this yii\web\View */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use frontend\modules\business\models\ApplicationParts;
use frontend\modules\business\models\ApplicationPartElements;
use frontend\modules\business\models\ApplicationPartCheckers;
?>

<div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
    <legend class="part-legend"><?= $part->title ?></legend>

    <?php if (!empty($part->intro)): ?>
        <div class="part-element-narration"><?= $part->intro ?></div>
    <?php endif; ?>

    <?php foreach (ApplicationPartElements::forPart($part->id, ApplicationPartElements::active_yes) as $element): ?>

        <?php if ($element->active == ApplicationPartElements::active_yes): ?>

            <?php $parent_or_student = in_array($element->element, [ApplicationPartCheckers::part_declaration_applicant, ApplicationPartCheckers::part_declaration_parent_guardian]) ?>

            <?php if (!empty($element->narration)): ?>
                <br/>

                <div class="part-container">
                    <legend class="part-legend-2"><?= $element->title ?></legend>

                    <div class="part-element-narration border-less<?php if (!$parent_or_student): ?> left-half-width pull-left-pdf<?php endif; ?>"><?= $element->narration ?></div>

                    <?php if (!$parent_or_student): ?>

                        <div class="part-element-narration border-less right-half-width pull-right-pdf">

                            <div class="part-container part-element-narration half-width-sm stamp-height pull-left-pdf">
                                <legend class="part-legend-2 part-element-narration-sm">Name / Address / Telephone</legend>
                            </div>

                            <div class="part-container part-element-narration half-width-sm stamp-height pull-right-pdf">
                                <legend class="part-legend-2 part-element-narration-sm">Official Rubber Stamp</legend>
                            </div>

                        </div>

                    <?php endif; ?>

                </div>
            <?php endif; ?>

        <?php endif; ?>

    <?php endforeach; ?>

</div>