<?php

namespace frontend\modules\business\activeQueries;

use frontend\modules\business\models\ApplicationPartElements;

/**
 * This is the ActiveQuery class for [[\frontend\modules\business\models\ApplicationPartElements]].
 *
 * @see \frontend\modules\business\models\ApplicationPartElements
 */
class ApplicationPartElementsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ApplicationPartElements[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ApplicationPartElements|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    /**
     * 
     * @param integer $part part id
     * @param string $element element
     * @param string $narration narration
     * @param integer $active active: 1 - yes, 0 - no
     * @param string $oneOrAll one or all
     * @return ApplicationPartElements model(s)
     */
    public function searchElements($part, $element, $narration, $active, $oneOrAll) {
        return
                $this->where(
                        'id > 0' .
                        (empty($part) ? '' : " && part = '$part'") .
                        (empty($element) ? '' : " && element = '$element'") .
                        (empty($narration) ? '' : " && narration like '%$narration%'") .
                        (is_numeric($active) ? " && active = '$active'" : '')
                )->orderBy('`order` asc')->$oneOrAll()
        ;
    }

}
