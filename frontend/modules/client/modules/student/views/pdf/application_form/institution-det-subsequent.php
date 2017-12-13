<?php
/* @var $this yii\web\View */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use frontend\modules\business\models\ApplicationParts;
?>

<?php if ($part->new_page != ApplicationParts::new_page_yes): ?> <br/> <?php endif; ?>

<div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
    <legend class="part-legend"><?= $part->title ?></legend>

    <?php if (!empty($part->intro)): ?>
        <pre class="part-element-narration"><?= $part->intro ?></pre>
    <?php endif; ?>

</div>