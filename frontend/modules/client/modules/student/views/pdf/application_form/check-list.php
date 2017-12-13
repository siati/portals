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
        <div class="part-element-narration"><?= $part->intro ?></div>
    <?php endif; ?>

    <?php foreach (ApplicationPartElements::forPart($part->id, ApplicationPartElements::active_yes) as $element): ?>

        <br/>

        <div class="part-container">
            <legend class="part-legend-2"><?= $element->title ?></legend>

            <?php if (!empty($element->narration)): ?>
                <pre class="part-element-narration"><?= $element->narration ?></pre>
            <?php endif; ?>

        </div>

    <?php endforeach; ?>
</div>