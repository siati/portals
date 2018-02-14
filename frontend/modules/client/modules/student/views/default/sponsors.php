<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $applicant integer */
/* @var $sponsors \frontend\modules\client\modules\student\models\ApplicantSponsors */
/* @var $form_content string */
/* @var $saved boolean */

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = 'Sponsor\'s Details';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $pre = Yii::$app->request->isAjax ? 'client/student/default/' : '' ?>

<div class="gnrl-frm spnsr-det">

    <div class="gnrl-frm-cont">

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy gnrl-frm-pdg-top-0 gnrl-frm-pdg-rgt-0 gnrl-frm-pdg-btm-0 fit-in-pn" style="height: 85%">

            <div class="spnsr-tab-pn pull-left grntr-lst">
                <?php ActiveForm::begin(['id' => 'form-spnsr-det-btn']); ?>

                <input name='ApplicantSponsors[id]' type='hidden' id='id'>

                <input name='ApplicantSponsors[applicant]' type='hidden' value='<?= $applicant ?>'>

                <?php $i = 0 ?>

                <?php foreach ($sponsors as $sponsor): ?>
                    <?= Html::button('Sponsor ' . ++$i, ['class' => "spnsr spnsr-$sponsor->id btn btn-sm btn-primary spnsr-nav-btn", 'name' => 'spnsr-det-btn-btn', 'of-id' => $sponsor->id]) ?>
                <?php endforeach; ?>

                <?= Html::button('Add Sponsor', ['class' => 'spnsr spnsr-new btn btn-sm btn-primary spnsr-nav-btn', 'name' => 'spnsr-det-btn-btn', 'of-id' => '']) ?>

                <?php ActiveForm::end(); ?>
            </div>

            <div class="spnsr-frm-pn pull-right prnt-frm-hr">
                <?= $form_content ?>
            </div>

        </div>

        <div class="gnrl-frm-ftr"><?php if (Yii::$app->request->isAjax): ?>

                <?= Html::button('Update', ['id' => 'spnsrs-btn', 'class' => 'btn btn-primary pull-left', 'name' => 'sponsors-button']) ?>

                <div class="btn btn-danger pull-right" onclick="closeDialog()"><b>Close</b></div>

            <?php else: ?>

                <?= Html::button('Update', ['id' => 'spnsrs-btn', 'class' => 'btn btn-primary pull-right', 'name' => 'sponsors-button']) ?>

            <?php endif; ?>
            
        </div>

    </div>
</div>

<?php
$this->registerJs(
        "
            function highlightActiveSponsorTab() {
                btn = (id = $('#applicantsponsors-id').val()) * 1 > 0 ? $('.spnsr-' + id) : $('.spnsr-new');
                
                btn.removeClass('btn-primary').addClass('btn-success');
            }
            
            function loadSponsor(id) {
                $('#id').val(id);
                '$pre' === '' || '$pre' === null ? $('#form-spnsr-det-btn').submit() : dynamicSponsor();
            }
        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            /* highlight the active tab */
                highlightActiveSponsorTab();
            /* highlight the active tab */
            
            /* load sponsor details on the view */
                $('.spnsr').click(
                    function () {
                        loadSponsor($(this).attr('of-id'));
                    }
                );
            /* load sponsor details on the view */

            /* the real submit button is hidden */
                $('#spnsrs-btn').click(
                    function (event) {
                        event.preventDefault();
                        $('#spnsrs-btn-inner').click();
                    }
                );
            /* the real submit button is hidden */
            
            /* is saved */
               '$saved' ? dataSaved('Success', 'Personal Details Saved', 'success') : '';
            /* is saved */

        "
        , \yii\web\VIEW::POS_READY
)
?>

<?php if (Yii::$app->request->isAjax): ?>

    <?php
    $this->registerJs(
            "
                function dynamicSponsor() {
                    form = $('#form-spnsr-det-btn');

                   $.post(form.attr('action'), form.serialize(),
                        function(frm) {
                            $('#yii-modal-cnt').html(frm);
                        }
                    );
                }
                
                function saveSponsor() {
                    form = $('#form-spnsr-det');
                    
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
                $('.fit-in-pn').css('max-height', $('#yii-modal-cnt').height() * 0.70 + 'px');
                
                $('#spnsrs-btn-inner').click(
                    function(event) {
                        event.preventDefault();
                        
                        event.stopPropagation();
                        
                        saveSponsor();
                    }
                );
            "
            , \yii\web\VIEW::POS_READY)
    ?>

<?php endif; ?>
