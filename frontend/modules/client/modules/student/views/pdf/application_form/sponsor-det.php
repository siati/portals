<?php
/* @var $this yii\web\View */
/* @var $sponsors \frontend\modules\client\modules\student\models\ApplicantSponsors */

use frontend\modules\client\modules\student\models\ApplicantSponsors;
use common\models\PostalCodes;
?>

<?php if (!empty($sponsors)): ?>
    <?php $i = 0 ?>

    <div class="part-container">
        <legend class="part-legend">Sponsor Details</legend>

        <?php foreach ($sponsors as $sponsor): ?>

            <?php $postal_code = PostalCodes::returnCode($sponsor->postal_code) ?>

            <div class="part-container">
                <legend class="part-legend-2">Sponsor <?= ++$i ?></legend>

                <table class="part-table">
                    <tbody>
                        <tr>
                            <td class="part-table-label">Type</td>
                            <td class="part-table-label">Name</td>
                            <td class="part-table-label">Study Level</td>
                        </tr>
                        <tr>
                            <td class="part-table-data"><?= ApplicantSponsors::relatioships()[$sponsor->relationship] ?></td>
                            <td class="part-table-data"><?= $sponsor->name ?></td>
                            <td class="part-table-data"><?= ApplicantSponsors::studyLevels()[$sponsor->study_level] ?></td>
                        </tr>

                        <tr><td class="part-table-divider"></td></tr>

                        <tr>
                            <td class="part-table-label">Phone</td>
                            <td class="part-table-label">Email Address</td>
                            <td class="part-table-label">Postal Address</td>
                        </tr>

                        <tr>
                            <td class="part-table-data"><?= $sponsor->phone ?></td>
                            <td class="part-table-data"><?= $sponsor->email ?></td>
                            <td class="part-table-data"><?= is_object($postal_code) && !empty($sponsor->postal_no) ? "$sponsor->postal_no, $postal_code->town - $postal_code->code" : '' ?></td>
                        </tr>
                    </tbody>
                </table>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>