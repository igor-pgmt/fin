<?php

namespace frontend\models;

use wowkaster\serializeAttributes\SerializeAttributesBehavior;
use Yii;

/**
 * This is the model class for table "settingsadmin".
 *
 * @property integer $registration
 * @property string $name_my_old_wallet_balance
 * @property string $name_my_old_wgsumma_balance
 * @property string $name_my_old_summa_balance
 * @property string $name_my_new_wallet_balance
 * @property string $name_my_new_wgsumma_balance
 * @property string $name_my_new_summa_balance
 * @property string $name_our_old_wgsumma_balance
 * @property string $name_our_old_summa_balance
 * @property string $name_our_new_wgsumma_balance
 * @property string $name_our_new_summa_balance
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
 * @property integer $related_record
 * @property integer $approve
 * @property integer $timestamp
 * @property integer $session_using
 */
class Settingsadmin extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'settingsadmin';
	}

	public function behaviors() {
		return [
			[
				'class' => SerializeAttributesBehavior::className(),
				'convertAttr' => ['settings' => 'json']
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
				//[
					//['settings'],	'required'],

					[['registration', 'session_using'],	'boolean'],
				[['settings', 'timezone'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'registration' => 'Регистрация на сайте',
			'timezone' => 'Часовой пояс',
			'session_using' => 'Использование сессий'
		];
	}

	/**
	 * @inheritdoc
	 * @return SettingsadminQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new SettingsadminQuery(get_called_class());
	}
}
