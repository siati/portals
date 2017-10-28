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
        "
        , \yii\web\VIEW::POS_READY
)
?>