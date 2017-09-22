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

        <?php $homeUrl = Yii::$app->homeUrl ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => Yii::jrCompanyName(),
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top bcg-img-ctn',
                    'style' => "margin-bottom: 0; background-image: url($homeUrl../../common/assets/logos/kakamega.gif)"
                ],
            ]);

            $user_id = Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->id;

            $vals = empty($user_id) ? '' : "<input name='Applicants[id]' type='hidden' value='$user_id'><input name='User[id]' type='hidden' value='$user_id'>";

            $menuItems[] = [
                'label' => Yii::$app->user->isGuest ? 'You\'re Guest' : 'Welcome ' . Yii::$app->user->identity->username . ',',
                'items' => Yii::$app->user->isGuest ? [
            NavBar::linkHelper('ste-hm', '/site/index', 'post', '', 'Home', 'mn-hglght'),
            '<li class="divider"></li>',
            NavBar::linkHelper('sgn-in', '/site/login', 'post', '', 'Sign In', 'mn-hglght'),
            NavBar::linkHelper('sgn-up', '/client/student/default/register', 'post', '', 'Register', 'mn-hglght'),
            '<li class="divider"></li>',
            NavBar::linkHelper('psw-rd', '/site/request-password-reset', 'post', '', 'Password Reset', 'mn-hglght'),
                ] : [
            NavBar::linkHelper('ste-hm', '/site/index', 'post', '', 'Home', 'mn-hglght'),
            '<li class="divider"></li>',
            NavBar::linkHelper('sgn-up', '/client/student/default/register', 'post', $vals, 'Personal Details', 'mn-hglght'),
            NavBar::linkHelper('prt-dt', '/client/student/default/education', 'post', "<input name='EducationBackground[applicant]' type='hidden' value='$user_id'>", 'Education Background', 'mn-hglght'),
            NavBar::linkHelper('prt-dt', '/client/student/default/parents', 'post', "<input name='Applicants[id]' type='hidden' value='$user_id'>", 'Parent\'s Details', 'mn-hglght'),
            '<li class="divider"></li>',
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
                    <?= $content ?>
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

        <?php $this->registerJs("$('.wrap > .container').css('height', (hgt = $(window).height() - $('body .footer').height() - $('body > .wrap > .navbar').height() + 35) + 'px').css('padding-top', $('body > .wrap > .navbar').height() + 'px')", \yii\web\VIEW::POS_READY) ?>

        <?php $this->registerJs("$('.fit-in-pn').css('max-height', $('.page-content').height() * 0.85 + 'px')", \yii\web\VIEW::POS_READY) ?>

    </body>
</html>
<?php $this->endPage() ?>
