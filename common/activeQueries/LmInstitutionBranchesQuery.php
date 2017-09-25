<?php

namespace common\activeQueries;
use common\models\LmInstitutionBranches;

/**
 * This is the ActiveQuery class for [[\common\models\LmInstitutionBranches]].
 *
 * @see \common\models\LmInstitutionBranches
 */
class LmInstitutionBranchesQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \common\models\LmInstitutionBranches[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\LmInstitutionBranches|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param string $institution_code institution type
     * @param integer $active active
     * @return LmInstitutionBranches ActiveRecords
     */
    public function searchInstitutions($institution_code, $active) {
        return $this->where(
                        'RECID > 0' .
                        (empty($institution_code) ? '' : " && INSTITUTIONCODE = '$institution_code'") .
                        (is_number($active) ? " && ACTIVE = '$active'" : '')
                )->orderBy('INSTITUTIONBRANCHNAME asc')->all();
    }

}
