<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $user \common\models\User */
/* @var $applicant \frontend\modules\client\modules\student\models\Applicants */

use yii\helpers\Html;

$this->title = 'My Loan Applications';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="gnrl-frm stdt-rsdc">

    <div class="gnrl-frm-cont">

        <div class="gnrl-frm-hdr"><?= $this->title ?></div>

        <div class="gnrl-frm-bdy fit-in-pn" style="height: 85%; display: table">

            <div class="full-dim-vtcl-scrl" style="display: table-cell">
                <div class="my-loans-tile-cvr">
                    <div class="my-loans-tile my-ln-sqr-tl">YYYYYYYYYYYYYYYYYYYYYYYYYYYY</div>
                </div>
            </div>

        </div>

        <div class="gnrl-frm-ftr">

            <?= Html::submitButton('Go', ['class' => 'btn btn-primary pull-right', 'name' => 'my-loans-button']) ?>

        </div>

    </div>
</div>

<?php
$this->registerJs(
        "
            /* the tiles should be square */
                $('.my-ln-sqr-tl').bind('squareTile', 
                    function () {
                        $(this).css('height', $(this).width());
                    }
                );
                
                $('.my-ln-sqr-tl').trigger('squareTile');
            /* the tiles should be square */
        "
        , \yii\web\VIEW::POS_READY
)
?>