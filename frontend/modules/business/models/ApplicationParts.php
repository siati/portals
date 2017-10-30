<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\StaticMethods;

/**
 * This is the model class for table "{{%application_parts}}".
 *
 * @property integer $id
 * @property integer $application
 * @property string $part
 * @property integer $order
 * @property string $title
 * @property string $intro
 * @property integer $new_page
 * @property integer $order_elements
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ApplicationParts extends \yii\db\ActiveRecord {

    const new_page_no = 0;
    const new_page_yes = 1;
    const order_elements_no = 0;
    const order_elements_yes = 1;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%application_parts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['application', 'part', 'title', 'created_by'], 'required'],
            [['application', 'order', 'new_page', 'order_elements'], 'integer'],
            [['application'], 'positiveValue'],
            [['created_at', 'modified_at'], 'safe'],
            [['part'], 'string', 'max' => 60],
            [['title'], 'string', 'min' => 5, 'max' => 300],
            [['intro'], 'string'],
            [['title', 'intro'], 'notNumerical'],
            [['created_by', 'modified_by'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'application' => Yii::t('app', 'Application'),
            'part' => Yii::t('app', 'Section'),
            'order' => Yii::t('app', 'Order'),
            'title' => Yii::t('app', 'Title'),
            'intro' => Yii::t('app', 'Opening Statement'),
            'new_page' => Yii::t('app', 'Page Break Before'),
            'order_elements' => Yii::t('app', 'Order Elements'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\activeQueries\ApplicationPartsQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\business\activeQueries\ApplicationPartsQuery(get_called_class());
    }
    
    /**
     * 
     * @param integer $pk part id
     * @return ApplicationParts model
     */
    public static function returnPart($pk) {
        return static::find()->byPk($pk);
    }
    
    /**
     * 
     * @param integer $application application id
     * @param string $part application part
     * @param string $oneOrAll one or all
     * @return ApplicationParts model(s)
     */
    public static function searchParts($application, $part, $oneOrAll) {
        return static::find()->searchParts($application, $part, $oneOrAll);
    }
    
    /**
     * 
     * @param integer $application application id
     * @param string $part application part
     * @return ApplicationParts model
     */
    public static function byApplicationAndPart($application, $part) {
        return static::searchParts($application, $part, self::one);
    }
    
    /**
     * 
     * @param integer $application application id
     * @param string $part application part
     * @return ApplicationParts model
     */
    public static function newPart($application, $part) {
        $model = new ApplicationParts;
        
        $model->application = $application;
        $model->part = $part;
        $model->new_page = self::new_page_no;
        $model->order_elements = self::order_elements_no;
        
        $model->created_by = Yii::$app->user->identity->username;
        
        return $model;
    }
    
    /**
     * 
     * @param integer $id part id
     * @param integer $application application id
     * @param string $part application part
     * @return ApplicationParts model
     */
    public static function partToLoad($id, $application, $part) {
        return is_object($model = static::returnPart($id)) || (!empty($application) && is_object($model = static::byApplicationAndPart($application, $part))) ? $model : static::newPart($application, $part);
    }

    /**
     * 
     * @return boolean true - model saved
     */
    public function modelSave() {
        if ($this->isNewRecord)
            $this->created_at = StaticMethods::now();
        else {
            $this->modified_by = Yii::$app->user->identity->username;
            $this->modified_at = StaticMethods::now();
        }

        return $this->save();
    }
    
    /**
     * 
     * @return array new page yes, no
     */
    public static function newPage() {
        return [self::new_page_no => 'No', self::new_page_yes => 'Yes'];
    }

}
