<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use app\models\ActiveRecord;

/**
 * This is the model class for making related records of table "finances".
 *
 * @property integer $money
 * @property integer $user_id
 * @property integer $currency_id
 * @property integer $old_wallet_id
 * @property integer $new_wallet_id
 * @property date	 $date
 * @property string $comment
 */
class Allocation extends Model
{

	public $money;
	public $user_id;
	public $currency_id;
	public $old_wallet_id;
	public $new_wallet_id;
	public $date;
	public $comment;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['currency_id', 'user_id', 'old_wallet_id', 'new_wallet_id'], 'integer'],
			[['money'], 'integer', 'min'=>1 ],
			[['date'], 'default', 'value' => date('YYYY-mm-dd')],
			[['comment'], 'string', 'max' => 255],

						[
				[
					'money',
					'currency_id',
					'user_id',
					'old_wallet_id',
					'new_wallet_id'
				],
			'required'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'money' => 'Сумма',
			'currency_id' => 'Валюта',
			'user_id' => 'User ID',
			'old_wallet_id' => 'Откуда',
			'new_wallet_id' => 'Куда',
			'date' => 'Дата',
			'comment' => 'Комментарий',
		];
	}


	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getWallet()
	{
		return $this->hasOne(MyWallet::className(), ['id' => 'wallet_id']);
	}

	public function getStor() // получаем кошельки по их id
	{
		$wallet = $this->wallet;
		return $wallet ? $wallet->name : '';
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}


}
