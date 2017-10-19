<?php

namespace frontend\modules\business\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\modules\business\models\Products;
use frontend\modules\business\models\ProductOpening;

/**
 * Default controller for the `business` module
 */
class DefaultController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index', 'products', 'save-product', 'save-opening'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'products', 'save-product', 'save-opening'
                        ],
                        'allow' => !Yii::$app->user->isGuest,
                        'roles' => ['@'],
                        'verbs' => ['post']
                    ]
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * 
     * @return string view to update product settings
     */
    public function actionProducts() {
        return $this->render('product-settings', [
                    'product' => $product = Products::productToLoad(empty($_POST['Products']['id']) ? '' : $_POST['Products']['id']),
                    'opening' => ProductOpening::openingToLoad(empty($_POST['ProductOpening']['id']) ? '' : $_POST['ProductOpening']['id'], $product->id, empty($_POST['ProductOpening']['academic_year']) ? '' : $_POST['ProductOpening']['academic_year'], empty($_POST['ProductOpening']['subsequent']) ? '' : $_POST['ProductOpening']['subsequent'])
                        ]
        );
    }

    /**
     * 
     * @return string save product
     */
    public function actionSaveProduct() {
        $product = Products::productToLoad(empty($_POST['Products']['id']) ? '' : $_POST['Products']['id']);

        $product->load(Yii::$app->request->post());

        if (($ajax = $this->ajaxValidate($product)) === self::IS_AJAX || count($ajax) > 0) {

            if (isset($_POST['sbmt']))
                return [$product->modelSave(), $product->id];

            return is_array($ajax) ? $ajax : [];
        }
    }

    /**
     * 
     * @return string save product opening
     */
    public function actionSaveOpening() {
        $opening = ProductOpening::openingToLoad(empty($_POST['ProductOpening']['id']) ? '' : $_POST['ProductOpening']['id'], empty($_POST['ProductOpening']['product']) ? '' : $_POST['ProductOpening']['product'], empty($_POST['ProductOpening']['academic_year']) ? '' : $_POST['ProductOpening']['academic_year'], empty($_POST['ProductOpening']['subsequent']) ? '' : $_POST['ProductOpening']['subsequent']);

        $opening->load(Yii::$app->request->post());

        if (($ajax = $this->ajaxValidate($opening)) === self::IS_AJAX || count($ajax) > 0) {

            if (isset($_POST['sbmt']) && $opening->modelSave())
                return [];

            return is_array($ajax) ? $ajax : [];
        }
    }

}
