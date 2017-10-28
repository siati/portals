<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $sections array */
/* @var $application integer */

use frontend\modules\client\modules\student\models\ApplicantProductAccessCheckers;
use frontend\modules\business\models\ProductAccessProperties;
use frontend\modules\business\models\ProductAccessPropertyItems;
?>

<?php $comma = ProductAccessPropertyItems::comma ?>

<div class="full-dim-vtcl-scrl" style="border-bottom: 3px solid #ddd">

    <div class="sctns-lst pull-left">
        <ol style="width: 100%; padding: 0">
            <?php foreach ($sections as $section => $detail): ?>

                <?php isset($selectedSection) ? '' : $selectedSection = $section ?>

                <?php
                if ($section == $selectedSection) {
                    $bcg = '#31b0d5';
                    $clr = '#fff';
                } else {
                    $bcg = 'inherit';
                    $clr = '#333';
                }
                ?>

                <li class="ld-stng-sctn kasa-pointa" sctn="<?= $section ?>" style="padding: 0 3px; background-color: <?= $bcg ?>; color: <?= $clr ?>"><small><b>&bullet; <span><?= $detail[ApplicantProductAccessCheckers::section_name] ?></span></b></small></li>
            <?php endforeach; ?>
        </ol>
    </div>

    <div class="sctn-dtl pull-right">
        <div id="sctn-nm" class="sctn-dtl-hdr">
            <div class="gnrl-frm-divider" style="margin: 0 5px"><?= $sections[ApplicantProductAccessCheckers::section_applicant][ApplicantProductAccessCheckers::section_name] ?></div>
        </div>

        <div class="sctn-dtl-bdy">
            <div class="sctn-dtl-bdy-itm-lst pull-left">
                <ol id="sctn-itm-nms" style="width: 100%; padding: 0">

                    <?php foreach ($sections as $section => $detail): ?>

                        <?php foreach ($detail[ApplicantProductAccessCheckers::section_items] as $i => $item): ?>

                            <?php $model = ProductAccessProperties::propertyToLoad(null, $item[ApplicantProductAccessCheckers::property], $item[ApplicantProductAccessCheckers::table], $item[ApplicantProductAccessCheckers::column], $item[ApplicantProductAccessCheckers::model], $item[ApplicantProductAccessCheckers::attribute]) ?>

                            <?php
                            if ($model->isNewRecord) {
                                $model->name = $item[ApplicantProductAccessCheckers::name];
                                $model->operation = $item[ApplicantProductAccessCheckers::operation];
                                $model->active = ProductAccessProperties::active;
                            }
                            ?>

                            <?php if ($section == $selectedSection && !isset($selectedItem)): ?>
                                <?php $selectedItem = $i ?>
                                <?php $selectedItemModel = $model ?>
                            <?php endif; ?>

                            <?php $models[$section][$i] = $model ?>

                            <?= $this->render('property-form', ['model' => $model, 'section' => $section, 'selectedSection' => $selectedSection, 'i' => $i, 'selectedItem' => $selectedItem]) ?>

                        <?php endforeach; ?>

                    <?php endforeach; ?>

                </ol>

            </div>

            <div class="sctn-dtl-bdy-itm-frms pull-right" id="itms-lst">
                <?= $this->render('property-item-form', ['sections' => $sections, 'application' => $application, 'properties' => $models]) ?>
            </div>
        </div>
    </div>
</div>



