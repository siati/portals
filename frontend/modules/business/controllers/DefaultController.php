<?php

namespace frontend\modules\business\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\modules\business\models\Products;
use frontend\modules\business\models\ProductOpening;
use frontend\modules\business\models\ProductOpeningSettings;
use frontend\modules\business\models\ProductAccessProperties;
use frontend\modules\business\models\ProductAccessPropertyItems;
use frontend\modules\client\modules\student\models\ApplicantProductAccessCheckers;
use frontend\modules\business\models\ApplicationParts;
use frontend\modules\business\models\ApplicationPartElements;

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
                    'index', 'products', 'save-product', 'save-opening', 'opening-i-d', 'save-settings', 'dynamic-settings', 'access-checkers', 'save-access-property', 'save-access-property-item', 'application-parts', 'save-application-part', 'save-application-part-element'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'products', 'save-product', 'save-opening', 'opening-i-d', 'save-settings', 'dynamic-settings', 'access-checkers', 'save-access-property', 'save-access-property-item', 'application-parts', 'save-application-part', 'save-application-part-element'
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
                    'opening' => $opening = ProductOpening::openingToLoad(empty($_POST['ProductOpening']['id']) ? '' : $_POST['ProductOpening']['id'], $product->id, empty($_POST['ProductOpening']['academic_year']) ? '' : $_POST['ProductOpening']['academic_year'], empty($_POST['ProductOpening']['subsequent']) ? '' : $_POST['ProductOpening']['subsequent']),
                    'settings' => ProductOpeningSettings::settingsToLoad($opening->id)
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

            if (isset($_POST['sbmt']))
                return $opening->modelSave() ? [true] : [false, $opening->errors];

            return is_array($ajax) ? $ajax : [];
        }
    }

    /**
     * load opening id to the client
     */
    public function actionOpeningID() {
        echo ProductOpening::openingToLoad(empty($_POST['id']) ? '' : $_POST['id'], empty($_POST['product']) ? '' : $_POST['product'], empty($_POST['academic_year']) ? '' : $_POST['academic_year'], empty($_POST['subsequent']) ? '' : $_POST['subsequent'])->id;
    }

    /**
     * 
     * @return string save product setting
     */
    public function actionSaveSettings() {
        $settings = ProductOpeningSettings::settingsToLoad(empty($_POST['ProductOpeningSettings']['application']) ? '' : $_POST['ProductOpeningSettings']['application']);

        foreach ($settings as $setting)
            isset($_POST['ProductOpeningSettings'][$setting->setting]) ? $setting->attributes = $_POST['ProductOpeningSettings'][$setting->setting] : '';

        if (($ajax = $this->ajaxValidateMultiple($settings)) === self::IS_AJAX || count($ajax) > 0) {

            if (isset($_POST['sbmt'])) {
                foreach ($settings as $setting)
                    isset($_POST['ProductOpeningSettings'][$setting->setting]) ? ($setting->modelSave() ? '' : $hasError[] = $setting->name) : ('');

                return empty($hasError) ? [true] : [false, empty($hasError)];
            }

            return is_array($ajax) ? $ajax : [];
        }
    }

    /**
     * 
     * @return array load product settings dynamically to client
     */
    public function actionDynamicSettings() {
        $opening = ProductOpening::openingToLoad(empty($_POST['id']) ? '' : $_POST['id'], empty($_POST['product']) ? '' : $_POST['product'], empty($_POST['academic_year']) ? '' : $_POST['academic_year'], empty($_POST['subsequent']) ? '' : $_POST['subsequent']);

        foreach (['min_apps', 'max_apps', 'consider_counts', 'since', 'till', 'grace', 'appeal_since', 'appeal_till'] as $attribute)
            $settings["productopening-$attribute"] = $opening->$attribute;

        foreach (ProductOpeningSettings::settingsToLoad($opening->id) as $setting)
            $settings["productopeningsettings-$setting->setting-value"] = $setting->value;

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return empty($settings) ? [] : $settings;
    }

    /**
     * 
     * @return string main interface for advanced application opening settings
     */
    public function actionAccessCheckers() {
        return $this->renderAjax('advanced-settings', ['sections' => ApplicantProductAccessCheckers::checkerSections(), 'application' => $_POST['application']]);
    }

    /**
     * 
     * @return array save applicant product access rules
     */
    public function actionSaveAccessProperty() {
        foreach ($_POST['ProductAccessProperties'] as $property => $post) {
            $models[$property] = ProductAccessProperties::propertyToLoad(empty($post['id']) ? '' : $post['id'], $property, empty($post['table']) ? '' : $post['table'], empty($post['column']) ? '' : $post['column'], empty($post['model_class']) ? '' : $post['model_class'], empty($post['attribute']) ? '' : $post['attribute']);
            $models[$property]->attributes = $post;
        }

        if (($ajax = $this->ajaxValidateMultiple($models)) === self::IS_AJAX || count($ajax) > 0) {

            if (isset($_POST['sbmt'])) {
                $models[$_POST['sbmt']]->modelSave() ? '' : $hasError[] = $models[$_POST['sbmt']]->property;

                return [empty($hasError), $models[$_POST['sbmt']]->name, $models[$_POST['sbmt']]->id];
            }

            return is_array($ajax) ? $ajax : [];
        }
    }
    
    /**
     * 
     * @return array save applicant product access rules
     */
    public function actionSaveAccessPropertyItem() {
        foreach ($_POST['ProductAccessPropertyItems'] as $key => $post) {
            $models[$key] = ProductAccessPropertyItems::itemToLoad(empty($post['id']) ? '' : $post['id'], empty($post['application']) ? '' : $post['application'], empty($post['property']) ? '' : $post['property']);
            $models[$key]->attributes = $post;
        }

        if (($ajax = $this->ajaxValidateMultiple($models)) === self::IS_AJAX || count($ajax) > 0) {

            if (isset($_POST['sbmt'])) {
                $models[$_POST['sbmt']]->modelSave() ? '' : $hasError[] = $models[$_POST['sbmt']]->property;

                return [empty($hasError), $models[$_POST['sbmt']]->property];
            }

            return is_array($ajax) ? $ajax : [];
        }
    }
    
    /**
     * 
     * @return string main interface for application part settings
     */
    public function actionApplicationParts() {
        return $this->renderAjax('application-parts', ['parts' => ProductOpening::returnOpening($_POST['application'])->theParts(!empty($_POST['appeal'])), 'application' => $_POST['application'], 'appeal' => empty($_POST['appeal']) ? ApplicationParts::appeal_no : ApplicationParts::appeal_yes]); 
    }
    
    /**
     * @return array save application part
     */
    public function actionSaveApplicationPart() {
        foreach ($_POST['ApplicationParts'] as $key => $post) {
            $models[$key] = ApplicationParts::partToLoad(empty($post['id']) ? '' : $post['id'], empty($post['application']) ? '' : $post['application'], empty($post['appeal']) ? ApplicationParts::appeal_no : $post['appeal'], empty($post['part']) ? '' : $post['part']);
            $models[$key]->attributes = $post;
        }

        if (($ajax = $this->ajaxValidateMultiple($models)) === self::IS_AJAX || count($ajax) > 0) {

            if (isset($_POST['sbmt'])) {
                $models[$_POST['sbmt']]->modelSave() ? '' : $hasError[] = $models[$_POST['sbmt']]->part;

                return [empty($hasError), $models[$_POST['sbmt']]->title, $models[$_POST['sbmt']]->id];
            }

            return is_array($ajax) ? $ajax : [];
        }
    }
    
    /**
     * 
     * @return array save application part element
     */
    public function actionSaveApplicationPartElement() {
        foreach ($_POST['ApplicationPartElements'] as $key => $post) {
            $models[$key] = ApplicationPartElements::elementToLoad(empty($post['id']) ? '' : $post['id'], empty($post['part']) ? '' : $post['part'], empty($post['element']) ? '' : $post['element']);
            $models[$key]->attributes = $post;
        }

        if (($ajax = $this->ajaxValidateMultiple($models)) === self::IS_AJAX || count($ajax) > 0) {

            if (isset($_POST['sbmt'])) {
                $models[$_POST['sbmt']]->modelSave() ? '' : $hasError[] = $models[$_POST['sbmt']]->element;

                return [empty($hasError), $models[$_POST['sbmt']]->title, $models[$_POST['sbmt']]->id];
            }

            return is_array($ajax) ? $ajax : [];
        }
    }

}
