<?php

namespace common\activeQueries;

use common\models\LmBaseEnums;

/**
 * This is the ActiveQuery class for [[\common\models\LmBaseEnums]].
 *
 * @see \common\models\LmBaseEnums
 */
class LmBaseEnumsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \common\models\LmBaseEnums[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\LmBaseEnums|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param string $names comma separated item names
     * @param boolean $distinct true - distinct names
     * @return LmBaseEnums ActiveRecords
     */
    public function mainItems($names, $distinct) {
        return $this->select($distinct ? 'NAME' : '*')->where((empty($names) ? '' : "NAME in ($names)"))->distinct($distinct)->orderBy('NAME asc')->all();
    }

    /**
     * 
     * @param string $name item name
     * @param string $elements comma separated item elements
     * @return LmBaseEnums ActiveRecords
     */
    public function itemElements($name, $elements) {
        return $this->where("NAME = '$name'" . (empty($elements) ? '' : " && ELEMENT in ($elements)"))->all();
    }
    
    /**
     * 
     * @param string $name item name
     * @param string $element item element
     * @return LmBaseEnums ActiveRecord
     */
    public function byNameAndElement($name, $element) {
        return $this->where("NAME = '$name' && ELEMENT = '$element'")->one();
    }
    
    /**
     * 
     * @param string $name item name
     * @param string $value item element value
     * @return LmBaseEnums ActiveRecord
     */
    public function byNameAndValue($name, $value) {
        return $this->where("NAME = '$name' && VALUE = '$value'")->one();
    }

}
