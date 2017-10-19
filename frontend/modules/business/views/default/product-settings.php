<?php
/* @var $this yii\web\View */
/* @var $product \frontend\modules\business\models\Products */
/* @var $opening \frontend\modules\business\models\ProductOpening */

use yii\bootstrap\ButtonDropdown;

$this->title = 'Product Settings';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$items[] = ['label' => 'New Product', 'url' => '#', 'options' => ['class' => 'btn btn-xs ld-prdct', 'prd-id' => '', 'style' => 'width: 100%; font-weight: bold; float: left; text-align: justify']];

foreach (\frontend\modules\business\models\Products::allProducts() as $prod_on_list)
    $items[] = ['label' => $prod_on_list->name, 'url' => '#', 'options' => ['class' => 'btn btn-xs ld-prdct', 'prd-id' => $prod_on_list->id, 'style' => 'width: 100%; font-weight: bold; float: left; text-align: justify']];

$buttonDrop = empty($items) ? '' :
        ButtonDropdown::widget([
            'label' => 'Click To See All Products',
            'containerOptions' => ['class' => 'btn btn-xs btn-success', 'style' => 'width: auto; padding: 0'],
            'options' => ['class' => 'btn btn-xs btn-success', 'style' => 'width: 100%; border: none; font-weight: bold'],
            'dropdown' => [
                'items' => $items
            ]
        ])
?>

<div class="gnrl-frm prdct-stng">

    <div class="gnrl-frm-cont">

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy fit-in-pn">

            <?= $buttonDrop ?>

            <?= $this->render('product', ['product' => $product]) ?>

            <?= $this->render('opening', ['opening' => $opening]) ?>

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
                post = $('#form-prdct-stng').serializeArray();
                
                post.push({name: 'sbmt', value: ''});
                
                $.post('save-product', post,
                    function (saved) {
                        if (saved[0]) {
                            $('#products-id').val() * 1 === saved[1] * 1 ? '' : $('#products-id').val(saved[1]);
                            $('#prdct-sv-btn').click();
                        }
                    }
                );
            }
            
            function saveOpening() {
                post = $('#form-prdct-opng').serializeArray();
                
                post.push({name: 'ProductOpening[product]', value: $('#products-id').val()}, {name: 'sbmt', value: ''});
                
                $.post('save-opening', post, function () {});
            }

        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            
            /* load desired product */
                $('.ld-prdct').click(
                    function (event) {
                        event.preventDefault();
                        
                        event.stopPropagation();
                        
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

        "
        , \yii\web\VIEW::POS_READY
)
?>