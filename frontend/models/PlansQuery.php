<?php

namespace frontend\models;

use creocoder\taggable\TaggableQueryBehavior;

/**
 * This is the ActiveQuery class for [[Plans]].
 *
 * @see Plans
 */
class PlansQuery extends \yii\db\ActiveQuery
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
     * @return Plans[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Plans|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
