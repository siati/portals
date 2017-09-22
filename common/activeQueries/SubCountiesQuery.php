<?php

namespace common\activeQueries;

use common\models\SubCounties;

/**
 * This is the ActiveQuery class for [[\common\models\SubCounties]].
 *
 * @see \common\models\SubCounties
 */
class SubCountiesQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \common\models\SubCounties[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\SubCounties|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @return SubCounties ActiveRecords
     */
    public function allSubcounties() {
        return $this->orderBy("name asc")->all();
    }

    /**
     * 
     * @param integer $county county id
     * @return SubCounties ActiveRecords
     */
    public function subcountiesForCounty($county) {
        return $this->where("county = '$county'")->orderBy("name asc")->all();
    }

}
