<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%lm_courses}}".
 *
 * @property string $RECID
 * @property string $INSTITUTIONCODE
 * @property string $INSTITUTIONBRANCHCODE
 * @property integer $LEVELOFSTUDY
 * @property string $FACULTY
 * @property integer $COURSETYPE
 * @property integer $COURSECATEGORY
 * @property string $COURSEDESCRIPTION
 * @property string $COURSECODE
 * @property integer $COURSEDURATION
 * @property integer $ACTIVE
 */
class LmCourses extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%lm_courses}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['RECID', 'INSTITUTIONCODE', 'INSTITUTIONBRANCHCODE', 'LEVELOFSTUDY', 'FACULTY', 'COURSETYPE', 'COURSECATEGORY', 'COURSEDESCRIPTION', 'COURSECODE', 'COURSEDURATION', 'ACTIVE'], 'required'],
            [['LEVELOFSTUDY', 'FACULTY', 'COURSETYPE', 'COURSECATEGORY', 'COURSEDURATION', 'ACTIVE'], 'integer'],
            [['RECID', 'INSTITUTIONCODE', 'COURSECODE'], 'string', 'max' => 30],
            [['INSTITUTIONBRANCHCODE'], 'string', 'max' => 10],
            [['COURSEDESCRIPTION'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'RECID' => Yii::t('app', 'Recid'),
            'INSTITUTIONCODE' => Yii::t('app', 'Institutioncode'),
            'INSTITUTIONBRANCHCODE' => Yii::t('app', 'Institutionbranchcode'),
            'LEVELOFSTUDY' => Yii::t('app', 'Levelofstudy'),
            'FACULTY' => Yii::t('app', 'Faculty'),
            'COURSETYPE' => Yii::t('app', 'Coursetype'),
            'COURSECATEGORY' => Yii::t('app', 'Coursecategory'),
            'COURSEDESCRIPTION' => Yii::t('app', 'Coursedescription'),
            'COURSECODE' => Yii::t('app', 'Coursecode'),
            'COURSEDURATION' => Yii::t('app', 'Courseduration'),
            'ACTIVE' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\activeQueries\LmCoursesQuery the active query used by this AR class.
     */
    public static function find() {
        return new \common\activeQueries\LmCoursesQuery(get_called_class());
    }
    
    /**
     * 
     * @param string $institution_code institution code
     * @param string $institution_branch_code branch code
     * @param string $level_of_study level of study
     * @param string $faculty faculty
     * @param string $course_type course type
     * @param string $course_category course category
     * @param integer $active active
     * @return LmCourses models
     */
    public static function searchCourses($institution_code, $institution_branch_code, $level_of_study, $faculty, $course_type, $course_category, $active) {
        return static::find()->searchCourses($institution_code, $institution_branch_code, $level_of_study, $faculty, $course_type, $course_category, $active);
    }
    
    /**
     * 
     * @param string $institution_code institution code
     * @param string $institution_branch_code branch code
     * @param string $level_of_study level of study
     * @param string $faculty faculty
     * @param string $course_type course type
     * @param string $course_category course category
     * @param integer $active active
     * @return array courses
     */
    public static function courses($institution_code, $institution_branch_code, $level_of_study, $faculty, $course_type, $course_category, $active) {
        return StaticMethods::modelsToArray(static::searchCourses($institution_code, $institution_branch_code, $level_of_study, $faculty, $course_type, $course_category, $active), 'COURSECODE', 'COURSEDESCRIPTION', true);
    }
    
    /**
     * 
     * @param string $institution_code institution code
     * @param string $course_code course code
     * @return LmCourses model
     */
    public static function byInstitutionAndCourseCodes($institution_code, $course_code) {
        return static::find()->byInstitutionAndCourseCodes($institution_code, $course_code);
    }

}
