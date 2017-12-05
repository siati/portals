<?php
/* @var $this yii\web\View */
/* @var $form \kartik\form\ActiveForm */
/* @var $compilation array */
/* @var $applicant integer */
/* @var $application integer */
/* @var $appeal boolean */
/* @var $print boolean */
?>

<div class="tmln-cmpl-lst-div">

    <?php if (empty($compilation)): ?>

        <?php $name = Yii::$app->user->identity->username ?>

        <?= $this->render('../../../../../../views/site/no-content', ['message' => "<p>Well, $name,</p><p>Looks like you've duly completed filling in your details as required</p><p>You may proceed to</p><div id='prt-aplctn-fm' class='btn btn-lg btn-success'><small><b><i class='fa fa-print'></i> Print The Application</b></small></div><br/><br/><p>whenever you're ready</p>"]) ?>

    <?php else: ?>

        <?= $this->render('compilation-list', ['compilations' => $compilation]) ?>

    <?php endif; ?>

</div>

<div class="tmln-cmpl-btn-div">
    <div id="cpl-aplctn-fm" class="btn btn-sm btn-primary" style="width: 100%">
        <b><i class='fa fa-search-plus'></i> Compile Application</b>
    </div>
</div>

<?php
$this->registerJs(
        "
            /* compile application */
                $('#cpl-aplctn-fm').click(
                    function() {
                        applicationCompile('$applicant', '$application', '$appeal', 1);
                    }
                );
            /* compile application */
            
            /* print application */
                $('#prt-aplctn-fm').click(
                    function() {
                        amateurForm('$applicant', '$application', '$appeal');
                    }
                );
                
                '$print' ? $('#prt-aplctn-fm').click() : '';
            /* print application */
        "
        , \yii\web\VIEW::POS_READY
)
?>