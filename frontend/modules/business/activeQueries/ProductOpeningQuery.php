<?php

namespace frontend\modules\business\activeQueries;

use frontend\modules\business\models\ProductOpening;

/**
 * This is the ActiveQuery class for [[\frontend\modules\business\models\ProductOpening]].
 *
 * @see \frontend\modules\business\models\ProductOpening
 */
class ProductOpeningQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ProductOpening[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ProductOpening|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $product product id
     * @param string $academic_year academic year
     * @param integer $subsequent subsequent: 0 - false, otherwise true
     * @param string $since opening date
     * @param string $till closing date
     * @param string $grace closing for printing
     * @param integer $min_apps minimum applications accepted
     * @param integer $max_apps maximum applications accepted
     * @param string $oneOrAll one or all
     * @return ProductOpening ActiveRecord(s)
     */
    public function searchOpening($product, $academic_year, $subsequent, $since, $till, $grace, $min_apps, $max_apps, $oneOrAll) {
        return $this->where(
                        'id > 0' .
                        (empty($product) ? '' : " && product = '$product'") .
                        (empty($academic_year) ? '' : " && academic_year = '$academic_year'") .
                        (empty($subsequent) ? '' : " && subsequent = '$subsequent'") .
                        (empty($since) ? '' : " && since >= '$since'") .
                        (empty($till) ? '' : " && till <= '$till'") .
                        (empty($grace) ? '' : " && grace <= '$grace'") .
                        (empty($min_apps) ? '' : " && min_apps = '$min_apps'") .
                        (empty($max_apps) ? '' : " && max_apps = '$max_apps'")
                )->orderBy('product asc, academic_year desc')->$oneOrAll();
    }

}
