<?php

namespace frontend\modules\client\modules\student\activeQueries;

/**
 * This is the ActiveQuery class for [[\frontend\modules\client\modules\student\models\Applicants]].
 *
 * @see \frontend\modules\client\modules\student\models\Applicants
 */
class ApplicantsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\Applicants[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\client\modules\student\models\Applicants|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
