<?php

/* @var $this \yii\web\View */

use kartik\sidenav\SideNav;
use common\models\User
?>

<?php $requested_route = Yii::$app->requestedRoute ?>

<?php foreach (User::applicantsMenu() as $item => $sub_items): ?>

    <?php $menu[$item] = ['label' => $item] ?>

    <?php foreach ($sub_items as $sub_item): ?>

        <?php $menu[$item]['items'][] = ['label' => SideNav::linkHelper($route = "/client/student/default/$sub_item[ax]", 'post', $sub_item['ps'], $sub_item['nm'], $sub_item['id']), 'url' => '#', 'active' => $requested_route == substr($route, 1)] ?>

    <?php endforeach; ?>

<?php endforeach; ?>

<?= SideNav::widget(['type' => SideNav::TYPE_DEFAULT, 'encodeLabels' => false, 'heading' => Yii::jrCompanyName(), 'items' => $menu]) ?>