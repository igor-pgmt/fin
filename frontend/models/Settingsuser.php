<?php

namespace frontend\models;

use wowkaster\serializeAttributes\SerializeAttributesBehavior;
use common\models\User;
use Yii;

/**
 * This is the model class for table "settingsuser".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name_id
 * @property string $name_money
 * @property string $name_motion_id
 * @property string $name_category_id
 * @property string $name_user_id
 * @property string $name_wallet_id
 * @property string $name_date
 * @property string $name_comment
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
 * @property string $name_related_record
 * @property string $approve
 * @property boolean $id_id
 * @property boolean $money
 * @property boolean $motion_id
 * @property boolean $category_id
 * @property boolean $user_id_id
 * @property boolean $wallet_id
 * @property boolean $date
 * @property boolean $comment
 * @property boolean $my_old_wallet_balance
 * @property boolean $my_old_wgsumma_balance
 * @property boolean $my_old_summa_balance
 * @property boolean $my_new_wallet_balance
 * @property boolean $my_new_wgsumma_balance
 * @property boolean $my_new_summa_balance
 * @property boolean $our_old_wgsumma_balance
 * @property boolean $our_old_summa_balance
 * @property boolean $our_new_wgsumma_balance
 * @property boolean $our_new_summa_balance
 * @property boolean $related_record
 * @property boolean $approve
 * @property boolean $timestamp
 * @property string $color_row1
 * @property string $color_row2
 * @property string $color_select
 * @property string $color_approve
 * @property string $color_incomes
 * @property string $color_expenses
 */
class Settingsuser extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'settingsuser';
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
				[
					[
						'user_id',
						'settings',
					],
				'required'],

			[['id', 'user_id'], 'integer'],
		   [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
		   [['settings', 'timezone'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'timezone' => 'Часовой пояс',
			'settings' => 'Settings',
		];
	}

	/**
	 * @inheritdoc
	 * @return SettingsuserQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new SettingsuserQuery(get_called_class());
	}
}
