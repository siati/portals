<?php

namespace frontend\modules\client\modules\loan\controllers;

use yii\web\Controller;

/**
 * Default controller for the `loan` module
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
