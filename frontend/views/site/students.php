<?php
/* @var $this \yii\web\View */

use common\models\User
?>

<?php $this->title = 'The Students\' Portal' ?>

<div class="site-index full-dim jumbotron" style="padding-top: 10px">

    <?php $w = 0 ?>

    <?php foreach (User::applicantsMenu() as $item => $sub_items): ?>

        <div class="full-width menu-hdr td-pdg-lft bold kasa-pointa" <?php if (++$w > 1): ?> style="margin-top: 30px"<?php endif; ?>><?= $item ?></div>

        <table>

            <?php $i = 0 ?>

            <?php foreach ($sub_items as $sub_item): ?>

                <?php if (++$i % 5 == 1): ?>

                    <tr>

                    <?php endif; ?>

                    <td id="ajx-<?= $sub_item['id'] ?>-td" class="td-pdg-rnd">
                        <div id="ajx-<?= $sub_item['id'] ?>" class="btn btn-lg btn-primary home-tile white-space" actn="<?= $sub_item['ax'] ?>" wdt="<?= $sub_item['wd'] ?>" hgt="<?= $sub_item['hg'] ?>" title="<?= $sub_item['tt'] ?>"
                             <?php if (isset($sub_item['ap'])): ?> ap="<?= $sub_item['ap'] ?>" pr="<?= $sub_item['pr'] ?>" <?php endif; ?>
                             >
                            <span class="<?= $sub_item['fa'] ?> home-icon full-width"></span>
                            <p><small><i><?= $sub_item['nm'] ?></i></small></p>
                        </div>
                    </td>

                    <?php if ($i % 5 == 0 || $i == count($sub_items)): ?>

                    </tr>

                <?php endif; ?>

            <?php endforeach; ?>

        </table>

    <?php endforeach; ?>

</div>