<?php

/* @var $this \yii\web\View */

use kartik\sidenav\SideNav;
?>
<?php $user_id = Yii::$app->user->identity->id ?>

<?php $requested_route = Yii::$app->requestedRoute ?>

<?=

SideNav::widget(
        [
            'type' => SideNav::TYPE_DEFAULT,
            'encodeLabels' => false,
            'heading' => Yii::jrCompanyName(),
            'items' => [
                ['label' => SideNav::linkHelper($route = '/', 'post', [], 'Home', 'sd-nav-hm'), 'url' => '#', 'active' => $requested_route == substr($route, 1)],
                ['label' => SideNav::linkHelper($route = '/business/default/products', 'post', ['Products[id]' => ''], 'Product Settings', 'sd-nav-prdcts'), 'url' => '#', 'active' => $requested_route == substr($route, 1)]
            ],
        ]
)
?>