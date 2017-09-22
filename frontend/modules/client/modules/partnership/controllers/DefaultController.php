<?php

namespace frontend\modules\client\modules\partnership\controllers;

use yii\web\Controller;

/**
 * Default controller for the `partnership` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
