<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Settingsuser]].
 *
 * @see Settingsuser
 */
class SettingsuserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Settingsuser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Settingsuser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
