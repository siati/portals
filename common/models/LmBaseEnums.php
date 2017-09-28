<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%lm_base_enums}}".
 *
 * @property integer $id
 * @property string $NAME
 * @property string $ELEMENT
 * @property integer $VALUE
 * @property string $LABEL
 */
class LmBaseEnums extends \yii\db\ActiveRecord {

    const yes_no = 'NoYesCombo';
    const yes = 'Yes';
    const no = 'No';
    const gender = 'Gender';
    const gender_male = 'Male';
    const gender_female = 'Female';
    const study_level = 'LMStudyLevel';
    const study_level_none = 'None';
    const study_level_certificate = 'Certificate';
    const study_level_diploma = 'Diploma';
    const study_level_degree = 'Degree';
    const study_level_masters = 'Masters';
    const study_level_phd = 'PhD';
    const course_category = 'LMCourseCategory';
    const course_category_none = 'none';
    const course_category_humanity = 'Humanity';
    const course_category_ict = 'ICT';
    const course_category_medical = 'Medical';
    const course_category_engineering_and_technology = 'EngineeringTechnology';
    const course_category_science_and_agriculture = 'ScienceAgriculture';
    const course_category_education = 'Education';
    const course_category_nursing = 'Nursing';
    const course_category_clinical_medicine = 'ClinicalMedicine';
    const course_category_laboratory_technology = 'LaboratoryTechnology';
    const course_category_nutrition = 'Nutrition';
    const course_category_health_records = 'HealthRecordsMgt';
    const course_category_public_health = 'PublicHealth';
    const course_category_pharmaceutical_technology = 'PharmaceuticalTechnology';
    const course_category_radiography = 'Radiography';
    const course_category_physiotherapy = 'Physiotherapy';
    const course_type = 'LMCourseTypes';
    const course_type_technical = 'Technical';
    const course_type_non_technical = 'NonTechnical';
    const school_type = 'LMSchoolType';
    const school_type_public = 'Public';
    const school_type_private = 'Private';
    const admission_category = 'LMAdmissionCategory';
    const admission_category_public_govt_sponsored = 'PublicUniversityGovtSponsored';
    const admission_category_public_self_sponsored = 'PublicUniversitySelfSponsored';
    const admission_category_private_govt_sponsored = 'PrivateUniversityGovtSponsored';
    const admission_category_private_self_sponsored = 'PrivateUniversitySelfSponsored';
    const institution_type = 'LMInstitutionType';
    const institution_type_none = 'None';
    const institution_type_primary = 'Primary';
    const institution_type_secondary = 'Secondary';
    const institution_type_polytechnic = 'Polytechnic';
    const institution_type_technical = 'Technical';
    const institution_type_technology = 'Technology';
    const institution_type_kmtc = 'Kmtc';
    const institution_type_university = 'University';
    const institution_type_tertiary = 'Tertiary';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%lm_base_enums}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['NAME', 'ELEMENT', 'LABEL'], 'required'],
            [['VALUE'], 'integer'],
            [['NAME', 'ELEMENT'], 'string', 'max' => 40],
            [['LABEL'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'NAME' => Yii::t('app', 'Name'),
            'ELEMENT' => Yii::t('app', 'Element'),
            'VALUE' => Yii::t('app', 'Value'),
            'LABEL' => Yii::t('app', 'Label'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\activeQueries\LmBaseEnumsQuery the active query used by this AR class.
     */
    public static function find() {
        return new \common\activeQueries\LmBaseEnumsQuery(get_called_class());
    }

    /**
     * 
     * @param string $names comma separated item names
     * @param boolean $distinct true - distinct names
     * @return LmBaseEnums models
     */
    public static function mainItems($names, $distinct) {
        return static::find()->mainItems($names, $distinct);
    }

    /**
     * 
     * @param string $name item name
     * @param string $element comma separated item elements
     * @return LmBaseEnums models
     */
    public static function itemElements($name, $element) {
        return static::find()->itemElements($name, $element);
    }

    /**
     * 
     * @param string $name item name
     * @param string $element item element
     * @return LmBaseEnums model
     */
    public static function byNameAndElement($name, $element) {
        return static::find()->byNameAndElement($name, $element);
    }

    /**
     * 
     * @param string $name item name
     * @param string $value item element value
     * @return LmBaseEnums model
     */
    public static function byNameAndValue($name, $value) {
        return static::find()->byNameAndValue($name, $value);
    }

    /**
     * 
     * @return array yes, no
     */
    public static function yesNo() {
        return StaticMethods::modelsToArray(static::itemElements(self::yes_no, null), 'VALUE', 'LABEL', false);
    }

    /**
     * 
     * @param string $yesNo yesNo element
     * @return LmBaseEnums model
     */
    public static function yesOrNo($yesNo) {
        return static::byNameAndElement(self::yes_no, $yesNo);
    }

    /**
     * 
     * @return array genders
     */
    public static function genders() {
        return StaticMethods::modelsToArray(static::itemElements(self::gender, null), 'VALUE', 'LABEL', false);
    }

    /**
     * 
     * @param string $gender gender element
     * @return LmBaseEnums model
     */
    public static function gender($gender) {
        return static::byNameAndElement(self::gender, $gender);
    }

    /**
     * 
     * @return array study levels
     */
    public static function studyLevels() {
        return StaticMethods::modelsToArray(static::itemElements(self::study_level, null), 'VALUE', 'LABEL', false);
    }

    /**
     * 
     * @param string $study_level study level element
     * @return LmBaseEnums model
     */
    public static function studyLevel($study_level) {
        return static::byNameAndElement(self::study_level, $study_level);
    }

    /**
     * 
     * @return array course categories
     */
    public static function courseCategories() {
        return StaticMethods::modelsToArray(static::itemElements(self::course_category, null), 'VALUE', 'LABEL', false);
    }

    /**
     * 
     * @param string $course_category course category
     * @return LmBaseEnums model
     */
    public static function courseCategory($course_category) {
        return static::byNameAndElement(self::course_category, $course_category);
    }

    /**
     * 
     * @return array course types
     */
    public static function courseTypes() {
        return StaticMethods::modelsToArray(static::itemElements(self::course_type, null), 'VALUE', 'LABEL', false);
    }

    /**
     * 
     * @param string $course_type course type
     * @return LmBaseEnums model
     */
    public static function courseType($course_type) {
        return static::byNameAndElement(self::course_type, $course_type);
    }

    /**
     * 
     * @return array school types
     */
    public static function schoolTypes() {
        return StaticMethods::modelsToArray(static::itemElements(self::school_type, null), 'VALUE', 'LABEL', false);
    }

    /**
     * 
     * @param string $school_type school type
     * @return LmBaseEnums model
     */
    public static function schoolType($school_type) {
        return static::byNameAndElement(self::school_type, $school_type);
    }

    /**
     * 
     * @return array admission categories
     */
    public static function admissionCategories() {
        return StaticMethods::modelsToArray(static::itemElements(self::admission_category, null), 'VALUE', 'LABEL', false);
    }

    /**
     * 
     * @param string $admission_category admission category
     * @return LmBaseEnums model
     */
    public static function admissionCategory($admission_category) {
        return static::byNameAndElement(self::admission_category, $admission_category);
    }

    /**
     * 
     * @param string $admission_category admission category
     * @return LmBaseEnums model
     */
    public static function admissionCategory2($admission_category) {
        return static::byNameAndValue(self::admission_category, $admission_category);
    }

    /**
     * 
     * @return array institution types
     */
    public static function institutionTypes() {
        return StaticMethods::modelsToArray(static::itemElements(self::institution_type, null), 'VALUE', 'LABEL', false);
    }

    /**
     * 
     * @param string $institution_type institution type
     * @return LmBaseEnums model
     */
    public static function institutionType($institution_type) {
        return static::byNameAndElement(self::institution_type, $institution_type);
    }

    /**
     * 
     * @param string $admission_category admission category
     * @return LmBaseEnums model
     */
    public static function schoolTypeFromAdmissionCategory($admission_category) {
        return static::
                schoolType(
                        in_array(static::admissionCategory2($admission_category)->ELEMENT, [self::admission_category_public_govt_sponsored, self::admission_category_public_self_sponsored]) ? self::school_type_public : self::school_type_private
        );
    }
    
    /**
     * 
     * @param string $level_of_study level of study
     * @return int study duration
     */
    public static function studyDuration($level_of_study) {
        $study_level_element = static::admissionCategory2($level_of_study)->ELEMENT;
        
        if (in_array($study_level_element, [self::study_level_diploma, self::study_level_masters, self::study_level_phd]))
            return 2;
        
        if (in_array($study_level_element, [self::study_level_degree]))
            return 4;
        
        if (in_array($study_level_element, [self::study_level_certificate]))
            return 1.5;
        
        return 1;
    }

    /**
     * 
     * @param boolean $fractional true - include fractional values
     * @return array course durations
     */
    public static function courseDurations($fractional) {
        return
                $fractional ? [
            '1' => 'One Year',
            '1.5' => 'One and A Half Years',
            '2' => 'Two Years',
            '2.5' => 'Two and A Half Years',
            '3' => 'Three Years',
            '3.5' => 'Three and A Half Years',
            '4' => 'Four Years',
            '5' => 'Five Years',
            '6' => 'Six Years',
            '7' => 'Seven Years'
                ] : [
            '1' => 'One Year',
            '2' => 'Two Years',
            '3' => 'Three Years',
            '4' => 'Four Years',
            '5' => 'Five Years',
            '6' => 'Six Years',
            '7' => 'Seven Years'
        ];
    }

    /**
     * 
     * @return array study years
     */
    public static function studyYears() {
        return [
            1 => 'First Year',
            2 => 'Second Year',
            3 => 'Third Year',
            4 => 'Fourth Year',
            5 => 'Fifth Year',
            6 => 'Sixth Year',
            7 => 'Seventh Year'
        ];
    }

    /**
     * 
     * @return array countries
     */
    public static function countries() {
        return [
            'KEN' => 'Kenya',
            'UG' => 'Uganda',
            'TZ' => 'Tanzania',
            'RWD' => 'Rwanda'
        ];
    }

}
