<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $applicant integer */
/* @var $backgrounds array */
/* @var $form_content string */
/* @var $saved boolean */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use frontend\modules\client\modules\student\models\EducationBackground;

$this->title = 'Education Background';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $study_level_primary = EducationBackground::study_level_primary ?>
<?php $study_level_secondary = EducationBackground::study_level_secondary ?>
<?php $primary_cert = EducationBackground::primary_cert ?>
<?php $secondary_cert = EducationBackground::secondary_cert ?>

<?php $pre = Yii::$app->request->isAjax ? 'client/student/default/' : '' ?>

<div class="gnrl-frm edcn-det">

    <div class="gnrl-frm-cont">

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy gnrl-frm-pdg-top-0 gnrl-frm-pdg-rgt-0 gnrl-frm-pdg-btm-0 fit-in-pn" style="height: 85%">

            <div class="edcn-tab-pn pull-left lvl-lst">
                <?php ActiveForm::begin(['id' => 'form-edcn-det-btn']); ?>

                <input name='EducationBackground[id]' type='hidden' id='id'>

                <input name='EducationBackground[applicant]' type='hidden' value='<?= $applicant ?>'>

                <input name='EducationBackground[study_level]' type='hidden' id="study_level">

                <?php foreach ($backgrounds as $id => $background): ?>
                    <?= Html::button($background[1], ['class' => "edcn-bcg bcg-$id btn btn-sm btn-primary edcn-nav-btn", 'name' => 'edcn-det-btn-btn', 'edctn' => $id, 'lvl' => in_array($background[0], [$study_level_primary, $study_level_secondary]) ? $background[0] : '']) ?>
                <?php endforeach; ?>

                <?= Html::button('New Education', ['class' => 'edcn-bcg bcg-new btn btn-sm btn-primary edcn-nav-btn', 'name' => 'edcn-det-btn-btn', 'edctn' => '', 'lvl' => '']) ?>

                <?php ActiveForm::end(); ?>
            </div>

            <div class="edcn-frm-pn pull-right edcn-frm-hr" style="padding-top: 10px">
                <?= $form_content ?>
            </div>

        </div>

        <div class="gnrl-frm-ftr">

            <?php if (Yii::$app->request->isAjax): ?>

                <?= Html::button('Update', ['id' => 'edcn-btn', 'class' => 'btn btn-primary pull-left', 'name' => 'education-button']) ?>

                <div class="btn btn-danger pull-right" onclick="closeDialog()"><b>Close</b></div>

            <?php else: ?>

                <?= Html::button('Update', ['id' => 'edcn-btn', 'class' => 'btn btn-primary pull-right', 'name' => 'education-button']) ?>

            <?php endif; ?>

        </div>

    </div>
</div>

