<?php

/* @var $this \yii\web\View */
/* @var $content string */

use kartik\sidenav\SideNav;
use yii\helpers\Url;
use common\models\LmBaseEnums;
use frontend\modules\client\modules\student\models\Applicants;

$yes = LmBaseEnums::byNameAndElement(LmBaseEnums::yes_no, LmBaseEnums::yes)->VALUE;
?>
<?php $applicant = \frontend\modules\client\modules\student\models\Applicants::returnApplicant($user_id = Yii::$app->user->identity->id); ?>

<?php $requested_route = Yii::$app->requestedRoute ?>

<?php

$profile_items[] = ['label' => SideNav::linkHelper($route = '/client/student/default/register', 'post', ['Applicants[id]' => $user_id, 'User[id]' => $user_id], 'Personal Details', 'sd-nav-prsnl'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))];
$profile_items[] = ['label' => SideNav::linkHelper($route = '/client/student/default/residence', 'post', ['ApplicantsResidence[applicant]' => $user_id], 'Current Residence Details', 'sd-nav-rsdnc'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))];
$profile_items[] = ['label' => SideNav::linkHelper($route = '/client/student/default/education', 'post', ['EducationBackground[applicant]' => $user_id], 'Education Background', 'sd-nav-edctn'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))];
$profile_items[] = ['label' => SideNav::linkHelper($route = '/client/student/default/institution', 'post', ['ApplicantsInstitution[applicant]' => $user_id], 'Institution Details', 'sd-nav-inst'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))];

$applicant->employed == $yes ? $profile_items[] = ['label' => SideNav::linkHelper($route = '/client/student/default/employment', 'post', ['ApplicantsEmployment[applicant]' => $user_id], 'Employment Details', 'sd-nav-eplymt'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))] : '';

if ($applicant->employed != $yes && $applicant->parents != Applicants::parents_not_applicable) {
    $profile_items[] = ['label' => SideNav::linkHelper($route = '/client/student/default/parents', 'post', ['Applicants[id]' => $user_id], 'Parents\' Details', 'sd-nav-prnts'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))];
    $profile_items[] = ['label' => SideNav::linkHelper($route = '/client/student/default/expenses', 'post', ['applicant' => $user_id], 'Family Expenses', 'sd-nav-expns'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))];
    $profile_items[] = ['label' => SideNav::linkHelper($route = '/client/student/default/sponsors', 'post', ['ApplicantSponsors[applicant]' => $user_id], 'Sponsors\' Details', 'sd-nav-spnsrs'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))];
}

$applicant->married == $yes ? $profile_items[] = ['label' => SideNav::linkHelper($route = '/client/student/default/spouse', 'post', ['ApplicantsSpouse[applicant]' => $user_id], 'Spouse Details', 'sd-nav-sps'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))] : '';
$profile_items[] = ['label' => SideNav::linkHelper($route = '/client/student/default/guarantors', 'post', ['ApplicantsGuarantors[applicant]' => $user_id], 'Guarantors\' Details', 'sd-nav-grntrs'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))];

$profile_items[] = ['label' => SideNav::linkHelper($route = '/business/default/products', 'post', ['Products[id]' => ''], 'Product Settings', 'sd-nav-prdcts'), 'url' => '#', 'active' => ($requested_route == substr($route, 1))];

$profile_items[] = ['label' => 'Historical', 'url' => Url::to([$route = '/site/historical']), 'active' => ($requested_route == substr($route, 1))];
$profile_items[] = ['label' => '<span class="pull-right badge">2</span> Announcements', 'icon' => 'bullhorn', 'items' => [
        ['label' => 'Event 1', 'url' => Url::to(['$route = /site/event-1']), 'active' => ($requested_route == substr($route, 1))],
        ['label' => 'Event 2', 'url' => Url::to(['$route = /site/event-2']), 'active' => ($requested_route == substr($route, 1))]
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
                ['label' => SideNav::linkHelper($route = '/site/index', 'post', [], 'Home', 'sd-nav-hm'), 'icon' => 'home', 'url' => '#', 'active' => ($requested_route == substr($route, 1))],
                ['label' => '<span class="pull-right badge">3</span> Profile', 'icon' => 'user', 'items' => $profile_items],
                ['label' => 'Profile', 'icon' => 'user', 'url' => Url::to(['/site/profile']), 'active' => ($requested_route == substr($route, 1))],
            ],
        ]
)
?>