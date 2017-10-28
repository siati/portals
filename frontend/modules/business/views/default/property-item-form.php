<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $properties \frontend\modules\business\models\ProductAccessProperties */
/* @var $sections array */
/* @var $application integer */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use frontend\modules\client\modules\student\models\ApplicantProductAccessCheckers;
use frontend\modules\business\models\ProductAccessPropertyItems;
?>

<?php foreach ($sections as $section => $detail): ?>

    <?php foreach ($detail[ApplicantProductAccessCheckers::section_items] as $i => $item): ?>

        <?php $model = ProductAccessPropertyItems::itemToLoad(null, $application, $properties[$section][$i]->id) ?>

        <div id="itm-<?= "$section-$i" ?>" class="full-dim-vtcl-scrl" style="overflow: hidden">

            <div id="sctn-itm-nm-<?= "$section-$i" ?>" class="sctn-dtl-bdy-itm-frms-hdr">
                <div class="gnrl-frm-divider" style="margin: 0 5px"><?= $properties[$section][$i]->name ?></div>
            </div>

            <div id="sctn-itm-bdy-<?= "$section-$i" ?>" class="sctn-dtl-bdy-itm-frms-bdy">

                <?php $is_array = is_array($items = $item[ApplicantProductAccessCheckers::value_set]) ?>

                <div id="sctn-itm-fm-<?= "$section$i" ?>" class="full-dim-vtcl-scrl" style="overflow: hidden">

                    <div class="itms-drp-dwn-lst pull-left"<?php if (!$is_array): ?> style="display: table"<?php endif; ?>>

                        <?php if ($is_array): ?>

                            <div class="full-dim-vtcl-scrl">
                                <table>

                                    <?php foreach ($items as $key => $value): ?>

                                        <tr class="clck-itms<?= $model->valueIsIncluded($key) ? ' slctd-itm' : '' ?> kasa-pointa" val="<?= $key ?>" idx="<?= "$section$i" ?>"><td><?= $value ?></td></tr>

                                    <?php endforeach; ?>

                                </table>
                            </div>

                        <?php else: ?>

                            <div class="full-dim-vtcl-scrl no-itms">
                                <h4>No Items To Display</h4>
                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="itm-frms-mny pull-right">

                        <?php $form = ActiveForm::begin(['id' => $form_id = "form-$section-$i", 'enableAjaxValidation' => true, 'action' => 'save-access-property-item', 'options' => ['property' => "$section$i", 'parent_property' => $properties[$section][$i]->property], 'fieldConfig' => ['options' => ['class' => 'form-group-sm']]]); ?>

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

                    <div class="itm-frms-btns-mny pull-right" id="sctn-itm-fm-btn-<?= "$section-$i" ?>">
                        <div class="btn btn-sm btn-primary pull-left prpty-itm-sv-btn" id="prpty-itm-sv-<?= "$section-$i" ?>" form="<?= "$section-$i" ?>"><b>Save</b></div>
                        <div class="btn btn-sm btn-danger pull-right" onclick="closeDialog()"><b>Close</b></div>
                    </div>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

<?php endforeach; ?>