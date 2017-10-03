<?php

namespace common\activeQueries;
use common\models\LmBankBranch;

/**
 * This is the ActiveQuery class for [[\common\models\LmBankBranch]].
 *
 * @see \common\models\LmBankBranch
 */
class LmBankBranchQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \common\models\LmBankBranch[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\LmBankBranch|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $BANKCODE bank code
     * @param integer $BRANCHCODE branch code
     * @param string $oneOrAll one or all
     * @return LmBankBranch ActiveRecord(s)
     */
    public function searchBranches($BANKCODE, $BRANCHCODE, $oneOrAll) {
        return $this->where(
                        "BANKCODE = '$BANKCODE' && BRANCHCODE > 0" .
                        (empty($BRANCHCODE) ? '' : " && BRANCHCODE = '$BRANCHCODE'")
                )->orderBy('BRANCHNAME asc')->$oneOrAll();
    }

}
