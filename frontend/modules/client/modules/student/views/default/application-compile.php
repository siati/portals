<div class="tmln-cmpl-lst-div">

</div>

<div class="tmln-cmpl-btn-div">
    <div id="prt-aplctn-fm" class="btn btn-sm btn-primary" style="width: 100%">
        <b><i class="fa fa-print"></i> Compile and Print</b>
    </div>
</div>

<?php
$this->registerJs(
        "
            function amateurForm(aplcnt, aplctn, apl) {
                $.post('amateur-form', {'applicant': aplcnt, 'application': aplctn, 'appeal': apl},
                    function (res) {
                        fileDownload('../../../', res[0], res[1], 'Amateur Form');
                    }
                );
            }
        "
        , \yii\web\VIEW::POS_HEAD
)
?>

<?php
$this->registerJs(
        "
            /* print application */
                $('#prt-aplctn-fm').click(
                    function() {
                        amateurForm('$applicant', '$application', '$appeal');
                    }
                );
            /* print application */
        "
        , \yii\web\VIEW::POS_READY
)
?>