<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "currencies".
 *
 * @property integer $id
 * @property integer $name
 * @property integer $name_g
 */
class Currencies extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currencies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_g'], 'required'],
            [['deleted'], 'boolean'],
            [['name'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Валюта в именительном падеже',
            'name_g' => 'Валюта в родительном падеже',
            'deleted' => 'Удалена',
        ];
    }

        public static function findCurrencies()
    {
        //use app\models\Currencies;
        $query_currencies=Currencies::find()
        ->Where(['deleted'=>false])
        ->all();

        //use yii\helpers\ArrayHelper;
        $currencies=ArrayHelper::map($query_currencies, 'id', 'name');
        return $currencies;
    }

}
