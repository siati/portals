<?php
/* @var $this yii\web\View */
/* @var $family_expenses \frontend\modules\client\modules\student\models\ApplicantsFamilyExpenses */
/* @var $part \frontend\modules\business\models\ApplicationParts */

use common\models\LmBaseEnums;
use frontend\modules\business\models\ApplicationParts;
?>

<?php $columns = 4 ?>

<?php $i = 0 ?>

<?php if (!empty($family_expenses)): ?>

    <div class="part-container<?= $part->new_page == ApplicationParts::new_page_yes ? ' page-break' : '' ?>">
        <legend class="part-legend"><?= $part->title ?></legend>

        <?php if (!empty($part->intro)): ?>
            <pre class="part-element-narration"><?= $part->intro ?></pre>
        <?php endif; ?>

        <table class="part-table">
            <tbody>

                <?php foreach ($family_expenses as $family_expense): ?>

                    <?php
                    if (++$i % $columns == 1):
                        ?>

                        <tr>

                            <?php $amounts = [] ?>

                            <?php
                        endif;
                        ?>

                        <td class="part-table-label"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::expense_type, $family_expense->expense_type)->LABEL ?></td>

                        <?php $amounts[] = $family_expense->amount ?>

                        <?php
                        if ($i % $columns == 0 || $i == count($family_expenses)):
                            ?>

                        </tr>

                        <?php if (!empty($amounts)): ?>

                            <tr>

                                <?php foreach ($amounts as $amount): ?>

                                    <td class="part-table-data"><?= number_format($amount, 2) ?></td>

                                <?php endforeach; ?>

                            </tr>

                        <?php endif; ?>

                        <?php
                    endif;
                    ?>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>