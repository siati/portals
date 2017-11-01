<?php

namespace common\activeQueries;

use common\models\LmInstitution;

/**
 * This is the ActiveQuery class for [[\common\models\LmInstitution]].
 *
 * @see \common\models\LmInstitution
 */
class LmInstitutionQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \common\models\LmInstitution[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\LmInstitution|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param string $country country
     * @param string $institution_type institution type
     * @param string $school_type school type
     * @param integer $active active
     * @return LmInstitution ActiveRecords
     */
    public function searchInstitutions($country, $institution_type, $school_type, $active) {
        return $this->where(
                        'RECID > 0' .
                        (empty($country) ? '' : " && COUNTRY = '$country'") .
                        (empty($institution_type) ? '' : " && INSTITUTIONTYPE = '$institution_type'") .
                        (empty($school_type) ? '' : " && SCHOOLTYPE = '$school_type'") .
                        (is_numeric($active) ? " && ACTIVE = '$active'" : '')
                )->orderBy('INSTITUTIONNAME asc')->all();
    }
    
    /**
     * 
     * @param string $institution_code institution code
     * @return LmInstitution ActiveRecord
     */
    public function byInstitutionCode($institution_code) {
        return $this->where("INSTITUTIONCODE = '$institution_code'")->one();
    }

}
