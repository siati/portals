<?php
/* @var $this yii\web\View */
/* @var $residence \frontend\modules\client\modules\student\models\ApplicantsResidence */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use common\models\Counties;
use common\models\SubCounties;
use common\models\Constituencies;
use common\models\Wards;
use frontend\modules\business\models\ApplicationParts;
?>

<div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
    <legend class="part-legend"><?= $part->title ?></legend>
    
    <?php if (!empty($part->intro)): ?>
        <pre class="part-element-narration"><?= $part->intro ?></pre>
    <?php endif; ?>
    
    <table class="part-table">
        <tbody>
            <tr>
                <td class="part-table-label">Nearest Primary School</td>
                <td class="part-table-label">Location</td>
                <td class="part-table-label">Sub-Location</td>
                <td class="part-table-label">Village / Estate</td>
            </tr>
            <tr>
                <td class="part-table-data"><?= $residence->nearest_primary ?></td>
                <td class="part-table-data"><?= $residence->location ?></td>
                <td class="part-table-data"><?= $residence->sub_location ?></td>
                <td class="part-table-data"><?= $residence->village ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label">County</td>
                <td class="part-table-label">Sub-County</td>
                <td class="part-table-label">Constituency</td>
                <td class="part-table-label">Ward</td>
            </tr>

            <tr>
                <td class="part-table-data"><?= is_object($county = Counties::returnCounty($residence->county)) ? $county->name : '' ?></td>
                <td class="part-table-data"><?= is_object($sub_county = SubCounties::returnSubcounty($residence->sub_county)) ? $sub_county->name : '' ?></td>
                <td class="part-table-data"><?= is_object($constituency = Constituencies::returnConstituency($residence->constituency)) ? $constituency->name : '' ?></td>
                <td class="part-table-data"><?= is_object($ward = Wards::returnWard($residence->ward)) ? $ward->name : '' ?></td>
            </tr>

            <tr><td class="part-table-divider"></td></tr>

            <tr>
                <td class="part-table-label" colspan="2">Nearest Town</td>
                <td class="part-table-label" colspan="2">Apartment</td>
            </tr>

            <tr>
                <td class="part-table-data" colspan="2"><?= $residence->town ?></td>
                <td class="part-table-data" colspan="2"><?= $residence->apartment ?></td>
            </tr>
        </tbody>
    </table>
</div>