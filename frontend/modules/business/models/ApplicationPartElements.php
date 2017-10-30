<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\StaticMethods;

/**
 * This is the model class for table "{{%application_part_elements}}".
 *
 * @property integer $id
 * @property integer $part
 * @property string $element
 * @property string $title
 * @property string $narration
 * @property integer $order
 * @property integer $active
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class ApplicationPartElements extends \yii\db\ActiveRecord {

    const active_no = 0;
    const active_yes = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%application_part_elements}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['part', 'element', 'title', 'created_by'], 'required'],
            [['part', 'order', 'active'], 'integer'],
            [['part'], 'positiveValue'],
            [['title'], 'string', 'min' => 5, 'max' => 128],
            [['title'], 'sanitizeString'],
            [['narration'], 'string'],
            [['created_at', 'modified_at'], 'safe'],
            [['element'], 'string', 'max' => 60],
            [['created_by', 'modified_by'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'part' => Yii::t('app', 'Section'),
            'element' => Yii::t('app', 'Element'),
            'title' => Yii::t('app', 'Title'),
            'narration' => Yii::t('app', 'Narration'),
            'order' => Yii::t('app', 'Order'),
            'active' => Yii::t('app', 'Active'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\activeQueries\ApplicationPartElementsQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\business\activeQueries\ApplicationPartElementsQuery(get_called_class());
    }
    
    /**
     * 
     * @param integer $pk element id
     * @return ApplicationPartElements model
     */
    public static function returnElement($pk) {
        return static::find()->byPk($pk);
    }
    
    /**
     * 
     * @param integer $part part id
     * @param string $element element
     * @param string $narration narration
     * @param integer $active active: 1 - yes, 0 - no
     * @param string $oneOrAll one or all
     * @return ApplicationPartElements model(s)
     */
    public static function searchElements($part, $element, $narration, $active, $oneOrAll) {
        return static::find()->searchElements($part, $element, $narration, $active, $oneOrAll);
    }
    
    /**
     * 
     * @param integer $part part id
     * @param string $element element
     * @return ApplicationPartElements model
     */
    public static function byPartAndElement($part, $element) {
        return static::searchElements($part, $element, null, null, self::one);
    }
    
    /**
     * 
     * @param integer $part part id
     * @param string $element element
     * @return ApplicationPartElements model
     */
    public static function newElement($part, $element) {
        $model = new ApplicationPartElements;
        
        $model->part = $part;
        $model->element = $element;
        $model->active = self::active_yes;
        
        $model->created_by = Yii::$app->user->identity->username;
        
        return $model;
    }
    
    /**
     * 
     * @param integer $id element id
     * @param integer $part part id
     * @param string $element element
     * @return ApplicationPartElements model
     */
    public static function elementToLoad($id, $part, $element) {
        return is_object($model = static::returnElement($id)) || (!empty($part) && is_object($model = static::byPartAndElement($part, $element))) ? $model : static::newElement($part, $element);
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
     * @return array active
     */
    public static function actives() {
        return [self::active_yes => 'Yes', self::active_no => 'No'];
    }

}
