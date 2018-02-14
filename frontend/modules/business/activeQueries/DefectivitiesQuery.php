<?php

namespace frontend\modules\business\activeQueries;

/**
 * This is the ActiveQuery class for [[\frontend\modules\business\models\Defectivities]].
 *
 * @see \frontend\modules\business\models\Defectivities
 */
class DefectivitiesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\Defectivities[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\Defectivities|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
