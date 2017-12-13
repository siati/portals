<?php

namespace frontend\modules\business\activeQueries;

use frontend\modules\business\models\ApplicationParts;

/**
 * This is the ActiveQuery class for [[\frontend\modules\business\models\ApplicationParts]].
 *
 * @see \frontend\modules\business\models\ApplicationParts
 */
class ApplicationPartsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ApplicationParts[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ApplicationParts|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $application application id
     * @param integer $appeal is appeal
     * @param string $part application part
     * @param string $oneOrAll one or all
     * @return ApplicationParts ActiveRecord(s)
     */
    public function searchParts($application, $appeal, $part, $order, $oneOrAll) {
        return
                $this->where(
                        'id > 0' .
                        (empty($application) ? '' : " && application = '$application'") .
                        (is_numeric($appeal) ? " && appeal = '$appeal'" : '') .
                        (empty($part) ? '' : " && part = '$part'") .
                        (is_numeric($order) ? (empty($order) ? " && `order` * 1 < 1" : " && `order` * 1 > 0") : (''))
                )->orderBy('`order` asc')->$oneOrAll()
        ;
    }

}
