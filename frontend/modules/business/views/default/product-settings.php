<?php
/* @var $this yii\web\View */
/* @var $product \frontend\modules\business\models\Products */
/* @var $opening \frontend\modules\business\models\ProductOpening */
/* @var $settings \frontend\modules\business\models\ProductOpeningSettings */

$this->title = 'Product Settings';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $subsequent_name = \frontend\modules\business\models\ProductSettings::has_subsequent ?>
<?php $subsequent_value = common\models\LmBaseEnums::applicantType(common\models\LmBaseEnums::applicant_type_subsequent)->VALUE ?>

<div class="gnrl-frm prdct-stng">

    <div class="gnrl-frm-cont">

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy gnrl-frm-pdg-top-0 gnrl-frm-pdg-rgt-0 gnrl-frm-pdg-btm-0 fit-in-pn"  style="height: 85%">
            <div class="prdcts-tab-pn pull-left">
                <ol style="width: 100%; padding: 0">
                    <li class="ld-prdct kasa-pointa" prd-id="" title="Create New Product" style="padding: 0 3px"><small><b>&bullet; Create New Product</b></small></li>

                    <?php foreach (\frontend\modules\business\models\Products::allProducts() as $prod_on_list): ?>
                        <li class="ld-prdct kasa-pointa" prd-id="<?= $prod_on_list->id ?>" title="<?= $prod_on_list->name ?>" style="padding: 0 3px"><small><b>&bullet; <?= substr($prod_on_list->name, 0, 22) ?></b></small></li>
                    <?php endforeach; ?>
                </ol>
            </div>

            <div class="prdcts-frm-pn pull-right">
                <?= $this->render('product', ['product' => $product]) ?>

                <?= $this->render('opening', ['opening' => $opening]) ?>

                <?= $this->render('settings', ['settings' => $settings]) ?>
            </div>



        </div>

        <div class="gnrl-frm-ftr"></div>

    </div>
</div>

