<?php

namespace common\activeQueries;

use common\models\LmBanks;

/**
 * This is the ActiveQuery class for [[\common\models\LmBanks]].
 *
 * @see \common\models\LmBanks
 */
class LmBanksQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \common\models\LmBanks[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\LmBanks|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $BANKCODE bank code
     * @param integer $DISPLAYONLINE display online
     * @param string $oneOrAll one or all
     * @return LmBanks ActiveRecord(s)
     */
    public function searchBanks($BANKCODE, $DISPLAYONLINE, $oneOrAll) {
        return $this->where(
                        'BANKCODE > 0' .
                        (empty($BANKCODE) ? '' : " && BANKCODE = '$BANKCODE'") .
                        (is_numeric($DISPLAYONLINE) ? " && DISPLAYONLINE = '$DISPLAYONLINE'" : '')
                )->orderBy('NAME asc')->$oneOrAll();
    }

}
