<?php

namespace common\activeQueries;
use common\models\Wards;

/**
 * This is the ActiveQuery class for [[\common\models\Wards]].
 *
 * @see \common\models\Wards
 */
class WardsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \common\models\Wards[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Wards|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
    
    /**
     * 
     * @return Wards ActiveRecords
     */
    public function allWards() {
        return $this->orderBy("name asc")->all();
    }
    
    /**
     * 
     * @param integer $constituency constituency id
     * @return Wards ActiveRecords
     */
    public function wardsForConstituency($constituency) {
        return $this->where("constituency = '$constituency'")->orderBy("name asc")->all();
    }

}
