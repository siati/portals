<?php

namespace frontend\modules\business\activeQueries;

/**
 * This is the ActiveQuery class for [[\frontend\modules\business\models\ProductOpeningSettings]].
 *
 * @see \frontend\modules\business\models\ProductOpeningSettings
 */
class ProductOpeningSettingsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ProductOpeningSettings[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ProductOpeningSettings|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $application product opening id
     * @param string $setting setting
     * @param string $name setting name
     * @param string $value setting value
     * @param integer $active active: 0 - false, true
     * @param string $remark setting remark
     * @param string $oneOrAll one or all
     * @return ProductOpeningSettings ActiveRecord(s)
     */
    public function searchSettings($application, $setting, $name, $value, $active, $remark, $oneOrAll) {
        return $this->where(
                        'id > 0' .
                        (empty($application) ? '' : " && application = '$application'") .
                        (empty($setting) ? '' : " && setting = '$setting'") .
                        (empty($name) ? '' : " && name like '%$name%'") .
                        (empty($value) ? '' : " && value = '$value'") .
                        (is_numeric($active) ? " && active = '$active'" : '') .
                        (empty($remark) ? '' : " && remark like '%$remark%'")
                )->$oneOrAll();
    }

}
