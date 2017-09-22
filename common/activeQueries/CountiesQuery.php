<?php

namespace common\activeQueries;
use common\models\Counties;

/**
 * This is the ActiveQuery class for [[\common\models\Counties]].
 *
 * @see \common\models\Counties
 */
class CountiesQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \common\models\Counties[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Counties|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
    
    /**
     * 
     * @return Counties ActiveRecords
     */
    public function allCounties() {
        return $this->orderBy("name asc")->all();
    }

}
