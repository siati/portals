<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $openings \frontend\modules\business\models\ProductOpening */
/* @var $applicant integer */

use frontend\modules\business\models\ProductOpening;
use common\models\LmBaseEnums;
?>

<div class="tmln-mthr-div">
    <div class="tmln-lst-dv pull-left">
        <table>
            <?php foreach ($openings as $opening): ?>
                <tr opng="<?= $opening->id ?>" apl="0">
                    <td class="td-pdg-bth btn btn-xs btn-primary tmln-lst-dv-td" style="margin-bottom: 2.5px; text-align: justify"><?= LmBaseEnums::byNameAndValue(LmBaseEnums::applicant_type, $opening->subsequent)->LABEL ?>, <?= $opening->academic_year ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="tmln-frm-div pull-left">
        Select academic year to proceed
    </div>

    <div class="tmln-cmpl-div pull-right">
        <div class="tmln-cmpl-lst-div">Select academic year to proceed</div>
        
        <div class="tmln-cmpl-btn-div"><div class="btn btn-sm btn-primary" style="width: 100%"><b><i class="fa fa-print"></i> Compile and Print</b></div></div>
    </div>
</div>

<div class="tmln-btns-div">
    <div class="btn btn-sm btn-danger pull-right" onclick="closeDialog()"><b>Close</b></div>
</div>

<?php
$this->registerJs(
        "
            function applicantsApplication(aplcnt, aplctn, apl) {
                $.post('load-application', {'Applications[applicant]': aplcnt, 'Applications[application]': aplctn, 'appeal': apl},
                    function (form) {
                        $('.tmln-frm-div').html(form);
                    }
                );
            }
        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            $('.tmln-lst-dv-td').click(
                function () {
                    applicantsApplication('$applicant', $(this).parent().attr('opng'), $(this).parent().attr('apl'))
                }
            );
        "
        , \yii\web\VIEW::POS_READY
)
?>