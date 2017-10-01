<?php

namespace frontend\modules\client\modules\student\activeQueries;

use frontend\modules\client\modules\student\models\ApplicantsResidence;

/**
 * This is the ActiveQuery class for [[\frontend\modules\client\modules\student\models\ApplicantsResidence]].
 *
 * @see \frontend\modules\client\modules\student\models\ApplicantsResidence
 */
class ApplicantsResidenceQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsResidence[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\ApplicantsResidence|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $applicant applicant id
     * @param integer $county county
     * @param integer $sub_county sub county
     * @param integer $constituency constituency
     * @param integer $ward ward
     * @param string $location location
     * @param string $sub_location sub location
     * @param string $village village
     * @param string $apartment apartment
     * @param string $nearest_primary nearest primary
     * @param string $oneOrAll one or all
     * @return ApplicantsResidence ActiveRecord[s]
     */
    public function searchResidences($applicant, $county, $sub_county, $constituency, $ward, $location, $sub_location, $village, $apartment, $nearest_primary, $oneOrAll) {
        return $this->where(
                        'id > 0' .
                        (empty($applicant) ? '' : " && applicant = '$applicant'") .
                        (empty($county) ? '' : " && county = '$county'") .
                        (empty($sub_county) ? '' : " && sub_county = '$sub_county'") .
                        (empty($constituency) ? '' : " && constituency = '$constituency'") .
                        (empty($ward) ? '' : " && ward = '$ward'") .
                        (empty($location) ? '' : " && location like '%$location%'") .
                        (empty($sub_location) ? '' : " && sub_location like '%$sub_location%'") .
                        (empty($village) ? '' : " && village like '%$village%'") .
                        (empty($apartment) ? '' : " && apartment like '%$apartment%'") .
                        (empty($nearest_primary) ? '' : " && nearest_primary like '%$nearest_primary%'")
                )->$oneOrAll();
    }

}
