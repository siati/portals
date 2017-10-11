<?php

namespace frontend\modules\business\activeQueries;

/**
 * This is the ActiveQuery class for [[\frontend\modules\business\models\ProductAccessPropertyItems]].
 *
 * @see \frontend\modules\business\models\ProductAccessPropertyItems
 */
class ProductAccessPropertyItemsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ProductAccessPropertyItems[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\modules\business\models\ProductAccessPropertyItems|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
