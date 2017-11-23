<?php
/* @var $this yii\web\View */
/* @var $user \common\models\User */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */

use frontend\modules\business\models\Products;

$this->title = 'Our Products';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $products = Products::allProducts() ?>

<?php $count = count($products) ?>

<?php $columns = 4 ?>

<?php $effective_columns = $count > $columns ? $columns : $count ?>

<?php $td_width = 100 / $effective_columns ?>

<?php $i = 0 ?>

<div class="gnrl-frm stdt-rsdc">

    <div class="gnrl-frm-cont">

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-ftr">

            <strong>The following are our loan and scholarship products. Click on any to see if you're eligible</strong>

        </div>

        <div class="gnrl-frm-bdy fit-in-pn" style="height: 85%; padding: 0">

            <div class="full-dim-vtcl-scrl prdcts-prnt-div">
                <table style="margin: 5px 0">
                    <?php foreach ($products as $product): ?>

                        <?php if (++$i % $effective_columns == 1): ?>
                            <tr>
                            <?php endif; ?>

                            <td style="width: <?= $td_width ?>px; padding: 7.5px">
                                <div class="my-loans-tile-cvr">
                                    <div class="my-loans-tile my-ln-sqr-tl" prdct-id="<?= $product->id ?>" prdct-nm="<?= $product->name ?>">

                                        <?= $product->name ?>

                                        <?php if (!empty($product->description)): ?>

                                            <br/>

                                            <pre><small><i><?= $product->description ?></i></small></pre>
                                        <?php else: ?>

                                            <br/>

                                            <br/>

                                        <?php endif; ?>

                                        <small>Click to Proceed</small>

                                    </div>
                                </div>
                            </td>

                            <?php if ($i % $effective_columns == 0 || $i == $count): ?>
                            </tr>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </table>
            </div>

        </div>

    </div>
</div>

<?php
$this->registerJs(
        "
            function productTimelineForApplicant(prdct_nm, prdct_id, aplcnt) {
                yiiModal(prdct_nm, 'application-timeline', {'product': prdct_id, 'applicant': aplcnt}, $(window).width() * 0.85, $('.gnrl-frm').height());
            }
        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            /* the tiles should be square */
                $('.my-ln-sqr-tl').bind('centerTiles', 
                    function () {
                        td_width = $('.prdcts-prnt-div').width() / $effective_columns;
                            
                        $(this).parent().css('width', td_width * 0.9 + 'px').css('height', td_width * 0.9 + 'px');
                            
                        $(this).parent().parent().css('padding-left', (td_width - $(this).parent().width()) / 2 + 'px');
                    }
                );
                
                $('.my-ln-sqr-tl').trigger('centerTiles');
            /* the tiles should be square */
            
            /* load product timeline for applicant */
                $('.my-loans-tile').click(
                    function () {
                        productTimelineForApplicant($(this).attr('prdct-nm'), $(this).attr('prdct-id'), $applicant->id);
                    }
                );
            /* load product timeline for applicant */
        "
        , \yii\web\VIEW::POS_READY
)
?>