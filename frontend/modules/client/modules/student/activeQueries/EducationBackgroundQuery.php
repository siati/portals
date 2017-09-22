<?php

namespace frontend\modules\client\modules\student\activeQueries;

/**
 * This is the ActiveQuery class for [[\frontend\modules\client\modules\student\models\EducationBackground]].
 *
 * @see \frontend\modules\client\modules\student\models\EducationBackground
 */
class EducationBackgroundQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\EducationBackground[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\EducationBackground|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $study_level study level
     * @return EducationBackground ActiveRecords
     */
    public function searchEducations($applicant, $study_level) {
        return $this->where(
                        'id > 0' .
                        (empty($applicant) ? '' : " && applicant = '$applicant'") .
                        (!is_numeric($study_level) ? '' : " && study_level = '$study_level'")
                )->orderBy('till asc')->all();
    }

}
