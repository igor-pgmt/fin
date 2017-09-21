<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[Settingsadmin]].
 *
 * @see Settingsadmin
 */
class SettingsadminQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Settingsadmin[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Settingsadmin|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
