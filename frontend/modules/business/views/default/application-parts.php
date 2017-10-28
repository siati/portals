<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $parts array */
/* @var $application integer */

use frontend\modules\business\models\ApplicationParts;
?>

<div class="full-dim-vtcl-scrl" style="border-bottom: 3px solid #ddd">

    <div class="prts-lst pull-left">
        <ol style="width: 100%; padding: 0">
            <?php foreach ($parts as $part => $detail): ?>

                <?php $model = ApplicationParts::partToLoad(null, $application, $part) ?>

                <?php $model->isNewRecord ? $model->attributes = $detail : ''; ?>

                <?php $models[$part] = $model ?>

                <?php isset($selectedPart) ? '' : $selectedPart = $part ?>

                <?php
                if ($part == $selectedPart) {
                    $bcg = '#31b0d5';
                    $clr = '#fff';
                } else {
                    $bcg = 'inherit';
                    $clr = '#333';
                }
                ?>

                <li class="ld-aplctn-prt kasa-pointa" prt="<?= $part ?>" style="padding: 0 3px; background-color: <?= $bcg ?>; color: <?= $clr ?>"><small><b>&bullet; <span><?= $model->title ?></span></b></small></li>
                        <?php endforeach; ?>
        </ol>
    </div>

    <?= $this->render('application-parts-detail', ['parts' => $parts, 'models' => $models, 'selectedPart' => $selectedPart]) ?>

</div>


<?php
$this->registerJs(
        "
            function saveApplicationPart(prt) {
                post = $('#form-prt-' + prt).serializeArray();

                post.push({name: 'sbmt', value: prt});
                
                $.post('save-application-part', post,
                    function(saved) {
                        if (saved[0]) {
                            customSwal('Success', 'Application Part Saved', '2500', 'success', false, true, 'ok', '#a5dc86', false, 'cancel');
                            
                            $('[fld-prt-stng-prt=' + prt + '], #applicationparts-' + prt + '-title').val(saved[1]);
                            
                            $('.prts-lst ol [prt=' + prt + '] span').text(saved[1]);
                            
                            $('#applicationparts-' + prt + '-id').val() !== saved[2] ? $('#applicationparts-' + prt + '-id').val(saved[2]) : '';
                        } else
                            customSwal('Failed', 'Application Part Was Not Saved<br\><br\>Please make any changes and retry', '2500', 'error', false, true, 'ok', '#f27474', false, 'cancel');
                    }
                );
            }
            
            function saveApplicationPartElement(prt_elmnt) {
                post = $('#form-prt-elmnt-' + prt_elmnt).serializeArray();

                post.push({name: 'sbmt', value: prt_elmnt});
                
                $.post('save-application-part-element', post,
                    function(saved) {
                        if (saved[0]) {
                            customSwal('Success', 'Application Part Element Saved', '2500', 'success', false, true, 'ok', '#a5dc86', false, 'cancel');
                            
                            $('[fld-prt-elmnt-stng-elmnt=' + prt_elmnt + '], #applicationpartelements-' + prt_elmnt + '-title').val(saved[1]);
                            
                            $('.prts-dtl .prts-dtl-bdy .prts-dtl-itms-fm-elm .prts-dtl-itms-fm-elm-lst ol [prtelmnt=' + prt_elmnt + '] span').text(saved[1]);
                            
                            $('#applicationpartelements-' + prt_elmnt + '-id').val() !== saved[2] ? $('#applicationpartelements-' + prt_elmnt + '-id').val(saved[2]) : '';
                        } else
                            customSwal('Failed', 'Application Part Element Was Not Saved<br\><br\>Please make any changes and retry', '2500', 'error', false, true, 'ok', '#f27474', false, 'cancel');
                    }
                );
            }
            
            function initializeSaveApplicationPartElement(prt_elmnt) {
                form =  $('#form-prt-elmnt-' + prt_elmnt);
                
                part = $('#applicationpartelements-' + prt_elmnt + '-part');
                
                if (part.val() * 1 > 0)
                    saveApplicationPartElement(prt_elmnt);
                else {
                    prt = form.attr('part');
                    
                    post = $('#form-prt-' + prt).serializeArray();

                    post.push({name: 'sbmt', value: prt});

                    $.post('save-application-part', post,
                        function(saved) {
                            if (saved[0] && saved[2] * 1 > 0) {
                                $('[fld-prt-stng-prt=' + prt + '], #applicationparts-' + prt + '-title').val(saved[1]);

                                $('.prts-lst ol [prt=' + prt + '] span').text(saved[1]);

                                $('#applicationparts-' + prt + '-id').val() !== saved[2] ? $('#applicationparts-' + prt + '-id').val(saved[2]) : '';

                                part.val(saved[2]);
                                
                                saveApplicationPartElement(prt_elmnt);
                            } else
                                customSwal('Failed', 'Application Part Was Not Saved<br\><br\>Please make any changes on the Part and retry', '2500', 'error', false, true, 'ok', '#f27474', false, 'cancel');
                        }
                    );
                }
            }
            
            function selectedPart(part) {
                $('.ld-aplctn-prt').css('background-color', 'inherit').css('color', '#333');
                
                part.css('background-color', '#31b0d5').css('color', '#fff');
                
                $('.prts-dtl').attr('hidden', 'hidden');
                
                $('.prts-dtl-' + part.attr('prt')).attr('hidden', null);

                if ($('.prts-dtl-' + part.attr('prt') + ' > .prts-dtl-bdy > .prts-dtl-itms-fm-elm').length) {

                    clicked = false;

                    $('.prts-dtl-' + part.attr('prt') + ' > .prts-dtl-bdy > .prts-dtl-itms-fm-elm > .prts-dtl-itms-fm-elm-lst > ol').find('li').each(
                        function () {
                            if (!clicked) {
                                $(this).click();
                                clicked = true;
                            }
                        }
                    );
                }
            }
            
            function selectedElement(elmnt) {
                $('.ld-aplctn-prt-elmnt').css('background-color', 'inherit').css('color', '#333');
                
                elmnt.css('background-color', '#31b0d5').css('color', '#fff');
                
                $('.prts-dtl-itms-fm-elm-fm').attr('hidden', 'hidden');
                
                $('.ld-aplctn-prt-elmnt-' + elmnt.attr('prt-elmnt')).attr('hidden', null);
            }

        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            /* select part */
                $('.ld-aplctn-prt').click(
                    function () {
                        selectedPart($(this));
                    }
                );
            /* select part */
            
            /* select element */
                $('.ld-aplctn-prt-elmnt').click(
                    function () {
                        selectedElement($(this));
                    }
                );
            /* select element */
            
            /* rename part */
                $('[fld-prt-stng=part]').change(
                    function () {
                        $('#applicationparts-' + $(this).attr('part') + '-title').val($(this).val());
                    }
                );
            /* rename part */
            
            /* save application part */
                $('.aplctn-prt-sv').click(
                    function () {
                        saveApplicationPart($(this).attr('prt'))
                    }
                );
            /* save application part */
            
            /* rename element */
                $('[fld-prt-elmnt-stng=elmnt]').change(
                    function () {
                        $('#applicationpartelements-' + $(this).attr('part') + $(this).attr('elmnt') + '-title').val($(this).val());
                    }
                );
            /* rename element */
            
            /* save application part element */
                $('.aplctn-prt-elmnt-sv').click(
                    function () {
                        initializeSaveApplicationPartElement($(this).attr('prt-elmnt'))
                    }
                );
            /* save application part element */
        "
        , \yii\web\VIEW::POS_READY
)
?>