<?php

namespace frontend\modules\client\modules\student\activeQueries;

use frontend\modules\client\modules\student\models\ApplicantSponsors;

/**
 * This is the ActiveQuery class for [[\frontend\modules\client\modules\student\models\ApplicantSponsors]].
 *
 * @see \frontend\modules\client\modules\student\models\ApplicantSponsors
 */
class ApplicantSponsorsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantSponsors[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantSponsors|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
    
    /**
     * 
     * @param integer $applicant applicant id
     * @return ApplicantSponsors ActiveRecords
     */
    public function sponsorsForApplicant($applicant) {
        return $this->where("applicant = '$applicant'")->orderBy('name asc')->all();
    }
    
    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $study_level study level(s, comma separated)
     * @param string $oneOrAll one or all
     * @return ApplicantSponsors ActiveRecord(s)
     */
    public function forApplicantAndStudyLevel($applicant, $study_level, $oneOrAll) {
        return $this->where("applicant = '$applicant' && study_level in ($study_level)")->orderBy('name asc')->$oneOrAll();
    }
    
    /**
     * 
     * @param integer $id sponsor id
     * @param string $name sponsor name
     * @return ApplicantSponsors ActiveRecord
     */
    public function distinctSponsor($id, $name) {
        return $this->where("name = '$name' && id != '$id'")->one();
    }

}