<?php
$this->registerJs(
        "
            function saveAccessProperty(field) {

                post = $('#' + field.attr('form')).serializeArray();
                
                post.push({name: 'sbmt', value: field.attr('property')});

                $.post('save-access-property', post,
                    function(saved) {
                        saved[0] ?
                            customSwal('Success', 'Property Name Saved', '2500', 'success', false, true, 'ok', '#a5dc86', false, 'cancel') :
                            customSwal('Failed', 'Property Name Was Not Saved<br\><br\>Please make any changes and retry', '2500', 'error', false, true, 'ok', '#f27474', false, 'cancel');
                            
                        field.val(saved[1]);
                        
                        saved[0] ? $('#sctn-itm-nm-' + field.attr('item') + ' > .gnrl-frm-divider').text(saved[1]) : '';
                    }
                );
                
            }
            
            function saveAccessPropertyItem(frm) {
                
                post = $('#form-' + frm).serializeArray();
                
                post.push({name: 'sbmt', value: $('#form-' + frm).attr('property')});

                $.post('save-access-property-item', post,
                    function(saved) {
                        saved[0] ?
                            customSwal('Success', 'Property Item Saved', '2500', 'success', false, true, 'ok', '#a5dc86', false, 'cancel') :
                            customSwal('Failed', 'Property Item Was Not Saved<br\><br\>Please make any changes and retry', '2500', 'error', false, true, 'ok', '#f27474', false, 'cancel');
                    }
                );

            }
            
            function beforeSavePropertyItem(frm) {
                
                form = $('#form-' + frm);
                
                prpty = form.find('#productaccesspropertyitems-' + $('#form-' + frm).attr('property') + '-property');
                
                if (prpty.val() * 1 > 0)
                    saveAccessPropertyItem(frm);
                else {
                    field = $('#productaccessproperties-' + $('#form-' + frm).attr('parent_property') + '-name');
                    
                    post = $('#' + field.attr('form')).serializeArray();
                
                    post.push({name: 'sbmt', value: field.attr('property')});

                    $.post('save-access-property', post,
                        function(saved) {
                            field.val(saved[1]).blur();

                            if (saved[0] && saved[2] * 1 > 0) {
                                prpty.val(saved[2]);
                                saveAccessPropertyItem(frm);
                                $('#sctn-itm-nm-' + field.attr('item') + ' > .gnrl-frm-divider').text(saved[1])
                            } else
                                customSwal('Failed', 'Property Name Was Not Saved<br\><br\>Please make any changes on the Property Name then retry', '2500', 'error', false, true, 'ok', '#f27474', false, 'cancel');
                        }
                    );

                }
            }
            
            function selectedSection(section) {
                $('.ld-stng-sctn').css('background-color', 'inherit').css('color', '#333');
                
                section.css('background-color', '#31b0d5').css('color', '#fff');
                
                $('#sctn-nm > .gnrl-frm-divider').text(section.find('span').text());
                
                $('.ld-stng-sctn-itm').attr('hidden', 'hidden');
                
                $('[prnt-sctn=' + section.attr('sctn') + ']').attr('hidden', null);

                clicked = false;

                $('[prnt-sctn=' + section.attr('sctn') + ']').each(
                    function () {
                        if (!clicked) {
                            $(this).find('[stng-fld=property]').click();
                            clicked = true;
                        }
                    }
                );
            }
            
            function updateSelectedItems(idx) {
                val = null;
            
                $('#sctn-itm-fm-' + idx + ' .slctd-itm').each(
                    function() {
                       val = val === null ? ($(this).attr('val')) : (val + ',' + $(this).attr('val'));
                    }
                );
                
                $('#productaccesspropertyitems-' + idx + '-specific_values').val(val).blur();
                
                $('#productaccesspropertyitems-' + idx + '-min_value, #productaccesspropertyitems-' + idx + '-max_value').blur();
            }
            
            function valueIsContained(min_value, max_value, value_array, value) {
                start = value + '$comma';
        
                middle = '$comma' + value + '$comma';

                end = '$comma' + value;
                    
                return value !== null && value != '' &&
                        (
                            (min_value !== null && min_value !== '' && max_value !== null && max_value != '' && ($.isNumeric(value) ? value * 1 >= min_value * 1 && value * 1 <= max_value * 1 : value >= min_value && value <= max_value)) ||
                            (min_value !== null && min_value !== '' && (max_value == null || max_value == '') && ($.isNumeric(value) ? value * 1 >= min_value * 1 : value >= min_value)) ||
                            (max_value !== null && max_value !== '' && (min_value == null || min_value == '') && ($.isNumeric(value) ? value * 1 <= max_value * 1 : value <= max_value)) ||
                            (
                                value_array !== null && value_array !== '' && value_array.replace('$comma', '') !== '' &&
                                (
                                    value === value_array ||
                                    (value_array.toLowerCase().indexOf(start.toLowerCase()) === 0) ||
                                    (value_array.toLowerCase().indexOf(middle.toLowerCase()) > -1) ||
                                    (value_array.length >= end.length && value_array.toLowerCase().indexOf(end.toLowerCase()) === value_array.length - end.length)
                                )
                            )
                        )
                ;
            }
            
            function rulesChanged(idx) {
                $('#sctn-itm-fm-' + idx + ' .slctd-itm').removeClass('slctd-itm');
                
                $('#sctn-itm-fm-' + idx + ' [idx=' + idx + ']').each(
                    function() {
                        valueIsContained($('#productaccesspropertyitems-' + idx + '-min_value').val(), $('#productaccesspropertyitems-' + idx + '-max_value').val(), $('#productaccesspropertyitems-' + idx + '-specific_values').val(), $(this).attr('val')) ?
                        $(this).addClass('slctd-itm') : $(this).removeClass('slctd-itm');
                    }
                );
            }
            
            function selectedItem(field) {
                $('[stng-fld=property]').css('background-color', 'inherit').css('color', '#555');
                
                field.css('background-color', '#31b0d5').css('color', '#fff');
                
                $('#itms-lst > div').hide();
                
                $('#itm-' + field.attr('item')).show();
            }

        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            /* save access property */
                $('[stng-fld=property]').change(
                    function () {
                        saveAccessProperty($(this));
                    }
                );
            /* save access property */
            
            /* select section */
                $('.ld-stng-sctn').click(
                    function () {
                        selectedSection($(this));
                    }
                );
            /* select section */
            
            /* select access property */
                $('[stng-fld=property]').click(
                    function () {
                        selectedItem($(this));
                    }
                );

                /* pre-selected property */
                    $('#productaccessproperties-$selectedSection-name').click();
                /* pre-selected property */
            /* select access property */
            
            /* select a list item into value array */
                $('.clck-itms').click(
                    function () {
                        $(this).hasClass('slctd-itm') ? $(this).removeClass('slctd-itm') : $(this).addClass('slctd-itm');
                        updateSelectedItems($(this).attr('idx'));
                    }
                );
            /* select a list item into value array */
            
            /* rules changed associated with list items */
                $('[rspnsv=rspnsv]').change(
                    function() {
                        rulesChanged($(this).attr('sctn-i'));
                    }
                );
            /* rules changed associated with list items */
            
            /* blurs for concurrent validation */
                $('[crvld=crvld]').change(
                    function() {
                        $('#productaccesspropertyitems-' + $(this).attr('sctn-i') + '-min_value, #productaccesspropertyitems-' + $(this).attr('sctn-i') + '-max_value, #productaccesspropertyitems-' + $(this).attr('sctn-i') + '-specific_values').blur();
                    }
                );
            /* blurs for concurrent validation */
            
            /* save access item */
                $('.prpty-itm-sv-btn').click(
                    function () {
                        beforeSavePropertyItem($(this).attr('form'));
                    }
                );
            /* save access item */
        "
        , \yii\web\VIEW::POS_READY
)
?>