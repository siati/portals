<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <!-- sweet alerts begin -->
        <script type="text/javascript"><?php require Yii::$app->basePath . '\..\vendor\bower\sweetalerts\dist\sweetalert.min.js' ?></script>
        <link rel="stylesheet" type="text/css" href="<?= Yii::$app->homeUrl ?>../../vendor/bower/sweetalerts/dist/sweetalert.css">
        <!-- sweet alerts end -->

        <?php $this->registerLinkTag(['rel' => 'shortcut icon', 'type' => 'image/png', 'href' => Yii::$app->homeUrl . '../../common/assets/icons/johnrays.png']); ?>

    </head>
    <body>
        <?php $this->beginBody() ?>

        <?php Modal::begin(['header' => "<div style='font-size: 24px'><span class='yii-modal-head'><b>Yii Modal</b></span></div>", 'id' => 'yii-modal-pane', 'size' => 'modal-lg', 'clientOptions' => ['backdrop' => 'static', 'keyboard' => false], 'closeButton' => ['class' => 'btn btn-sm btn-danger pull-right', 'id' => 'the-modal-close', 'style' => 'font-weight: bold']]) ?>

        <div id="yii-modal-cnt"></div>

        <?php Modal::end() ?>

        <?php $user_id = Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->id; ?>

        <?php $applicant = Yii::$app->user->isGuest ? '' : \frontend\modules\client\modules\student\models\Applicants::returnApplicant($user_id); ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => Yii::jrCompanyName(),
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

            $menuItems[] = [
                'label' => Yii::$app->user->isGuest ? 'You\'re Guest' : 'Welcome ' . Yii::$app->user->identity->username . ',',
                'items' => Yii::$app->user->isGuest ? [
            NavBar::linkHelper('sgn-in', '/site/login', 'post', '', 'Sign In', 'mn-hglght'),
            NavBar::linkHelper('sgn-up', '/client/student/default/register', 'post', '', 'Register', 'mn-hglght'),
            '<li class="divider"></li>',
            NavBar::linkHelper('psw-rd', '/site/request-password-reset', 'post', '', 'Password Reset', 'mn-hglght'),
                ] : [
            NavBar::linkHelper('psw-rd', '/site/request-password-reset', 'post', '', 'Password Reset', 'mn-hglght'),
            '<li class="divider"></li>',
            NavBar::linkHelper('sgn-ot', '/site/logout', 'post', '', 'Sign Out', 'mn-hglght'),
                ],
            ];

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
            ?>

            <div class="container">
                <div class="page-content full-dim-vtcl-scrl">
                    <div class="page-content-lft bcg-img-cvr pull-left" style="background-image: url(<?= Yii::$app->homeUrl ?>../../common/assets/logos/background.jpg)">

                        <div class="logo-div bcg-img-ctn" style="background-image: url(<?= Yii::$app->homeUrl ?>../../common/assets/logos/helb-logo.jpg)">

                        </div>

                        <div class="menu-div">

                            <?=
                            Yii::$app->user->isGuest ?
                                    $this->render('guest') :
                                    $this->render('applicant-menu');
                            ?>
                        </div>

                    </div>

                    <div class="page-content-rgt bcg-img-cvr pull-right" style="background-image: url(<?= Yii::$app->homeUrl ?>../../common/assets/logos/helb-wallpaper.png)">
                        <?= $content ?>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left"><?= Yii::jrCompany() ?></p>

                <p class="pull-right"><?= Yii::jrPowered() ?></p>
            </div>
        </footer>

        <?php $this->endBody() ?>

        <?php $this->registerJs("function clickTheButton(btn) {btn.click()}", \yii\web\VIEW::POS_HEAD) ?>

        <?php
        $this->registerJs(
                "
                    $('.nav-pills > li > a').click(
                        function(event) {
                            if (!$(this).hasClass('kn-toggle')) {
                                event.preventDefault();
                                
                                event.stopPropagation();
                                
                                $(this).parent().hasClass('active') ? '' : clickTheButton($(this).find('button'));
                                
                                return false;
                            }
                        }
                    );
                    
                    $('.nav-pills > li > a button').click(
                        function(event) {
                            event.stopPropagation();
                            
                            return true;
                        }
                    );
                "
                , \yii\web\VIEW::POS_READY
        )
        ?>

        <?php $this->registerJs("$('.wrap > .container').css('height', (hgt = $(window).height() - $('body .footer').height() - $('body > .wrap > .navbar').height() + 35) + 'px').css('padding-top', $('body > .wrap > .navbar').height() + 'px')", \yii\web\VIEW::POS_READY) ?>

        <?php $this->registerJs("$('.fit-in-pn').css('max-height', $('.page-content-rgt').height() * 0.84 + 'px')", \yii\web\VIEW::POS_READY) ?>

        <?php $this->registerJs("$('.logo-div').css('width', $('.logo-div').height() + 'px').css('margin-left', ($('.logo-div').parent().width() - $('.logo-div').width()) / 2 + 'px')", \yii\web\VIEW::POS_READY) ?>

    </body>
</html>
<?php $this->endPage() ?>