<?php
$this->registerJs(
        "
            function loadProduct(id) {
                $('#sd-nav-prdcts > [value=\"\"]').val(id);

                $('#sd-nav-prdcts > button').click();
            }
            
            function saveProduct() {
                post = $('#form-prdct-nm').serializeArray();
                
                post.push({name: 'sbmt', value: ''});
                
                $.post('save-product', post,
                    function (saved) {
                        if (saved[0]) {
                            customSwal('Success', 'Product Saved', '2500', 'success', false, true, 'ok', '#a5dc86', false, 'cancel');
                            $('#products-id').val() * 1 === saved[1] * 1 ? '' : $('#products-id').val(saved[1]);
                            $('#prdct-sv-btn').click();
                        } else
                            customSwal('Failed', 'Product Was Not Saved<br\><br\>Make any corrections and retry', '2500', 'error', false, true, 'ok', '#f27474', false, 'cancel');
                    }
                );
            }
            
            function saveOpening() {
                if ($('#products-id').val() * 1 > 0) {
                    post = $('#form-prdct-opng').serializeArray();

                    post.push({name: 'ProductOpening[product]', value: $('#products-id').val()}, {name: 'sbmt', value: ''});

                    $.post('save-opening', post,
                        function(saveds) {
                            saveds[0] ?
                                customSwal('Success', 'Application Dates Saved', '2500', 'success', false, true, 'ok', '#a5dc86', false, 'cancel') :
                                customSwal('Failed', 'Application Dates Were Not Saved<br\><br\>Please make any changes and retry', '2500', 'error', false, true, 'ok', '#f27474', false, 'cancel');
                        }
                    );
                } else
                    customSwal('Declined', 'The new product above must first be saved', '2500', 'error', false, true, 'ok', '#f27474', false, 'cancel');
            }
            
            function saveSettings() {
                $.post('opening-i-d', {'product': $('#products-id').val(), 'academic_year': $('#productopening-academic_year').val(), 'subsequent': $('#productopening-subsequent').val()},
                    function(id) {
                        if (id * 1 > 0) {
                            post = $('#form-prdct-stng').serializeArray();
                            
                            post.push({name: 'ProductOpeningSettings[application]', value: id}, {name: 'sbmt', value: ''});
                            
                            $.post('save-settings', post,
                                function(saveds) {
                                    saveds[0] ?
                                        customSwal('Success', 'Application Settings Saved', '2500', 'success', false, true, 'ok', '#a5dc86', false, 'cancel') :
                                        customSwal('Failed', 'Application Settings Were Not Saved<br\><br\>Please seek assistance from the system administrator', '2500', 'error', false, true, 'ok', '#f27474', false, 'cancel');
                                }
                            );
                        } else
                            customSwal('Declined', 'The Application Dates above must first be saved', '2500', 'error', false, true, 'ok', '#f27474', false, 'cancel');
                    }
                );
            }
            
            function dynamicSettings() {
                $.post('dynamic-settings', {'product': $('#products-id').val(), 'academic_year': $('#productopening-academic_year').val(), 'subsequent': $('#productopening-subsequent').val()},
                    function (settings) {
                        $.each(settings,
                            function (attr, val) {
                                if ($('#' + attr).length) {
                                    $('#' + attr).val(val).blur();

                                    attr === 'productopeningsettings-$subsequent_name-value' ? $('#' + attr).attr('disabled', $('#productopening-subsequent').val() === '$subsequent_value') : '';
                                }
                            }
                        );
                    }
                );
            }
            
            function advancedAccessSettings() {
                $.post('opening-i-d', {'product': $('#products-id').val(), 'academic_year': $('#productopening-academic_year').val(), 'subsequent': $('#productopening-subsequent').val()},
                    function(id) {
                        id * 1 > 0 ?
                            yiiModal('Advanced Access Controls', 'access-checkers', {'application': id}, $(window).width() * 0.95, $('.gnrl-frm').height()) :
                            customSwal('Declined', 'The Application Dates below must first be saved', '2500', 'error', false, true, 'ok', '#f27474', false, 'cancel');
                    }
                );
            }
            
            function applicationParts(apl) {
                $.post('opening-i-d', {'product': $('#products-id').val(), 'academic_year': $('#productopening-academic_year').val(), 'subsequent': $('#productopening-subsequent').val()},
                    function(id) {
                        id * 1 > 0 ?
                            yiiModal('Application Parts and Elements', 'application-parts', {'application': id, 'appeal': apl}, $(window).width() * 0.95, $('.gnrl-frm').height()) :
                            customSwal('Declined', 'The Application Dates below must first be saved', '2500', 'error', false, true, 'ok', '#f27474', false, 'cancel');
                    }
                );
            }
            
            function disableSubsequent() {
                $('#productopeningsettings-$subsequent_name-value').attr('disabled', $('#productopening-subsequent').val() === '$subsequent_value');
            }
            
            function highlightProduct() {
                $('[prd-id=' + ($('#products-id').val() * 1 > 0 ? $('#products-id').val() : '\"\"') + ']').css('background-color', '#a5dc86');
            }

        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            /* highlight active product */
                highlightProduct();
            /* highlight active product */
            
            /* load desired product */
                $('.ld-prdct').click(
                    function () {
                        loadProduct($(this).attr('prd-id'));
                    }
                );
            /* load desired product */
            
            /* save product details only */
                $('#prdct-sv').click(
                    function () {
                        saveProduct();
                    }
                );
            /* save product details only */
            
            /* save product opening only */
                $('#opng-sv').click(
                    function () {
                        saveOpening();
                    }
                );
            /* save product opening only */
            
            /* save product opening settings only */
                $('#stngs-sv').click(
                    function () {
                        saveSettings();
                    }
                );
            /* save product opening settings only */
            
            /* dynamic settings */
                $('#productopening-academic_year, #productopening-subsequent').change(
                    function () {
                        dynamicSettings();
                    }
                );
            /* dynamic settings */
            
            /* whether to disable subsequent setting */
                disableSubsequent();
            /* whether to disable subsequent setting */
            
            /* blurs for concurrent validation */
                $('#productopening-consider_counts').change(
                    function () {
                        $('#productopening-grace, #productopening-min_apps, #productopening-max_apps').blur();
                    }
                );
                
                $('#productopening-min_apps, #productopening-max_apps').change(
                    function () {
                        $('#productopening-min_apps, #productopening-max_apps').blur();
                    }
                );
                
                $('#productopening-since, #productopening-till, #productopening-grace').change(
                    function () {
                        $('#productopening-since, #productopening-till, #productopening-grace').blur();
                    }
                );
                
                $('#productopening-since, #productopening-appeal_since, #productopening-appeal_till').change(
                    function () {
                        $('#productopening-appeal_since, #productopening-appeal_till').blur();
                    }
                );
            /* blurs for concurrent validation */
            
            /* advanced access settings */
                $('#advnc-stngs').click(
                    function () {
                        advancedAccessSettings();
                    }
                );
            /* advanced access settings */
            
            /* the main application parts */
                $('#prntg-stngs').click(
                    function () {
                        applicationParts(0);
                    }
                );
            /* the main application parts */
            
            /* the appeal application parts */
                $('#prntg-stngs-apl').click(
                    function () {
                        applicationParts(1);
                    }
                );
            /* the appeal application parts */
        "
        , \yii\web\VIEW::POS_READY
)
?>