<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\StaticMethods;

/**
 * This is the model class for table "{{%application_views}}".
 *
 * @property integer $id
 * @property integer $application
 * @property integer $appeal
 * @property integer $user_id
 * @property string $username
 * @property string $viewed_at
 * @property integer $new_print
 */
class ApplicationViews extends \yii\db\ActiveRecord {

    const new_print_yes = 1;
    const new_print_no = 0;
    const appeal_yes = 1;
    const appeal_no = 0;


    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%application_views}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['application', 'user_id', 'username'], 'required'],
            [['application', 'appeal', 'user_id', 'new_print'], 'integer'],
            [['viewed_at'], 'safe'],
            [['username'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'application' => Yii::t('app', 'Application'),
            'appeal' => Yii::t('app', 'Appeal'),
            'user_id' => Yii::t('app', 'User ID'),
            'username' => Yii::t('app', 'Username'),
            'viewed_at' => Yii::t('app', 'Viewed At'),
            'new_print' => Yii::t('app', 'New Print'),
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\activeQueries\ApplicationViewsQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\business\activeQueries\ApplicationViewsQuery(get_called_class());
    }
    
    /**
     * 
     * @param integer $pk view id
     * @return ApplicationViews model
     */
    public static function returnView($pk) {
        return static::find()->byPk($pk);
    }
    
    /**
     * 
     * @param integer $application application id
     * @param integer $appeal appeal: 0 - no, 1 - yes
     * @param integer $user_id user id
     * @param string $username username
     * @param string $viewed_at_since viewed at
     * @param string $viewed_at_till viewed at
     * @param integer $new_print new_print: 0 - no ,1 - yes
     * @param string $oneOrAll one or all
     * @return pplicationViews model(s)
     */
    public static function searchViews($application, $appeal, $user_id, $username, $viewed_at_since, $viewed_at_till, $new_print, $oneOrAll) {
        return static::find()->searchViews($application, $appeal, $user_id, $username, $viewed_at_since, $viewed_at_till, $new_print, $oneOrAll);
    }
    
    /**
     * 
     * @param integer $application application id
     * @param integer $appeal appeal: 0 - no, 1 - yes
     * @return pplicationViews models
     */
    public static function forApplication($application, $appeal) {
        return static::searchViews($application, $appeal, null, null, null, null, null, self::all);
    }
    
    /**
     * 
     * @param integer $user_id user id
     * @param string $username username
     * @return pplicationViews models
     */
    public static function forUser($user_id, $username) {
        return static::searchViews(null, null, $user_id, $username, null, null, null, self::all);
    }
    
    /**
     * 
     * @param integer $application application id
     * @param integer $appeal appeal: 0 - no, 1 - yes
     * @param integer $user_id user id
     * @param string $username username
     * @return ApplicationViews models
     */
    public static function forApplicationAndUser($application, $appeal, $user_id, $username) {
        return static::searchViews($application, $appeal, $user_id, $username, null, null, null, self::all);
    }
    
    /**
     * 
     * @param integer $application
     * @param integer $appeal appeal: 0 - no, 1 - yes
     * @param integer $new_print 1 - new_print, 0 - reprint
     * @return boolean true - model saved
     */
    public static function newView($application, $appeal, $new_print) {
        $model = new ApplicationViews;
        
        $model->application = $application;
        $model->appeal = $appeal;
        $model->user_id = Yii::$app->user->identity->id;
        $model->username = Yii::$app->user->identity->username;
        $model->viewed_at = StaticMethods::now();
        $model->new_print = $new_print;
        
        return $model->save(false);
    }

}
