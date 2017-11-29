<?php

/* @var $this \yii\web\View */
/* @var $content string */

use kartik\sidenav\SideNav;
use yii\helpers\Url;
?>

<?php $requested_route = Yii::$app->requestedRoute ?>

<?php

$profile_items[] = ['label' => 'Historical', 'url' => Url::to([$route = '/site/historical']), 'active' => $requested_route == substr($route, 1)];
$profile_items[] = ['label' => '<span class="pull-right badge">2</span> Announcements', 'icon' => 'bullhorn', 'items' => [
        ['label' => 'Event 1', 'url' => Url::to([$route = '/site/event-1']), 'active' => $requested_route == substr($route, 1)],
        ['label' => 'Event 2', 'url' => Url::to([$route = '/site/event-2']), 'active' => $requested_route == substr($route, 1)]
    ]
];
?>

<?=

SideNav::widget(
        [
            'type' => SideNav::TYPE_DEFAULT,
            'encodeLabels' => false,
            'heading' => Yii::jrCompanyName(),
            'items' => [
                ['label' => SideNav::linkHelper($route = '/site/index', 'post', [], 'Home', 'sd-nav-hm'), 'icon' => 'home', 'url' => '#', 'active' => $requested_route == substr($route, 1)],
                ['label' => '<span class="pull-right badge">3</span> Profile', 'icon' => 'user', 'items' => $profile_items],
                ['label' => 'Others', 'icon' => 'user', 'url' => Url::to(['/site/profile']), 'active' => $requested_route == substr($route, 1)],
            ],
        ]
)
?>