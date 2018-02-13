<?php
/* @var $this \yii\web\View */

use frontend\modules\client\modules\student\models\Applicants
?>

<?php $applicant = Applicants::returnApplicant($user_id = Yii::$app->user->identity->id); ?>

<?php $this->title = 'The Students\' Portal' ?>

<?php
$items = [
    'My Profile' => [
        ['sd-nav-prsnl', 'Personal Details', 'fa fa-id-card', 'register', 1, 1],
        ['sd-nav-rsdnc', 'Current Residence', 'fa fa-home', 'residence', 1, 0.8],
        ['sd-nav-edctn', 'Education Background', 'fa fa-file-text', 'education', 1, 1],
        ['sd-nav-inst', 'Institution Details', 'fa fa-institution', 'institution', 1, 1],
        ['sd-nav-eplymt', 'Employment Details', 'fa fa-industry', 'employment', 1, 1],
        ['sd-nav-prnts', 'Parents\' Details', 'fa fa-group', 'parents', 1, 1],
        ['sd-nav-expns', 'Family Expenses', 'fa fa-money', 'expenses', 1, 1],
        ['sd-nav-spnsrs', 'Sponsors\' Details', 'fa fa-money', 'sponsors', 1, 1],
        ['sd-nav-sps', 'Spouse\'s Details', 'fa fa-heart', 'spouse', 1, 0.80],
        ['sd-nav-grntrs', 'Guarantors\' Details', 'fa fa-group', 'guarantors', 1, 1]
    ],
    'My Loan Applications' => [
        ['aply-dt', 'Loan Application', 'fa fa-pencil', '', 1, 1],
        ['stts-dt', 'Application Status', 'fa fa-home', '', 1, 1],
        ['dsbs-dt', 'Disbursement Schedules', 'fa fa-file-text', '', 1, 1],
        ['dsbs-dt', 'Enquiries', 'fa fa-institution', '', 1, 1]
    ]
        ]
?>

<div class="site-index full-dim-vtcl-scrl jumbotron">

    <?php $w = 0 ?>

    <?php foreach ($items as $item => $sub_items): ?>

        <div class="full-width menu-hdr td-pdg-lft bold kasa-pointa" <?php if (++$w > 1): ?> style="margin-top: 30px"<?php endif; ?>><?= $item ?></div>

        <table>

            <?php $i = 0 ?>

            <?php foreach ($sub_items as $sub_item): ?>

                <?php if (++$i % 5 == 1): ?>

                    <tr>

                    <?php endif; ?>

                    <td id="ajx-<?= $sub_item[0] ?>-td" class="td-pdg-rnd">
                        <div id="ajx-<?= $sub_item[0] ?>" class="btn btn-lg btn-primary home-tile white-space" actn="<?= $sub_item[3] ?>" wdt="<?= $sub_item[4] ?>" hgt="<?= $sub_item[5] ?>">
                            <span class="<?= $sub_item[2] ?> home-icon full-width"></span>
                            <p><small><i><?= $sub_item[1] ?></i></small></p>
                        </div>
                    </td>

                    <?php if ($i % 5 == 0 || $i == count($sub_items)): ?>

                    </tr>

                <?php endif; ?>

            <?php endforeach; ?>

        </table>

    <?php endforeach; ?>

</div>