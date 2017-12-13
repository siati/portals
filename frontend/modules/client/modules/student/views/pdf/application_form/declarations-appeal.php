<?php
/* @var $this yii\web\View */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use frontend\modules\business\models\ApplicationParts;
use frontend\modules\business\models\ApplicationPartElements;
?>

<?php if ($part->new_page != ApplicationParts::new_page_yes): ?> <br/> <?php endif; ?>

<div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
    <legend class="part-legend"><?= $part->title ?></legend>

    <?php if (!empty($part->intro)): ?>
        <pre class="part-element-narration"><?= $part->intro ?></pre>
    <?php endif; ?>

    <?php foreach (ApplicationPartElements::forPart($part->id, ApplicationPartElements::active_yes) as $element): ?>

        <?php if ($element->active == ApplicationPartElements::active_yes): ?>

            <?php if (!empty($element->narration)): ?>
                <br/>

                <div class="part-container">
                    <legend class="part-legend-2"><?= $element->title ?></legend>
                    
                    <pre class="part-element-narration border-less"><?= $element->narration ?></pre>

                </div>
            <?php endif; ?>

        <?php endif; ?>

    <?php endforeach; ?>

</div>