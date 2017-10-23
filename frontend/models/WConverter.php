<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\models\ActiveRecord;

/**
 * This is the model class for table "finances".
 *
 * @property integer $money
 * @property integer $user_id
 * @property integer $wallet_id
 * @property string $date
 * @property string $comment
 * @property integer $my_old_wallet_balance
 * @property integer $my_old_wgsumma_balance
 * @property integer $my_old_summa_balance
 * @property integer $my_new_wallet_balance
 * @property integer $my_new_wgsumma_balance
 * @property integer $my_new_summa_balance
 * @property integer $our_old_wgsumma_balance
 * @property integer $our_old_summa_balance
 * @property integer $our_new_wgsumma_balance
 * @property integer $our_new_summa_balance
 * @property integer $timestamp
 * @property integer $deleted
 *
 * @property Motions $motionType
 * @property Wallet $wallet
 * @property User $user
 * @property Categories $category
 */
class WConverter extends Model
{

    public $money;
    public $old_currency_id;
    public $new_currency_id;
    public $action;
    public $coefficient;
    public $user_id;
    public $old_wallet_id;
    public $new_wallet_id;
    public $date;
    public $comment;
    public $tagValues;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money', 'old_currency_id', 'new_currency_id', 'coefficient', 'user_id', 'old_wallet_id', 'new_wallet_id', 'date', 'action'], 'required'],
            [['old_currency_id', 'new_currency_id', 'user_id', 'old_wallet_id', 'new_wallet_id', 'action'], 'integer'],
            [['money'], 'integer', 'integerOnly' => false, ],
            [['coefficient'], 'double', 'min'=>0 ],
            [['date'], 'date','format' => 'YYYY-mm-dd'],
            [['comment'], 'string', 'max' => 255],
            [['tagValues'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'money' => 'Сумма',
            'old_currency_id' => 'Старая валюта',
            'new_currency_id' => 'Новая валюта',
            'coefficient' => 'Курс',
            'action' => 'Действие',
            'user_id' => 'User ID',
            'old_wallet_id' => 'Откуда',
            'new_wallet_id' => 'Куда',
            'date' => 'Дата',
            'comment' => 'Комментарий',
        ];
    }

}
