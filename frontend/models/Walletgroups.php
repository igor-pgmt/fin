<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "walletgroups".
 *
 * @property integer $id
 * @property string $name
 */
class Walletgroups extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'walletgroups';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name'], 'required'],
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
			'name' => 'Тип кошелька',
			'deleted' => 'удалён',
		];
	}


	public static function findWalletgroups()
	{
		//use app\models\Walletgroups;
		$query_walletgroups=Walletgroups::find()
		->Where(['deleted'=>false])
		->all();
		//use yii\helpers\ArrayHelper;
		$walletgroups=ArrayHelper::map($query_walletgroups,'id','name');
		return $walletgroups;
	}

}
