<?php

namespace common\activeQueries;

use common\models\LmEmployers;

/**
 * This is the ActiveQuery class for [[\common\models\LmEmployers]].
 *
 * @see \common\models\LmEmployers
 */
class LmEmployersQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \common\models\LmEmployers[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\LmEmployers|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $ACCOUNTNUM accountnum
     * @param string $NAME name
     * @param string $oneOrAll one or all
     * @return LmEmployers ActiveRecord(s)
     */
    public function searchEmployers($ACCOUNTNUM, $NAME, $oneOrAll) {
        return $this->where(
                        "ACCOUNTNUM != ''" .
                        (empty($ACCOUNTNUM) ? '' : " && ACCOUNTNUM = '$ACCOUNTNUM'") .
                        (empty($NAME) ? '' : " && NAME like '%$NAME%'") .
                        " && ACCOUNTNUM is not null && ACCOUNTNUM != '' && NAME is not null && NAME != ''"
                )->orderBy('NAME asc')->$oneOrAll();
    }

}
