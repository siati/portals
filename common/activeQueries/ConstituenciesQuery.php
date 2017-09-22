<?php

namespace common\activeQueries;

use common\models\Constituencies;

/**
 * This is the ActiveQuery class for [[\common\models\Constituencies]].
 *
 * @see \common\models\Constituencies
 */
class ConstituenciesQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \common\models\Constituencies[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Constituencies|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @return Constituencies ActiveRecords
     */
    public function allConstituencies() {
        return $this->orderBy("name asc")->all();
    }

    /**
     * 
     * @param integer $county county id
     * @return Constituencies ActiveRecords
     */
    public function constituenciesForCounty($county) {
        return $this->where("county = '$county'")->orderBy("name asc")->all();
    }

}
