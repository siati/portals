<?php

namespace common\activeQueries;
use common\models\PostalCodes;

/**
 * This is the ActiveQuery class for [[\common\models\PostalCodes]].
 *
 * @see \common\models\PostalCodes
 */
class PostalCodesQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \common\models\PostalCodes[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\PostalCodes|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
    
    /**
     * 
     * @return PostalCodes ActiveRecords
     */
    public function allCodes() {
        return $this->orderBy("town asc")->all();
    }

}