<?php
$this->registerJs(
        "
            function grade() {
                if ((lvl = $('#educationbackground-study_level').val()) === '$study_level_primary' || lvl === '$study_level_secondary')
                    $.post('$pre' + 'grade', {'score': $('#educationbackground-score').val(), 'out_of': $('#educationbackground-out_of').val()},
                        function (grd) {
                            $('#educationbackground-grade').val(grd);
                        }
                    );
            }
            
            function studyLevelChanged() {
                (grd_cls = (is_pri = (lvl = $('#educationbackground-study_level').val()) === '$study_level_primary') || lvl === '$study_level_secondary') ?
                    $('#educationbackground-score, #educationbackground-out_of').blur().attr('disabled', null) :
                    $('#educationbackground-score, #educationbackground-out_of').blur().attr('disabled', 'disabled').val(null);
                 
                $.post('$pre' + 'inst-types', {'study_level': $('#educationbackground-study_level').val()},
                    function (typs) {
                        $('#educationbackground-institution_type').html(typs).blur().attr('disabled', grd_cls ? 'disabled' : null);
                    }
                );
                
                $('#educationbackground-course_name').val(grd_cls ? (is_pri ? '$primary_cert' : '$secondary_cert') : (null)).blur().attr('disabled', grd_cls ? 'disabled' : null);
                 
                $.post('$pre' + 'merits', {'study_level': $('#educationbackground-study_level').val()},
                    function (mrts) {
                        $('#educationbackground-grade').html(mrts).blur().attr('disabled', grd_cls ? 'disabled' : null);
                        
                        grd_cls && ($('#educationbackground-out_of').val() === null || $('#educationbackground-out_of').val() === '') ?
                            $.post('$pre' + 'out-ofs', {'study_level': $('#educationbackground-study_level').val(), 'year': $('#educationbackground-till').val()},
                                function (otf) {
                                    $('#educationbackground-out_of').val(otf).change();
                                }
                            ) : '';
                    }
                );
                
                $.post('$pre' + 'educ-since-till', {'applicant': $('#educationbackground-applicant').val(), 'study_level': $('#educationbackground-study_level').val(), 'since': true, 'value': $('#educationbackground-since').val()},
                    function (yrs) {
                        $('#educationbackground-since').html(yrs)
                    }
                );
                
                $.post('$pre' + 'educ-since-till', {'applicant': $('#educationbackground-applicant').val(), 'study_level': $('#educationbackground-study_level').val(), 'value': $('#educationbackground-till').val()},
                    function (yrs) {
                        $('#educationbackground-till').html(yrs)
                    }
                );
            }
            
            function hideNewEducationButton() {
                ((id = $('#educationbackground-id').val()) === null || id === '') && ((lvl = $('#educationbackground-study_level').val()) === '$study_level_primary' || lvl === '$study_level_secondary') ? $('.bcg-new').hide() : $('.bcg-new').show();
            }
            
            function highlightSelectedTab(id, lvl) {
                btn = id * 1 > 0 ? ($('.bcg-' + id)) : (lvl === '$study_level_primary' || lvl === '$study_level_secondary' ? $('[lvl=' + lvl + ']') : $('.bcg-new'));
                btn.removeClass('btn-primary').addClass('btn-success');
            }
            
            function loadEducation(id, lvl) {
                $('#id').val(id);
                $('#study_level').val(lvl);
                '$pre' === '' || '$pre' === null ? $('#form-edcn-det-btn').submit() : dynamicEducation();
            }

        "
        , yii\web\View::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            /* hide or show the new education button conditionally */
                hideNewEducationButton();
            /* hide or show the new education button conditionally */

            /* highlighted the active tab */
                highlightSelectedTab($('#educationbackground-id').val(), $('#educationbackground-study_level').val());
            /* highlighted the active tab */

            /* buttons used to load education background */
                $('.edcn-bcg').click(
                    function () {
                        loadEducation($(this).attr('edctn'), $(this).attr('lvl'));
                    }
                );
            /* buttons used to load education background */

            /* there are a few dynamic about change of study level */
                $('#educationbackground-study_level').change(
                    function () {
                        studyLevelChanged();
                    }
                );
            /* there are a few dynamic about change of study level */

            /* dynamically recalculate the grade */
                $('#educationbackground-score, #educationbackground-out_of').change(
                    function () {
                        grade();
                    }
                );
            /* dynamically recalculate the grade */

            /* keep sponsorship reason in check */
                $('#educationbackground-sponsored').change(
                    function () {
                        $('#educationbackground-sponsorship_reason').blur();
                    }
                );
            /* keep sponsorship reason in check */

            /* the submit button is actually hidden */
                $('#edcn-btn').click(
                    function () {
                        $('#edcn-btn-inner').click();
                    }
                );
            /* the submit button is actually hidden */
            
            /* is saved */
               '$saved' ? dataSaved('Success', 'Education Background Saved', 'success') : '';
            /* is saved */
        "
        , yii\web\View::POS_READY
)
?>

<?php if (Yii::$app->request->isAjax): ?>

    <?php
    $this->registerJs(
            "
                function dynamicEducation() {
                    form = $('#form-edcn-det-btn');

                   $.post(form.attr('action'), form.serialize(),
                        function(frm) {
                            $('#yii-modal-cnt').html(frm);
                        }
                    );
                }
                
                function saveEducation() {
                    form = $('#form-edcn-det');
                    
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
                
                $('#edcn-btn-inner').click(
                    function(event) {
                        event.preventDefault();
                        
                        event.stopPropagation();
                        
                        saveEducation();
                    }
                );
            "
            , \yii\web\VIEW::POS_READY)
    ?>

<?php endif; ?>