<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "currencies".
 *
 * @property integer $id
 * @property integer $currency
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
            [['currency', 'currency_r'], 'required'],
            [['deleted'], 'boolean'],
            [['currency'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'currency' => 'Валюта в именительном падеже',
            'currency_r' => 'Валюта в родительном падеже',
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
        $currencies=ArrayHelper::map($query_currencies, 'id', 'currency');
        return $currencies;
    }

}
