<?php

namespace frontend\models;

use creocoder\taggable\TaggableQueryBehavior;

/**
 * This is the ActiveQuery class for [[Finances]].
 *
 * @see Finances
 */
class FinancesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    //for tagging
    public function behaviors()
    {
        return [
            TaggableQueryBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     * @return Finances[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Finances|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
