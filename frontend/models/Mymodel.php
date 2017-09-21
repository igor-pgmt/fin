<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use app\models\ActiveRecord;

/**
 * This is the model class for table "finances".
 *
 * @property integer $id
 * @property integer $money
 * @property integer $motion_type
 * @property integer $category_id
 * @property integer $user_id
 * @property integer $wallet_id
 * @property integer $date
 * @property string $comment
 * @property integer $my_old_wallet_balance
 * @property integer $my_old_summa_balance
 * @property integer $my_new_wallet_balance
 * @property integer $my_new_summa_balance
 * @property integer $our_old_wgsumma_balance
 * @property integer $our_old_summa_balance
 * @property integer $our_new_wgsumma_balance
 * @property integer $our_new_summa_balance
 * @property integer $timestamp
 * @property integer $deleted
 */
class Mymodel extends \yii\base\Model
{


    /**
     * @inheritdoc
     */
/*    public static function tableName()
    {
        return 'finances';
    }*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['id', 'money', 'motion_type', 'category_id', 'user_id', 'wallet_id', 'date', 'my_old_wallet_balance', 'my_old_summa_balance', 'my_new_wallet_balance', 'my_new_summa_balance', 'our_old_wgsumma_balance', 'our_old_summa_balance', 'our_new_wgsumma_balance', 'our_new_summa_balance', 'timestamp', 'deleted'], 'required'],

            [['id', 'money', 'motion_type', 'category_id', 'user_id', 'wallet_id', 'my_old_wallet_balance', 'my_old_summa_balance', 'my_new_wallet_balance', 'my_new_summa_balance', 'our_old_wgsumma_balance', 'our_old_summa_balance', 'our_new_wgsumma_balance', 'our_new_summa_balance', 'deleted'], 'integer'],
            [['date', 'timestamp'], 'safe'],
            [['comment'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'money' => 'Сумма',
            'motion_type' => 'Motion Type',
            'category_id' => 'Категория',
            'user_id' => 'User ID',
            'wallet_id' => 'Кошелёк',
            'date' => 'Дата',
            'comment' => 'Комментарий',
            'my_old_wallet_balance' => 'My Old Wallet Balance',
            'my_old_wgsumma_balance' => 'My Old Walletgroup Summa Balance',
            'my_old_summa_balance' => 'My Old Summa Balance',
            'my_new_wallet_balance' => 'My New Wallet Balance',
            'my_new_wgsumma_balance' => 'My New Walletgroup Summa Balance',
            'my_new_summa_balance' => 'My New Summa Balance',
            'our_old_wgsumma_balance' => 'Our Old Walletgroup Balance',
            'our_old_summa_balance' => 'Our Old Summa Balance',
            'our_new_wgsumma_balance' => 'Our New Walletgroup Balance',
            'our_new_summa_balance' => 'Our New Summa Balance',
            'timestamp' => 'Timestamp',
            'deleted' => 'Удалён',
        ];
    }
}
