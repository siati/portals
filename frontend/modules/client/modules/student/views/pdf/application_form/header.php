<?php
/* @var $this yii\web\View */
/* @var $application \frontend\modules\business\models\Applications */
/* @var $opening \frontend\modules\business\models\ProductOpening */
/* @var $product \frontend\modules\business\models\Products */
/* @var $is_appeal integer */
?>

<div class="part-element-narration border-less">

    <div class="part-container part-element-narration border-less left-half-header-width stamp-height pull-left-pdf">

        <pre class="part-element-narration-xxx">
            Anniversary Towers 18<sup>th</sup> Floor, University Way
            P O Box 69489 - 00400, NAIROBI, KENYA
            Telephone: +254 020 2278000
            Mobile: 0711052000
            Email: lending@helb.co.ke
            <span class="fa-pdf fa" style="color: #5bc0de">&#xf099</span> twitter.com/HELBpage
            <span class="fa-pdf fa" style="color: #31b0d5">&#xf082</span> facebook.com/HELBpage

            <b style="font-size: 12px">Serial Number</b>
            <div class="part-table-data-header" style="margin-left: 25px"><?= $application->serial_no ?></div>
        </pre>
        

    </div>

    <div class="part-container part-element-narration border-less mid-half-header-width stamp-height pull-left-pdf">

        <img src="<?= Yii::$app->homeUrl ?>../../common/assets/logos/kakamega.gif" height="90">

        <br/>

        <strong style="font-size: 19px">KAKAMEGA COUNTY EDUCATION REVOLVING FUND</strong>

        <br/>

        <strong><?= strtoupper($product->name) ?></strong>

        <br/>

        <strong> <?= empty($is_appeal) ? ($opening->isSubsequent() ? 'SUBSEQUENT APPLICATION' : 'FIRST TIME APPLICATION') : ('APPEAL / REVIEW') ?> FORM</strong>

        <br/>

        <br/>

        <strong style="font-size: 14px"><?= $opening->academic_year ?></strong>

    </div>

    <div class="part-container part-element-narration border-less right-half-header-width stamp-height pull-right-pdf">

        <div class="align-right">
            HELB ACT (1995)

            <br/>

            CAP 213A

            <br/>

            <br/>

            <br/>
            
            <img src="<?= Yii::$app->homeUrl ?>../../common/assets/logos/helb-logo.jpg" height="90">
        </div>
    </div>

</div>