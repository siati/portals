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

                <?php $openings_exist = true ?>

            <?php endforeach; ?>
        </table>

        <?php if (empty($openings_exist)): ?>

            <?= $this->render('../../../../../../views/site/no-content', ['message' => 'Oops!<br/><br/>Nothing for you to see here<br/><br/>Close and try some other product']) ?>

        <?php endif; ?>

    </div>

    <div class="tmln-frm-div pull-left">
        <?= $this->render('../../../../../../views/site/no-content', ['message' => $message = empty($openings_exist) ? 'Oops!<br/><br/>Nothing for you to see here<br/><br/>Close and try some other product' : 'Select An Academic Year To Proceed']) ?>
    </div>

    <div class="tmln-cmpl-div pull-right">
        <?= $this->render('../../../../../../views/site/no-content', ['message' => $message]) ?>
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
            
            function applicationCompile(aplcnt, aplctn, apl) {
                $.post('application-compile', {'applicant': aplcnt, 'application': aplctn, 'appeal': apl},
                    function (compile) {
                        $('.tmln-cmpl-div').html(compile);
                    }
                );
            }
            
            function amateurForm(aplcnt, aplctn, apl) {
                $.post('amateur-form', {'applicant': aplcnt, 'application': aplctn, 'appeal': apl},
                    function (res) {
                        fileDownload('../../../', res[0], res[1], 'Amateur Form');
                    }
                );
            }
            
            function selectedApplication(btn) {
                $('.tmln-lst-dv-td').removeClass('btn-success').addClass('btn-primary');
                btn.removeClass('btn-primary').addClass('btn-success');
            }
            
            function autoSelectFirstApplication() {
                clicked = false;
                
                $('.tmln-lst-dv').find('.tmln-lst-dv-td').each(
                    function () {
                        if (!clicked) {
                            $(this).click();
                            clicked = true;
                        }
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
            /* load application */
                $('.tmln-lst-dv-td').click(
                    function () {
                        selectedApplication(
                            $(this), applicationCompile('$applicant', $(this).parent().attr('opng'), $(this).parent().attr('apl'),
                                applicantsApplication('$applicant', $(this).parent().attr('opng'), $(this).parent().attr('apl'))
                            )
                        );
                    }
                );
            /* load application */
            
            /* autoselect first academic year */
                autoSelectFirstApplication();
            /* autoselect first academic year */
        "
        , \yii\web\VIEW::POS_READY
)
?>