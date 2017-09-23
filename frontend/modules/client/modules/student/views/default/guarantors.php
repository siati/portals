<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $applicant integer */
/* @var $guarantors \frontend\modules\client\modules\student\models\ApplicantsGuarantors */
/* @var $form_content string */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use frontend\modules\client\modules\student\models\ApplicantsGuarantors;

$this->title = 'Guarantor\'s Details';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $employed = ApplicantsGuarantors::employed_yes ?>

<div class="gnrl-frm grntr-det">

    <div class="gnrl-frm-cont">

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy gnrl-frm-pdg-top-0 gnrl-frm-pdg-rgt-0 gnrl-frm-pdg-btm-0 fit-in-pn" style="height: 85%">

            <div class="grntr-tab-pn pull-left grntr-lst">
                <?php ActiveForm::begin(['id' => 'form-grntr-det-btn']); ?>

                <input name='ApplicantsGuarantors[id]' type='hidden' id='id'>
                
                <input name='ApplicantsGuarantors[applicant]' type='hidden' value='<?= $applicant ?>'>

                <?php $i = 0 ?>
                
                <?php foreach ($guarantors as $guarantor): ?>
                    <?= Html::button('Guarantor ' . ++$i, ['class' => "grntr grntr-$guarantor->id btn btn-sm btn-primary grntr-nav-btn", 'name' => 'grntr-det-btn-btn', 'of-id' => $guarantor->id]) ?>
                <?php endforeach; ?>
                
                <?= Html::button('New Guarantor', ['class' => 'grntr grntr-new btn btn-sm btn-primary grntr-nav-btn', 'name' => 'grntr-det-btn-btn', 'of-id' => '']) ?>

                <?php ActiveForm::end(); ?>
            </div>

            <div class="grntr-frm-pn pull-right prnt-frm-hr">
                <?= $form_content ?>
            </div>

        </div>

        <div class="gnrl-frm-ftr">

            <?= Html::button('Update', ['id' => 'grntrs-btn', 'class' => 'btn btn-primary pull-right', 'name' => 'guarantors-button']) ?>

        </div>

    </div>
</div>

<?php
$this->registerJs(
        "
            function highlightActiveGuarantorTab() {
                btn = (id = $('#applicantsguarantors-id').val()) * 1 > 0 ? $('.grntr-' + id) : $('.grntr-new');
                
                btn.removeClass('btn-primary').addClass('btn-success');
            }
            
            function loadGuarantor(id) {
                $('#id').val(id);
                $('#form-grntr-det-btn').submit();
            }

        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            /* highlight the active tab */
                highlightActiveGuarantorTab();
            /* highlight the active tab */
            
            /* show and hide employment details appropriately */
                $('#applicantsguarantors-employed').change(
                    function() {
                        $(this).val() === '$employed' ? $('#oth-grntr-det').show() : $('#oth-grntr-det').hide();
                    }
                );
            /* show and hide employment details appropriately */
            
            /* trigger show or hide employment details appropriately */
                $('#applicantsguarantors-employed').change();
            /* trigger show or hide employment details appropriately */
            
            /* load guarantor details on the view */
                $('.grntr').click(
                    function () {
                        loadGuarantor($(this).attr('of-id'));
                    }
                );
            /* load guarantor details on the view */

            /* the real submit button is hidden */
                $('#grntrs-btn').click(
                    function (event) {
                        event.preventDefault();
                        $('#grntrs-btn-inner').click();
                    }
                );
            /* the real submit button is hidden */

        "
        , \yii\web\VIEW::POS_READY
)
?>
