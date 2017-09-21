<?php

namespace frontend\models;
use frontend\models\Finances;
use Yii;
use frontend\models\Walletgroups;
use yii\helpers\ArrayHelper;
use common\models\User;
/**
 * This is the model class for table "wallets".
 *
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property integer $walletgroup_id
 *
 * @property User $user
 * @property Walletgroups $wallet
 */
class Wallets extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'wallets';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'user_id', 'walletgroup_id'], 'required'],
			[['user_id', 'walletgroup_id'], 'integer'],
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
			'name' => 'Название кошелька',
			'user_id' => 'Юзер',
			'walletgroup_id' => 'Тип кошелька',
			'deleted' => 'Удалён',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getWalletgroup()
	{
		return $this->hasOne(Walletgroups::className(), ['id' => 'walletgroup_id']);
	}

	public function getWtype() // получаем категории по их id
	{
		$walletgroup = $this->walletgroup;
		return $walletgroup ? $walletgroup->name : '';
	}

	/**
	 * Получает текущее количество денег на кошельке
	 * @return integer
	 */
	public static function getWamount($currency_id, $wallets_id)
	{
		 $value= Finances::find()
		->select('my_new_wallet_balance')
		->Where(['user_id' => Yii::$app->user->identity->id])
		->andWhere(['wallet_id' => $wallets_id])
		->andWhere(['currency_id' => $currency_id])
		->andWhere(['deleted' => false])
		->orderBy(['date' => SORT_DESC, 'id' => SORT_DESC])
		->one();

		return $value['my_new_wallet_balance'];

	}

	public static function findWallet($id)
	{
		$query_wallet=Wallets::find()
		->andWhere(['user_id' => $id])
		->andWhere(['deleted' => false])
		->all();

		$userWallets=ArrayHelper::map($query_wallet,'id','name');

		return $userWallets;
	}

	public static function findMyWallets($id)
	{
		$myWallets=Wallets::find()
		->andWhere(['user_id'=>$id])
		->select('id')
		->column();

		return $myWallets;
	}

	public static function getAllWalletIDs()
	{
		$allWallets=Wallets::find()
		->select('id')
		->column();

		return $allWallets;
	}

	public static function getWalletgroupID($id)
	{
		$walletgroup_id = Wallets::find()
		->where(['id' => $id])
		->select('walletgroup_id')
		->asArray()
		->one(); //получаем id типа кошелька

		return $walletgroup_id;
	}

	public static function getWalletIDs($id, $user_id=false)
	{
		if (!$user_id) $user_id = User::findAlla();

		$walletIDs = Wallets::find()
		->where(['walletgroup_id' => $id])
		->andWhere(['user_id' => $user_id])
		->select('id')
		->column(); //получаем массив id кошельков

		return $walletIDs;
	}

	public static function getWalletIDsDirectly($id, $user_id=false)
	{
		$walletIDs = self::getWalletIDs(self::getWalletgroupID($id), $user_id);

		return $walletIDs;
	}

	public static function getWalletLastRecord($wallet_id, $currency_id)
	{

		$lastRecord = Finances::find()
		->where(['wallet_id' => $wallet_id])
		->andWhere(['currency_id' => $currency_id])
		->andWhere(['deleted' => false])
		->orderBy([
			'date' => SORT_DESC,
			'id'=> SORT_DESC
		])
		->one();

		return $lastRecord;
	}

	public static function getWalletgroupLastRecord($wallet_id, $currency_id)
	{

		$walletIDs = self::getWalletIDsDirectly($wallet_id);

		$lastRecord = Finances::find()
		->where(['wallet_id' => $walletIDs])
		->where(['currency_id' => $currency_id])
		->andWhere(['deleted' => false])
		->orderBy([
			'date' => SORT_DESC,
			'id'=> SORT_DESC
		])
		->one();

		return $lastRecord;
	}

}
