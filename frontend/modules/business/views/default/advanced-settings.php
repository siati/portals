<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $sections array */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use frontend\modules\client\modules\student\models\ApplicantProductAccessCheckers;
use frontend\modules\business\models\ProductAccessProperties;
use frontend\modules\business\models\ProductAccessPropertyItems;
?>

<?php $comma = ProductAccessPropertyItems::comma ?>

<div class="full-dim-vtcl-scrl" style="border-bottom: 3px solid #ddd">

    <div class="full-dim-vtcl-scrl pull-left" style="width: 20%; height: 100%; padding-right: 3px; border-right: 2px solid #ddd">
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

    <div class="full-dim-vtcl-scrl pull-right" style="width: 80%; height: 100%">
        <div id="sctn-nm" style="width: 100%; height: 5%">
            <div class="gnrl-frm-divider" style="margin: 0 5px; bottom: 0"><?= $sections[ApplicantProductAccessCheckers::section_applicant][ApplicantProductAccessCheckers::section_name] ?></div>
        </div>

        <div style="width: 100%; height: 95%">
            <div class="pull-left" style="width: 25%; height: 100%; border-right: 2px solid #ddd; overflow-x: hidden">
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

                            <?php
                            if ($section == $selectedSection && $i == $selectedItem) {
                                $bcg = '#31b0d5';
                                $clr = '#fff';
                            } else {
                                $bcg = 'inherit';
                                $clr = '#555';
                            }
                            ?>

                            <?php $form = ActiveForm::begin(['id' => $form_id = "form-$section-$model->property", 'enableAjaxValidation' => true, 'action' => 'save-access-property', 'fieldConfig' => ['options' => ['class' => 'form-group-sm']]]); ?>

                            <li class="ld-stng-sctn-itm kasa-pointa" prnt-sctn="<?= $section ?>"<?php if ($section != $selectedSection): ?> hidden="hidden" <?php endif; ?>style="padding: 0 3px">
                                <?= Html::activeHiddenInput($model, "[$model->property]table") ?>
                                <?= Html::activeHiddenInput($model, "[$model->property]column") ?>
                                <?= Html::activeHiddenInput($model, "[$model->property]model_class") ?>
                                <?= Html::activeHiddenInput($model, "[$model->property]attribute") ?>
                                <?= Html::activeHiddenInput($model, "[$model->property]operation") ?>
                                <?= Html::activeHiddenInput($model, "[$model->property]active") ?>

                                <?=
                                $form->field($model, "[$model->property]name")->textInput(
                                        [
                                            'property' => $model->property,
                                            'form' => $form_id,
                                            'item' => "$section-$i",
                                            'maxlength' => true,
                                            'stng-fld' => 'property',
                                            'style' => "border: none; background-color: $bcg; color: $clr"
                                        ]
                                )->label(false)
                                ?>
                            </li>

                            <?php ActiveForm::end(); ?>

                        <?php endforeach; ?>

                    <?php endforeach; ?>

                </ol>

            </div>

            <div class="pull-right" id="itms-lst" style="width: 75%; height: 100%; overflow: hidden">

                <?php foreach ($sections as $section => $detail): ?>

                    <?php foreach ($detail[ApplicantProductAccessCheckers::section_items] as $i => $item): ?>

                        <?php $model = ProductAccessPropertyItems::itemToLoad(null, $application, $models[$section][$i]->id) ?>

                        <div id="itm-<?= "$section-$i" ?>" style="width: 100%; height: 100%; overflow: hidden">

                            <div id="sctn-itm-nm-<?= "$section-$i" ?>" style="width: 100%; height: 5%">
                                <div class="gnrl-frm-divider" style="margin: 0 5px; bottom: 0"><?= $models[$section][$i]->name ?></div>
                            </div>

                            <div id="sctn-itm-bdy-<?= "$section-$i" ?>" style="width: 100%; height: 95%; padding: 0 5px; overflow-x: hidden">

                                <?php $is_array = is_array($items = $item[ApplicantProductAccessCheckers::value_set]) ?>

                                <div id="sctn-itm-fm-<?= "$section$i" ?>" style="width: 100%; height: 100%">

                                    <div class="pull-left" style="width: 30%; height: 100%;<?php if (!$is_array): ?> display: table;<?php endif; ?> overflow: hidden; border-right: 2px solid #ddd">

                                        <?php if ($is_array): ?>

                                            <div class="full-dim-vtcl-scrl">
                                                <table>

                                                    <?php foreach ($items as $key => $value): ?>

                                                        <tr class="clck-itms<?= $model->valueIsIncluded($key) ? ' slctd-itm' : '' ?> kasa-pointa" val="<?= $key ?>" idx="<?= "$section$i" ?>"><td><?= $value ?></td></tr>

                                                    <?php endforeach; ?>

                                                </table>
                                            </div>

                                        <?php else: ?>

                                            <div class="full-dim-vtcl-scrl" style="display: table-cell; vertical-align: middle; text-align: center">
                                                <h4>No Items To Display</h4>
                                            </div>

                                        <?php endif; ?>

                                    </div>

                                    <div class="pull-right" style="width: 70%; height: 92.5%; padding: 5px 5px 5px 0; overflow-x: hidden; border-bottom: 2px solid #ddd">

                                        <?php $form = ActiveForm::begin(['id' => $form_id = "form-$section-$i", 'enableAjaxValidation' => true, 'action' => 'save-access-property-item', 'options' => ['property' => "$section$i", 'parent_property' => $models[$section][$i]->property], 'fieldConfig' => ['options' => ['class' => 'form-group-sm']]]); ?>

                                        <?= Html::activeHiddenInput($model, "[$section$i]application") ?>

                                        <?= Html::activeHiddenInput($model, "[$section$i]property") ?>
                                        
                                        <table>

                                            <?php if ($item[ApplicantProductAccessCheckers::type] == ApplicantProductAccessCheckers::type_date): ?>
                                                <tr>
                                                    <td class="td-pdg-lft">
                                                        <?=
                                                        $form->field($model, "[$section$i]min_value", ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar"></i>']]])->widget(DatePicker::className(), [
                                                            'options' => ['placeholder' => 'yyyy-mm-dd', 'sctn-i' => "$section$i", 'rspnsv' => $is_array ? 'rspnsv' : false, 'crvld' => 'crvld', 'maxlength' => true],
                                                            'type' => DatePicker::TYPE_INPUT,
                                                            'pluginOptions' => [
                                                                'autoclose' => true,
                                                                'format' => 'yyyy-mm-dd'
                                                            ]
                                                                ]
                                                        );
                                                        ?>
                                                    </td>
                                                    <td class="td-pdg-lft">
                                                        <?=
                                                        $form->field($model, "[$section$i]max_value", ['addon' => ['prepend' => ['content' => '<i class="fa fa-calendar"></i>']]])->widget(DatePicker::className(), [
                                                            'options' => ['placeholder' => 'yyyy-mm-dd', 'sctn-i' => "$section$i", 'rspnsv' => $is_array ? 'rspnsv' : false, 'crvld' => 'crvld', 'maxlength' => true],
                                                            'type' => DatePicker::TYPE_INPUT,
                                                            'pluginOptions' => [
                                                                'autoclose' => true,
                                                                'format' => 'yyyy-mm-dd'
                                                            ]
                                                                ]
                                                        );
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <tr>
                                                    <td class="td-pdg-lft"><?= $form->field($model, "[$section$i]min_value")->textInput(['sctn-i' => "$section$i", 'rspnsv' => $is_array ? 'rspnsv' : false, 'crvld' => 'crvld', 'maxlength' => true]) ?></td>
                                                    <td class="td-pdg-lft"><?= $form->field($model, "[$section$i]max_value")->textInput(['sctn-i' => "$section$i", 'rspnsv' => $is_array ? 'rspnsv' : false, 'crvld' => 'crvld', 'maxlength' => true]) ?></td>
                                                </tr>
                                            <?php endif; ?>


                                            <tr>
                                                <td class="td-pdg-lft"><?= $form->field($model, "[$section$i]active")->dropDownList(ProductAccessPropertyItems::actives(), ['crvld' => 'crvld', 'sctn-i' => "$section$i"]) ?></td>
                                                <td class="td-pdg-lft"><?= $form->field($model, "[$section$i]required")->dropDownList(ProductAccessPropertyItems::requireds()) ?></td>
                                            </tr>

                                            <tr><td class="td-pdg-lft" colspan="2"><?= $form->field($model, "[$section$i]remark")->textarea(['rows' => 2, 'style' => 'resize: none']) ?></td></tr>

                                            <tr><td class="td-pdg-lft" colspan="2"><?= $form->field($model, "[$section$i]specific_values")->textarea(['sctn-i' => "$section$i", 'rspnsv' => $is_array ? 'rspnsv' : false, 'crvld' => 'crvld', 'rows' => 24, 'style' => 'resize: none']) ?></td></tr>

                                        </table>

                                        <?php ActiveForm::end(); ?>

                                    </div>

                                    <div class="pull-right" id="sctn-itm-fm-btn-<?= "$section-$i" ?>" style="width: 70%; height: 7.5%; padding: 5px 0 2px 10px; overflow: hidden">
                                        <div class="btn btn-sm btn-primary pull-left prpty-itm-sv-btn" id="prpty-itm-sv-<?= "$section-$i" ?>" form="<?= "$section-$i" ?>"><b>Save</b></div>
                                        <div class="btn btn-sm btn-danger pull-right" onclick="closeDialog()"><b>Close</b></div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php endforeach; ?>
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