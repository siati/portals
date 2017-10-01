<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use kartik\sidenav\SideNav;
use frontend\assets\AppAsset;
use yii\helpers\Url;

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

                        <div class="logo-div bcg-img-ctn" style="background-image: url(<?= Yii::$app->homeUrl ?>../../common/assets/logos/kakamega.gif)">

                        </div>

                        <div class="menu-div">

                            <?php $requested_route = Yii::$app->requestedRoute ?>

                            <?=
                            SideNav::widget(
                                    [
                                        'type' => SideNav::TYPE_DEFAULT,
                                        'encodeLabels' => false,
                                        'heading' => Yii::jrCompanyName(),
                                        'items' => [
                                            ['label' => SideNav::linkHelper($route = '/site/index', 'post', [], 'Home'), 'icon' => 'home', 'url' => '#', 'active' => ($requested_route == substr($route, 1))],
                                            ['label' => '<span class="pull-right badge">3</span> Profile', 'icon' => 'user', 'items' => [
                                                    ['label' => SideNav::linkHelper($route = '/client/student/default/register', 'post', ['Applicants[id]' => $user_id, 'User[id]' => $user_id], 'Personal Details'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))],
                                                    ['label' => SideNav::linkHelper($route = '/client/student/default/residence', 'post', ['ApplicantsResidence[applicant]' => $user_id], 'Current Residence Details'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))],
                                                    ['label' => SideNav::linkHelper($route = '/client/student/default/education', 'post', ['EducationBackground[applicant]' => $user_id], 'Education Background'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))],
                                                    ['label' => SideNav::linkHelper($route = '/client/student/default/parents', 'post', ['Applicants[id]' => $user_id], 'Parents\' Details'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))],
                                                    ['label' => SideNav::linkHelper($route = '/client/student/default/expenses', 'post', ['applicant' => $user_id], 'Family Expenses'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))],
                                                    ['label' => SideNav::linkHelper($route = '/client/student/default/guarantors', 'post', ['ApplicantsGuarantors[applicant]' => $user_id], 'Guarantors\' Details'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))],
                                                    ['label' => SideNav::linkHelper($route = '/client/student/default/institution', 'post', ['ApplicantsInstitution[applicant]' => $user_id], 'Institution Details'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))],
                                                    ['label' => 'Historical', 'url' => Url::to([$route = '/site/historical']), 'active' => ($requested_route == substr($route, 1))],
                                                    ['label' => '<span class="pull-right badge">2</span> Announcements', 'icon' => 'bullhorn', 'items' => [
                                                            ['label' => 'Event 1', 'url' => Url::to(['$route = /site/event-1']), 'active' => ($requested_route == substr($route, 1))],
                                                            ['label' => 'Event 2', 'url' => Url::to(['$route = /site/event-2']), 'active' => ($requested_route == substr($route, 1))]
                                                        ]
                                                    ],
                                                ]
                                            ],
                                            ['label' => 'Profile', 'icon' => 'user', 'url' => Url::to(['/site/profile']), 'active' => ($requested_route == substr($route, 1))],
                                        ],
                                    ]
                            )
                            ?>
                        </div>

                    </div>

                    <div class="page-content-rgt pull-right">
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
