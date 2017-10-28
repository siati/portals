<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $parts array */
/* @var $models \frontend\modules\business\models\ApplicationParts */
/* @var $selectedPart string */

use kartik\form\ActiveForm;
use frontend\modules\business\models\ApplicationPartCheckers;
use frontend\modules\business\models\ApplicationParts;
use frontend\modules\business\models\ApplicationPartElements;
?>

<?php foreach ($parts as $part => $detail): ?>

    <div class="prts-dtl prts-dtl-<?= $part ?> pull-right"<?php if ($part != $selectedPart): ?> hidden="hidden" <?php endif; ?>>
        <div class="prts-dtl-hdr">
            <?php $form = ActiveForm::begin(['id' => $uniq = uniqid(), 'enableAjaxValidation' => true, 'action' => 'save-application-part', 'fieldConfig' => ['options' => ['class' => 'form-group-sm']]]); ?>
            <?= $form->field($model = $models[$part], "[$uniq]title")->textInput(['fld-prt-stng' => 'part', 'part' => $part, 'maxlength' => true, 'style' => 'font-size: 16px; font-weight: bold; border: none; border-bottom: 3px solid #ddd; background-color: inherit'])->label(false)->error(['style' => 'text-align: right']) ?>
            <?php ActiveForm::end(); ?>
        </div>

        <div class="prts-dtl-bdy">
            <?php if (empty($detail[ApplicationPartCheckers::items])): ?>
                <div class="prts-dtl-no-itms-fm">
                    <?= $this->render('part-form', ['model' => $models[$part], 'count' => count($parts), 'has_items' => false]) ?>
                </div>
            <?php else: ?>
                <div class="prts-dtl-itms-fm pull-left">
                    <?= $this->render('part-form', ['model' => $models[$part], 'count' => count($parts), 'has_items' => true]) ?>
                </div>

                <div class="prts-dtl-itms-fm-elm pull-right">

                    <div class="prts-dtl-itms-fm-elm-lst pull-left">

                        <ol style="width: 100%; padding: 0">

                            <?php foreach ($detail[ApplicationPartCheckers::items] as $element => $sub_detail): ?>

                                <?php $sub_model = ApplicationPartElements::elementToLoad(null, $models[$part]->id, $element) ?>

                                <?php $sub_model->isNewRecord ? $sub_model->attributes = $sub_detail : ''; ?>

                                <?php $sub_models["$part$element"] = $sub_model ?>

                                <?php $part == $selectedPart && !isset($selectedElement) ? $selectedElement = $element : '' ?>

                                <?php
                                if ($part == $selectedPart && $selectedElement == $element) {
                                    $bcg = '#31b0d5';
                                    $clr = '#fff';
                                } else {
                                    $bcg = 'inherit';
                                    $clr = '#333';
                                }
                                ?>

                                <li class="ld-aplctn-prt-elmnt kasa-pointa"  prt-elmnt="<?= "$part-$element" ?>" style="padding: 0 3px; background-color: <?= $bcg ?>; color: <?= $clr ?>"><small><b>&bullet; <span><?= $sub_model->title ?></span></b></small></li>

                            <?php endforeach; ?>

                        </ol>

                    </div>

                    <?php foreach ($elements = $detail[ApplicationPartCheckers::items] as $element => $sub_detail): ?>
                        <div class="prts-dtl-itms-fm-elm-fm ld-aplctn-prt-elmnt-<?= "$part-$element" ?> pull-right" prt-elmnt="<?= "$part$element" ?>"<?php if ($part != $selectedPart || $selectedElement != $element): ?> hidden="hidden" <?php endif; ?>>
                            <div class="prts-dtl-itms-fm-elm-hdr">
                                <?php $form = ActiveForm::begin(['id' => uniqid(), 'enableAjaxValidation' => true, 'action' => 'save-application-part-element', 'fieldConfig' => ['options' => ['class' => 'form-group-sm']]]); ?>
                                <?= $form->field($sub_model = $sub_models["$part$element"], "[$part$element]title")->textInput(['part' => $part, 'elmnt' => $element, 'maxlength' => true, 'style' => 'font-size: 16px; font-weight: bold; border: none; border-bottom: 3px solid #ddd; background-color: inherit'])->label(false)->error(['style' => 'text-align: right']) ?>
                                <?php ActiveForm::end(); ?>
                            </div>

                            <div class="prts-dtl-itms-fm-elm-bdy">
                                <?= $this->render('part-element-form', ['model' => $sub_models["$part$element"], 'part' => $part, 'order' => $models[$part]->order_elements == ApplicationParts::order_elements_yes, 'count' => count($elements)]) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>