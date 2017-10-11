<?php

namespace frontend\modules\business\activeQueries;

use frontend\modules\business\models\Products;

/**
 * This is the ActiveQuery class for [[\frontend\modules\business\models\Products]].
 *
 * @see \frontend\modules\business\models\Products
 */
class ProductsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\Products[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\Products|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param string $code product code
     * @param string $name product name
     * @param string $helb_code HELB code
     * @param string $oneOrAll one or all
     * @return Products ActiveRecord(s)
     */
    public function searchProducts($code, $name, $helb_code, $oneOrAll) {
        return $this->where(
                        'id > 0' .
                        (empty($code) ? '' : " && code = '$code'") .
                        (empty($name) ? '' : " && name like '%$name%'") .
                        (empty($helb_code) ? '' : " && code = '$helb_code'")
                )->orderBy('name asc')->$oneOrAll();
    }

    /**
     * 
     * @param string $attribute attribute of [[$this]]
     * @param string $value value of [[$attribute]]
     * @param integer $id product id
     * @return Products ActiveRecord
     */
    public function distinctNaming($attribute, $value, $id) {
        return $this->where("$attribute = '$value' && id != '$id'")->orderBy('name asc')->one();
    }

}
