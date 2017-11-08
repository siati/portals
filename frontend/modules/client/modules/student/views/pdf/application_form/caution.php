<?php
/* @var $this yii\web\View */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use frontend\modules\business\models\ApplicationParts;
?>

<div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">

    <legend class="part-legend"><?= $part->title ?></legend>

    <?php if (!empty($part->intro)): ?>
        <pre class="part-element-narration-xs"><?= $part->intro ?></pre>
    <?php endif; ?>

</div>