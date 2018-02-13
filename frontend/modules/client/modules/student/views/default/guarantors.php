<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $applicant integer */
/* @var $guarantors \frontend\modules\client\modules\student\models\ApplicantsGuarantors */
/* @var $form_content string */
/* @var $saved boolean */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use frontend\modules\client\modules\student\models\ApplicantsGuarantors;

$this->title = 'Guarantor\'s Details';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $employed = ApplicantsGuarantors::employed_yes ?>

<?php $pre = Yii::$app->request->isAjax ? 'site' : '../../../site' ?>

<?php $pre2 = Yii::$app->request->isAjax ? 'client/student/default/' : '' ?>

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

                <?= Html::button('Add Guarantor', ['class' => 'grntr grntr-new btn btn-sm btn-primary grntr-nav-btn', 'name' => 'grntr-det-btn-btn', 'of-id' => '']) ?>

                <?php ActiveForm::end(); ?>
            </div>

            <div class="grntr-frm-pn pull-right prnt-frm-hr">
                <?= $form_content ?>
            </div>

        </div>

        <div class="gnrl-frm-ftr">

            <?php if (Yii::$app->request->isAjax): ?>

                <?= Html::button('Update', ['id' => 'grntrs-btn', 'class' => 'btn btn-primary pull-left', 'name' => 'guarantors-button']) ?>

                <div class="btn btn-danger pull-right" onclick="closeDialog()"><b>Close</b></div>

            <?php else: ?>

                <?= Html::button('Update', ['id' => 'grntrs-btn', 'class' => 'btn btn-primary pull-right', 'name' => 'guarantors-button']) ?>

            <?php endif; ?>

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
                '$pre2' === '' || '$pre2' === null ? $('#form-grntr-det-btn').submit() : dynamicGuarantor();
            }
            
            function checkTheIDNo() {
                $.post('$pre2' + 'id-no-is-parents', {'ApplicantsGuarantors[id]': $('#applicantsguarantors-id').val(), 'ApplicantsGuarantors[applicant]': $('#applicantsguarantors-applicant').val(), 'ApplicantsGuarantors[id_no]': $('#applicantsguarantors-id_no').val()},
                    function (values) {
                        $.each(values[1],
                            function(attr, val) {
                                if ($('#' + attr).length) {
                                    $('#' + attr).val(val).blur().attr('disabled', values[0] ? true : false);

                                    if (attr === 'applicantsguarantors-employed')
                                        $('#' + attr).change();
                                    else
                                    if (attr === 'applicantsguarantors-county') {
                                        $('#applicantsguarantors-ward').val(values[1]['applicantsguarantors-ward']);
                                        countyChanged(values[1]['applicantsguarantors-county'], values[1]['applicantsguarantors-sub_county'], $('#applicantsguarantors-sub_county'), '$pre/dynamic-subcounties', values[1]['applicantsguarantors-constituency'], $('#applicantsguarantors-constituency'), '$pre/dynamic-constituencies');
                                    } else
                                    if (attr === 'applicantsguarantors-constituency')
                                        dynamicWards(values[1]['applicantsguarantors-constituency'], values[1]['applicantsguarantors-ward'], $('#applicantsguarantors-ward'), '$pre/dynamic-wards');
                                }
                            }
                        );
                        
                        $.each(values[2],
                            function(attr, val) {
                                $('#' + attr).length ? $('#' + attr).blur().attr('disabled', false) : '';
                            }
                        );
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
            /* highlight the active tab */
                highlightActiveGuarantorTab();
            /* highlight the active tab */
            
            /* show and hide employment details appropriately */
                $('#applicantsguarantors-employed').change(
                    function() {
                        $('#applicantsguarantors-kra_pin, #applicantsguarantors-staff_no, #applicantsguarantors-employer_name').blur();
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
            
            /* check if id no is corresponding to applicant's parent */
                $('#applicantsguarantors-id_no').change(
                    function () {
                        checkTheIDNo();
                    }
                );
            /* check if id no is corresponding to applicant's parent */

            /* the real submit button is hidden */
                $('#grntrs-btn').click(
                    function (event) {
                        event.preventDefault();
                        $('#grntrs-btn-inner').click();
                    }
                );
            /* the real submit button is hidden */
            
            /* is saved */
               '$saved' ? dataSaved('Success', 'Guarantors Details Saved', 'success') : '';
            /* is saved */

        "
        , \yii\web\VIEW::POS_READY
)
?>

<?php if (Yii::$app->request->isAjax): ?>

    <?php
    $this->registerJs(
            "
                function dynamicGuarantor() {
                    form = $('#form-grntr-det-btn');

                   $.post(form.attr('action'), form.serialize(),
                        function(frm) {
                            $('#yii-modal-cnt').html(frm);
                        }
                    );
                }
                
                function saveGuarantor() {
                    form = $('#form-grntr-det');
                    
                    post = form.serializeArray();

                    post.push({'name': 'sbmt', 'value': true});

                   $.post(form.attr('action'), post,
                        function(frm) {
                            $('#yii-modal-cnt').html(frm);
                        }
                    );
                }

            ", yii\web\View::POS_HEAD
    )
    ?>

    <?php
    $this->registerJs(
            "
                $('.fit-in-pn').css('max-height', $('#yii-modal-cnt').height() * 0.84 + 'px');
                
                $('#grntrs-btn-inner').click(
                    function(event) {
                        event.preventDefault();
                        
                        event.stopPropagation();
                        
                        saveGuarantor();
                    }
                );
            "
            , \yii\web\VIEW::POS_READY)
    ?>

<?php endif; ?>