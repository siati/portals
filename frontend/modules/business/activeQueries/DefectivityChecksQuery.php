<?php

namespace frontend\modules\business\activeQueries;

/**
 * This is the ActiveQuery class for [[\frontend\modules\business\models\DefectivityChecks]].
 *
 * @see \frontend\modules\business\models\DefectivityChecks
 */
class DefectivityChecksQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\DefectivityChecks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\DefectivityChecks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
