<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\SubCounties;
use common\models\Constituencies;
use common\models\Wards;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\StaticMethods;
use common\models\Docs;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['signup', 'logout', 'check-file', 'expire-resource'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => Yii::$app->user->isGuest,
                        'roles' => ['?'],
                        'verbs' => ['post']
                    ],
                    [
                        'actions' => ['check-file', 'expire-resource', 'logout'],
                        'allow' => !Yii::$app->user->isGuest,
                        'roles' => ['@'],
                        'verbs' => ['post']
                    ]
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionModal() {
        return $this->renderAjax('modal');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest)
            return $this->goBack();

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {
            if (($ajax = $this->ajaxValidate($model)) === self::IS_AJAX || count($ajax) > 0)
                return is_array($ajax) ? $ajax : [];

            if ($model->login())
                return $this->goBack();
        }

        return $this->render('login', ['model' => $model]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout() {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }
    
    /**
     * check if resource is available on the server and return response to client
     */
    public function actionCheckFile() {
        echo Docs::fileLocate($_POST['cat'], $_POST['nm'], Docs::locator);
    }
    
    /**
     * drop file
     */
    public function actionExpireResource() {
        Docs::deleteFile(Docs::category_client, basename($_POST['nm']));
    }

    /**
     * load dynamic sub counties
     */
    public function actionDynamicSubcounties() {
        StaticMethods::populateDropDown(StaticMethods::modelsToArray(SubCounties::subcountiesForCounty($_POST['county']), 'id', 'name', false), 'Select SubCounty', $_POST['subcounty']);
    }

    /**
     * load dynamic constituencies
     */
    public function actionDynamicConstituencies() {
        StaticMethods::populateDropDown(StaticMethods::modelsToArray(Constituencies::constituenciesForCounty($_POST['county']), 'id', 'name', false), 'Select Constituency', $_POST['constituency']);
    }

    /**
     * load dynamic wards
     */
    public function actionDynamicWards() {
        StaticMethods::populateDropDown(StaticMethods::modelsToArray(Wards::wardsForConstituency($_POST['constituency']), 'id', 'name', false), 'Select Ward', $_POST['ward']);
    }

}
