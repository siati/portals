<?php

namespace frontend\modules\client\modules\student\models;

use Yii;
use common\models\StaticMethods;

/**
 * This is the model class for table "{{%education_background}}".
 *
 * @property integer $id
 * @property integer $applicant
 * @property string $institution_name
 * @property integer $institution_type
 * @property integer $school_type
 * @property integer $study_level
 * @property string $course_name
 * @property integer $since
 * @property integer $till
 * @property integer $annual_fees
 * @property string $exam_no
 * @property integer $score
 * @property integer $out_of
 * @property string $grade
 * @property integer $sponsored
 * @property integer $sponsorship_reason
 * @property string $created_by
 * @property string $created_at
 * @property string $modified_by
 * @property string $modified_at
 */
class EducationBackground extends \yii\db\ActiveRecord {

    const inst_type_primary = 0;
    const inst_type_secondary = 1;
    const inst_type_tertiary = 2;
    const inst_type_technical = 3;
    const inst_type_polytechnic = 4;
    const inst_type_technology = 5;
    const inst_type_medical = 6;
    const inst_type_university = 7;
    const school_type_public = 0;
    const school_type_private = 1;
    const school_type_faith_based = 2;
    const school_type_community_based = 3;
    const study_level_primary = 0;
    const study_level_secondary = 1;
    const study_level_certificate = 2;
    const study_level_diploma = 3;
    const study_level_degree = 4;
    const study_level_masters = 5;
    const study_level_phd = 6;
    const sponsored_yes = 1;
    const sponsored_no = 0;
    const sponsor_reason_none = 0;
    const sponsor_reason_poor = 1;
    const sponsor_reason_orphan = 2;
    const sponsor_reason_scholarship = 3;
    const primary_cert = 'Kenya Certificate of Primary Education';
    const secondary_cert = 'Kenya Certificate of Secondary Education';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%education_background}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicant', 'institution_name', 'institution_type', 'study_level', 'course_name', 'since', 'till', 'exam_no', 'created_by'], 'required'],
            [['applicant', 'institution_type', 'school_type', 'study_level', 'since', 'till', 'annual_fees', 'score', 'out_of', 'sponsored', 'sponsorship_reason'], 'integer'],
            [['institution_name'], 'string', 'min' => 10, 'max' => 35],
            [['institution_name', 'course_name'], 'notNumerical'],
            [['course_name'], 'string', 'min' => 15, 'max' => 60],
            [['exam_no'], $this->isPrimaryOrSecondary() ? 'integer' : 'notNumerical'],
            [['exam_no'], 'string', 'min' => 7, 'max' => 15],
            [['exam_no'], 'examNoLengthCheck'],
            [['created_by', 'modified_by'], 'string', 'max' => 15],
            [['since', 'till'], 'sinceTill'],
            [['study_level', 'since', 'till'], 'educationTimeline'],
            [['score', 'out_of'], 'computeGrade'],
            [['score', 'out_of'], 'required',
                'when' => function () {
                    return $this->isPrimaryOrSecondary();
                },
                'whenClient' => "
                    function () {
                        $('#educationbackground-score, #educationbackground-out_of').blur();
                        return $('#educationbackground-study_level').val() === '" . self::study_level_primary . "' || $('#educationbackground-study_level').val() === '" . self::study_level_secondary . "';
                    }
                "
            ],
            [['grade'], 'required',
                'when' => function () {
                    return $this->gradeRequired();
                },
                'whenClient' => "
                    function () {
                        $('#educationbackground-grade').blur();
                        return $('#educationbackground-study_level').val() !== '" . self::study_level_masters . "' && $('#educationbackground-study_level').val() !== '" . self::study_level_phd . "';
                    }
                "
            ],
            ['sponsorship_reason', 'hasSponsorship'],
            [['grade'], 'string', 'max' => 2],
            [['exam_no'], is_numeric($this->exam_no) ? 'positiveValue' : 'sanitizeString'],
            [['annual_fees', 'score', 'out_of'], 'positiveValue'],
            [['institution_name', 'annual_fees', 'course_name', 'score', 'out_of'], 'sanitizeString'],
            [['created_at', 'modified_at'], 'safe'],
        ];
    }

    /**
     * check exam no. length
     */
    public function examNoLengthCheck() {
        $min = static::examNoLength($this->study_level, $this->till, true);

        $max = static::examNoLength($this->study_level, $this->till, false);

        ($len = strlen($this->exam_no)) < $min || $len > $max ? $this->addError('exam_no', "Exam No. must be of length between $min and $max") : '';
    }

    /**
     * 
     * @return boolean true - is primary or secondary
     */
    public function isPrimaryOrSecondary() {
        return in_array($this->study_level, [self::study_level_primary, self::study_level_secondary]);
    }

    /**
     * study levels to be submitted chronologically
     */
    public function educationTimeline() {
        if (($lvl = $this->study_level) != ($pri = self::study_level_primary))
            if (count($precedingEducation = static::searchEducations($this->applicant, $lvl == ($sec = self::study_level_secondary) ? ($pri) : (in_array($lvl, [$crt = self::study_level_certificate, $dip = self::study_level_diploma, $dgr = self::study_level_degree]) ? ($sec) : ($lvl == ($mst = self::study_level_masters) ? ($dgr) : ($lvl == self::study_level_phd ? $mst : ''))))) > 0 && ($precedingEducation = $precedingEducation[0])) {
                if ($this->since < $precedingEducation->till || (in_array($lvl, [$sec, self::study_level_certificate, self::study_level_diploma, self::study_level_degree]) && $this->since == $precedingEducation->till))
                    $this->addError('since', 'This year conflicts with a prior education level\'s duration');
                else
                if ($this->till <= $precedingEducation->till)
                    $this->addError('since', 'This year conflicts with a prior education level\'s duration');
            } else
                $this->addError('study_level', 'Confirm you\'ve first submitted all preceding study levels');
    }

    /**
     * 
     * @param string $attribute attribute of [[$this]]
     */
    public function sinceTill($attribute) {
        !empty($this->since) && !empty($this->till) && $this->since >= $this->till ? $this->addError($attribute, 'Admission Year cannot be later or same as Exam Year') : '';

        $this->$attribute > date('Y') ? $this->addError($attribute, $this->getAttributeLabel($attribute) . ' cannot be later than this year') : '';
    }

    /**
     * 
     * @param string $attribute attribute of [[$this]]
     */
    public function computeGrade($attribute) {
        if (in_array($this->study_level, [self::study_level_masters, self::study_level_phd]))
            $this->grade = $this->out_of = $this->score = null;
        else
        if (in_array($this->study_level, [self::study_level_primary, self::study_level_secondary]))
            $this->score > $this->out_of ? $this->addError($attribute, 'Score must not exceed Out Of') : $this->grade = StaticMethods::gradeForPoints(static::computeMeanScore($this->score, $this->out_of));
        else
            $this->out_of = $this->score = null;
    }

    /**
     * 
     * @return boolean true - grade is required
     */
    public function gradeRequired() {
        return !in_array($this->study_level, [self::study_level_masters, self::study_level_phd]);
    }
    
    /**
     * 
     * @return boolean true - is sponsored
     */
    public function isSponsored() {
        return $this->sponsored == self::sponsored_yes;
    }
    
    /**
     * 
     * @return boolean true - is sponsored by reason
     */
    public function isSponsoredByReason() {
        return $this->sponsorship_reason != self::sponsor_reason_none;
    }
    
    /**
     * sponsorship must have valid reason
     */
    public function hasSponsorship() {
        $this->isSponsored() && !$this->isSponsoredByReason() ? $this->addError('sponsorship_reason', 'Not a valid reason for sponsorship') : '';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'applicant' => Yii::t('app', 'Applicant'),
            'institution_name' => Yii::t('app', 'Institution Name'),
            'institution_type' => Yii::t('app', 'Institution Type'),
            'school_type' => Yii::t('app', 'Public/Private'),
            'study_level' => Yii::t('app', 'Study Level'),
            'course_name' => Yii::t('app', 'Course Name'),
            'since' => Yii::t('app', 'Admission Year'),
            'till' => Yii::t('app', 'Examination Year'),
            'annual_fees' => Yii::t('app', 'Annual Fees'),
            'exam_no' => Yii::t('app', 'Examination No.'),
            'score' => Yii::t('app', 'Marks/Points'),
            'out_of' => Yii::t('app', 'Out Of'),
            'grade' => Yii::t('app', 'Grade/Merit'),
            'sponsored' => Yii::t('app', 'Sponsored'),
            'sponsorship_reason' => Yii::t('app', 'Sponsorship Reason'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_at' => Yii::t('app', 'Modified At')
        ];
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\activeQueries\EducationBackgroundQuery the active query used by this AR class.
     */
    public static function find() {
        return new \frontend\modules\client\modules\student\activeQueries\EducationBackgroundQuery(get_called_class());
    }

    /**
     * 
     * @param integer $pk education id
     * @return EducationBackground model
     */
    public static function returnEducation($pk) {
        return static::find()->byPk($pk);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $study_level study level
     * @return EducationBackground models
     */
    public static function searchEducations($applicant, $study_level) {
        return static::find()->searchEducations($applicant, $study_level);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $study_level study level
     * @return EducationBackground model
     */
    public static function newEducation($applicant, $study_level) {
        $model = new EducationBackground;

        $model->applicant = $applicant;

        $model->study_level = $study_level;

        $model->school_type = self::school_type_public;

        if (in_array($lvl = $model->study_level, [$pri = self::study_level_primary, self::study_level_secondary])) {
            $model->institution_type = $lvl == $pri ? self::inst_type_primary : self::inst_type_secondary;
            $model->course_name = $lvl == $pri ? self::primary_cert : self::secondary_cert;
            $model->out_of = static::outOfs($lvl, null);
        }

        $educationYears = static::admissionAndExaminationYears($model->applicant, $model->study_level);

        $model->since = $educationYears[0];

        $model->till = $educationYears[1];

        $model->sponsored = self::sponsored_no;
        
        $model->sponsorship_reason = self::sponsor_reason_none;

        $model->created_by = Yii::$app->user->identity->username;

        return $model;
    }

    /**
     * @param integer $id education id
     * @param integer $applicant applicant id
     * @param integer $study_level study level
     * @return EducationBackground model
     */
    public static function educationToLoad($id, $applicant, $study_level) {
        if (empty($id))
            if (count(static::searchEducations($applicant, self::study_level_primary)) < 1)
                $study_level = self::study_level_primary;
            else
            if (count(static::searchEducations($applicant, self::study_level_secondary)) < 1)
                $study_level = self::study_level_secondary;

        is_numeric($study_level) ? '' : $study_level = self::study_level_certificate;

        return is_object($model = self::returnEducation($id)) || (in_array($study_level, [self::study_level_primary, self::study_level_secondary]) && count($models = static::searchEducations($applicant, $study_level)) > 0 && is_object($model = $models[0])) ? $model : static::newEducation($applicant, $study_level);
    }

    /**
     * @param integer $applicant applicant id
     * @return array study levels to load
     */
    public static function levelsToLoad($applicant) {
        $studyLevels = static::studyLevels();

        foreach ($educations = static::searchEducations($applicant, null) as $education) {
            $lvls[$education->study_level] = (empty($lvls[$education->study_level]) ? '' : $lvls[$education->study_level] . ',') . $education->id;
            $levels[$education->id] = [$education->study_level, $studyLevels[$education->study_level]];
        }

        if (empty($lvls[$pri = self::study_level_primary]))
            return [[$pri, $studyLevels[$pri]]];

        if (empty($lvls[$sec = self::study_level_secondary]))
            return [$id = StaticMethods::stringExplode($lvls[$pri], ',')[0] => $levels[$id], [$sec, $studyLevels[$sec]]];

        if (isset($lvls[$phd = self::study_level_phd]) && empty($lvls[$mst = self::study_level_masters]))
            foreach (StaticMethods::stringExplode($lvls[$phd], ',') as $id)
                unset($levels[$id]);

        if (isset($lvls[$mst = self::study_level_masters]) && empty($lvls[$dgr = self::study_level_degree]))
            foreach (StaticMethods::stringExplode($lvls[$mst], ',') as $id)
                unset($levels[$id]);

        return empty($levels) ? [[$pri, $studyLevels[$pri]]] : $levels;
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
     * @param integer $study_level study level
     * @param integer $year exam year
     * @return int highest score
     */
    public static function outOfs($study_level, $year) {
        if ($study_level == self::study_level_primary) {
            if (empty($year) || $year > 1999)
                return 500;

            return 700;
        }

        if ($study_level == self::study_level_secondary)
            return 84;
    }

    /**
     * 
     * @param integer $study_level study level
     * @param integer $year examination year
     * @param boolean $min true - minimum length
     * @return int minimum length of exam no
     */
    public static function examNoLength($study_level, $year, $min) {
        if (in_array($study_level, [self::study_level_primary, self::study_level_secondary]))
            return empty($year) || $year < 2012 ? 9 : 11;

        return $min ? 7 : 15;
    }

    /**
     * 
     * @param integer $study_level study level
     * @return integer study duration
     */
    public static function studyLevelDurations($study_level) {
        switch ($study_level) {
            case self::study_level_primary: return 10;
            case self::study_level_secondary: return 7;
            case self::study_level_certificate: return 5;
            case self::study_level_diploma: return 6;
            case self::study_level_degree: return 7;
            case self::study_level_masters: return 20;
            case self::study_level_phd: return 29;

            default:
                break;
        }
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $study_level study level
     * @return array since, till
     */
    public static function admissionAndExaminationYears($applicant, $study_level) {
        $applicantModel = Applicants::returnApplicant($applicant);

        $yob = substr($applicantModel->dob, 0, 4);

        $duration = static::studyLevelDurations($study_level);

        if ($study_level == self::study_level_primary)
            return [$since = $yob + 5, $since + $duration];

        count($primaryEducation = static::searchEducations($applicant, self::study_level_primary)) > 0 && ($primaryEducation = $primaryEducation[0]);

        if ($study_level == self::study_level_secondary)
            return empty($primaryEducation->till) ? [$since = $yob + 13, $since + $duration] : [$since = $primaryEducation->till + 1, $since + $duration];

        count($secondaryEducation = static::searchEducations($applicant, self::study_level_secondary)) > 0 && ($secondaryEducation = $secondaryEducation[0]);

        if (in_array($study_level, [self::study_level_certificate, self::study_level_diploma, self::study_level_degree]))
            return empty($secondaryEducation->till) ? (empty($primaryEducation->till) ? [$since = $yob + 17, $since + $duration] : [$since = $primaryEducation->till + 5, $since + $duration]) : ([$since = $secondaryEducation->till + 1, $since + $duration]);

        count($degreeEducation = static::searchEducations($applicant, self::study_level_degree)) > 0 && ($degreeEducation = $degreeEducation[0]);

        if ($study_level == self::study_level_masters)
            return empty($degreeEducation->till) ? (empty($secondaryEducation->till) ? (empty($primaryEducation->till) ? [$since = $yob + 20, $since + $duration] : [$since = $primaryEducation->till + 8, $since + $duration]) : ([$since = $secondaryEducation->till + 4, $since + $duration])) : ([$since = $degreeEducation->till, $since + $duration]);

        count($mastersEducation = static::searchEducations($applicant, self::study_level_masters)) > 0 && ($mastersEducation = $mastersEducation[0]);

        if ($study_level == self::study_level_phd)
            return empty($mastersEducation->till) ? (empty($degreeEducation->till) ? (empty($secondaryEducation->till) ? (empty($primaryEducation->till) ? [$since = $yob + 21, $since + $duration] : [$since = $primaryEducation->till + 9, $since + $duration]) : ([$since = $secondaryEducation->till + 5, $since + $duration])) : ([$since = $degreeEducation->till + 1, $since + $duration])) : ([$since = $mastersEducation->till, $since + $duration]);
    }

    /**
     * 
     * @param integer $score score
     * @param integer $out_of out of
     * @return double mean score
     */
    public static function computeMeanScore($score, $out_of) {
        return !is_numeric($score) || !is_numeric($out_of) || empty($out_of) || $score > $out_of ? false : $score / $out_of * 12;
    }

    /**
     * 
     * @param integer $score score
     * @param integer $out_of out of
     * @return string mean grade
     */
    public static function theGrade($score, $out_of) {
        return StaticMethods::gradeForPoints(static::computeMeanScore($score, $out_of));
    }

    /**
     * 
     * @return array institution types
     */
    public static function institutionTypes() {
        return [
            self::inst_type_primary => 'Primary',
            self::inst_type_secondary => 'Secondary',
            self::inst_type_tertiary => 'Tertiary Institution',
            self::inst_type_technical => 'Technical Training College',
            self::inst_type_polytechnic => 'Polytechnic',
            self::inst_type_technology => 'Institute of Technology',
            self::inst_type_medical => 'Medical Training College',
            self::inst_type_university => 'University',
        ];
    }

    /**
     * 
     * @return array school types
     */
    public static function schoolTypes() {
        return [
            self::school_type_public => 'Public',
            self::school_type_private => 'Private',
            self::school_type_faith_based => 'Faith Based',
            self::school_type_community_based => 'Community Based'
        ];
    }

    /**
     * 
     * @return array study levels
     */
    public static function studyLevels() {
        return [
            self::study_level_primary => 'Primary Education',
            self::study_level_secondary => 'Secondary Education',
            self::study_level_certificate => 'Cerificate',
            self::study_level_diploma => 'Diploma',
            self::study_level_degree => 'Degree',
            self::study_level_masters => 'Masters',
            self::study_level_phd => 'PHD'
        ];
    }

    /**
     * 
     * @return array sponsoreds
     */
    public static function sponsoreds() {
        return [self::sponsored_no => 'No', self::sponsored_yes => 'Yes'];
    }

    /**
     * 
     * @return array sponsorship reasons
     */
    public static function sponsorshipReasons() {
        return [self::sponsor_reason_none => 'Not Applicable', self::sponsor_reason_poor => 'Poverty', self::sponsor_reason_orphan => 'Orphan', self::sponsor_reason_scholarship => 'Academic Scholarship'];
    }

    /**
     * 
     * @param integer $study_level study level
     * @return array merits levels
     */
    public static function merits($study_level) {
        if (in_array($study_level, [self::study_level_primary, self::study_level_secondary]))
            return StaticMethods::gradesForDropDown();

        if (in_array($study_level, [self::study_level_certificate, self::study_level_diploma]))
            return StaticMethods::certificateAndDiplomaMerits();

        if ($study_level == self::study_level_degree)
            return StaticMethods::degreeMerits();

        return [];
    }

    /**
     * 
     * @param integer $study_level study level
     * @return array institution types
     */
    public static function institutionTypesToDisplay($study_level) {
        $institutionTypes = static::institutionTypes();

        if (in_array($study_level, [$pri = self::study_level_primary, $sec = self::study_level_secondary, $dgr = self::study_level_degree, $mst = self::study_level_masters, $phd = self::study_level_phd])) {
            foreach ($institutionTypes as $i => $institutionType)
                if (($study_level == $pri && $i != self::inst_type_primary) || ($study_level == $sec && $i != self::inst_type_secondary) || (in_array($study_level, [$dgr, $mst, $phd]) && $i != self::inst_type_university))
                    unset($institutionTypes[$i]);
        } else {
            unset($institutionTypes[self::inst_type_primary]);
            unset($institutionTypes[self::inst_type_secondary]);
        }

        return $institutionTypes;
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $study_level study level
     * @return array study levels
     */
    public static function studyLevelsToDisplay($applicant, $study_level) {
        $studyLevels = static::studyLevels();

        if (in_array($study_level, [$pri = self::study_level_primary, $sec = self::study_level_secondary])) {
            foreach ($studyLevels as $j => $studyLevel)
                if ($j != $study_level)
                    unset($studyLevels[$j]);
        } else {
            unset($studyLevels[$pri]);
            unset($studyLevels[$sec]);

            if (count(static::searchEducations($applicant, $mst = self::study_level_masters)) < 1)
                unset($studyLevels[self::study_level_phd]);

            if (count(static::searchEducations($applicant, $dgr = self::study_level_degree)) < 1)
                unset($studyLevels[$mst]);

            if (count(static::searchEducations($applicant, $sec = self::study_level_secondary)) < 1) {
                unset($studyLevels[self::study_level_certificate]);
                unset($studyLevels[self::study_level_diploma]);
                unset($studyLevels[$dgr]);
            }
        }

        return $studyLevels;
    }

}
