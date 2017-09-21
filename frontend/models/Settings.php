<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
/**
 * This is the model class for tables "settingsuser" and "settingsadmin".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name_id
 * @property string $name_money
 * @property string $name_motion_id
 * @property string $name_category_id
 * @property string $name_user_id
 * @property string $name_tags
 * @property string $name_walletgroup_id
 * @property string $name_categroup_id
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
 * @property string $name_approve
 * @property string $name_timestamp
 * @property boolean $id
 * @property boolean $money
 * @property boolean $motion_id
 * @property boolean $category_id
 * @property boolean $user_id
 * @property boolean $tags
 * @property boolean $walletgroup_id
 * @property boolean $categroup_id
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
 * @property boolean $session_using
 * @property string $color_row1
 * @property string $color_row2
 * @property string $color_select
 * @property string $color_approve
 * @property string $color_incomes
 * @property string $color_expenses
 */
class Settings extends Model
{

public $name_id;
public $name_money;
public $name_motion_id;
public $name_category_id;
public $name_user_id; //User doesn't need information about others in his table but Admin uses this field
public $name_categroup_id;
public $name_tags;
public $name_walletgroup_id;
public $name_wallet_id;
public $name_currency_id;
public $name_date;
public $name_comment;
public $name_my_old_wallet_balance;
public $name_my_old_wgsumma_balance;
public $name_my_old_summa_balance;
public $name_my_new_wallet_balance;
public $name_my_new_wgsumma_balance;
public $name_my_new_summa_balance;
public $name_our_old_wgsumma_balance;
public $name_our_old_summa_balance;
public $name_our_new_wgsumma_balance;
public $name_our_new_summa_balance;
public $name_related_record;
public $name_approve;
public $name_timestamp;


public $id;
public $money;
public $motion_id;
public $category_id;
public $user_id; //User doesn't need information about others in his table but Admin uses this field
public $categroup_id;
public $tags;
public $walletgroup_id;
public $wallet_id;
public $currency_id;
public $date;
public $comment;
public $my_old_wallet_balance;
public $my_old_wgsumma_balance;
public $my_old_summa_balance;
public $my_new_wallet_balance;
public $my_new_wgsumma_balance;
public $my_new_summa_balance;
public $our_old_wgsumma_balance;
public $our_old_summa_balance;
public $our_new_wgsumma_balance;
public $our_new_summa_balance;
public $related_record;
public $approve;
public $timestamp;

public $registration;
public $timezone;
public $sessionUsing;

public $color_row1;
public $color_row2;
public $color_select;
public $color_approve;
public $color_incomes;
public $color_expenses;


	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
/*				[
					[
						'name_id',
						'name_money',
						'name_motion_id',
						'name_category_id',
						'name_user_id', //User doesn't need information about others in his table but Admin uses this field
						'name_wallet_id',
						'name_currency_id',
						'name_date',
						'name_comment',
						'name_my_old_wallet_balance',
						'name_my_old_wgsumma_balance',
						'name_my_old_summa_balance',
						'name_my_new_wallet_balance',
						'name_my_new_wgsumma_balance',
						'name_my_new_summa_balance',
						'name_our_old_wgsumma_balance',
						'name_our_old_summa_balance',
						'name_our_new_wgsumma_balance',
						'name_our_new_summa_balance',
						'name_related_record',
						'name_approve',
						'name_timestamp',

						'id',
						'money',
						'motion_id',
						'category_id',
						'user_id', //User doesn't need information about others in his table but Admin uses this field
						'wallet_id',
						'currency_id',
						'date',
						'comment',
						'my_old_wallet_balance',
						'my_old_wgsumma_balance',
						'my_old_summa_balance',
						'my_new_wallet_balance',
						'my_new_wgsumma_balance',
						'my_new_summa_balance',
						'our_old_wgsumma_balance',
						'our_old_summa_balance',
						'our_new_wgsumma_balance',
						'our_new_summa_balance',
						'related_record',
						'approve',
						'timestamp',
					],
				'required'],*/

				[
					[
						'id',
						'money',
						'motion_id',
						'category_id',
						'user_id',  //User doesn't need information about others in his table but Admin uses this field
						'categroup_id',
						'tags',
						'walletgroup_id',
						'wallet_id',
						'currency_id',
						'date',
						'comment',
						'my_old_wallet_balance',
						'my_old_wgsumma_balance',
						'my_old_summa_balance',
						'my_new_wallet_balance',
						'my_new_wgsumma_balance',
						'my_new_summa_balance',
						'our_old_wgsumma_balance',
						'our_old_summa_balance',
						'our_new_wgsumma_balance',
						'our_new_summa_balance',
						'related_record',
						'approve',
						'timestamp',

						'registration',
						'sessionUsing',
				], 'boolean'],

				[
					[
						'name_id',
						'name_money',
						'name_motion_id',
						'name_category_id',
						'name_user_id',  //User doesn't need information about others in his table but Admin uses this field
						'name_categroup_id',
						'name_tags',
						'name_walletgroup_id',
						'name_wallet_id',
						'name_currency_id',
						'name_date',
						'name_comment',
						'name_my_old_wallet_balance',
						'name_my_old_wgsumma_balance',
						'name_my_old_summa_balance',
						'name_my_new_wallet_balance',
						'name_my_new_wgsumma_balance',
						'name_my_new_summa_balance',
						'name_our_old_wgsumma_balance',
						'name_our_old_summa_balance',
						'name_our_new_wgsumma_balance',
						'name_our_new_summa_balance',
						'name_related_record',
						'name_approve',
						'name_timestamp',

						'color_row1',
						'color_row2',
						'color_select',
						'color_approve',
						'color_incomes',
						'color_expenses',

						'timezone',

				], 'string'],
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
			'name_id' => 'ID записи',
			'name_money' => 'Сумма',
			'name_motion_id' => 'Тип движения',
			'name_category_id' => 'Категория',
			'name_user_id' => 'Пользователь',
			'name_categroup_id' => 'Группа категории',
			'name_tags' => 'Тэги',
			'name_walletgroup_id' => 'Группа кошелька',
			'name_wallet_id' => 'Название кошелька',
			'name_currency_id' => 'Валюта',
			'name_date' => 'Дата движения',
			'name_comment' => 'Комментарий',
			'name_my_old_wallet_balance' => 'Старый баланс на моём кошельке',
			'name_my_old_wgsumma_balance' => 'Старый баланс на моей группе кошелька',
			'name_my_old_summa_balance' => 'Мой cтарый суммарный баланс',
			'name_my_new_wallet_balance' => 'Новый баланс на моём кошельке',
			'name_my_new_wgsumma_balance' => 'Новый баланс на моей группе кошелька',
			'name_my_new_summa_balance' => 'Мой новый суммарный баланс',
			'name_our_old_wgsumma_balance' => 'Наш старый баланс на нашей группе кошелька',
			'name_our_old_summa_balance' => 'Наш старый суммарный баланс',
			'name_our_new_wgsumma_balance' => 'Наш новый баланс на нашей группе кошелька',
			'name_our_new_summa_balance' => 'Наш новый суммарный баланс',
			'name_related_record' => 'Связанная запись',
			'name_approve' => 'Подтверждение',
			'name_timestamp' => 'Дата внесения',


			'id' => 'ID записи',
			'money' => 'Сумма',
			'motion_id' => 'Тип движения',
			'category_id' => 'Категория',
			'user_id' => 'Пользователь',
			'categroup_id' => 'Группа категории',
			'tags' => 'Тэги',
			'walletgroup_id' => 'Группа кошелька',
			'wallet_id' => 'Название кошелька',
			'currency_id' => 'Валюта',
			'date' => 'Дата движения',
			'comment' => 'Комментарий',
			'my_old_wallet_balance' => 'Старый баланс на моём кошельке',
			'my_old_wgsumma_balance' => 'Старый баланс на моей группе кошелька',
			'my_old_summa_balance' => 'Мой cтарый суммарный баланс',
			'my_new_wallet_balance' => 'Новый баланс на моём кошельке',
			'my_new_wgsumma_balance' => 'Новый баланс на моей группе кошелька',
			'my_new_summa_balance' => 'Мой новый суммарный баланс',
			'our_old_wgsumma_balance' => 'Наш старый баланс на нашей группе кошелька',
			'our_old_summa_balance' => 'Наш старый суммарный баланс',
			'our_new_wgsumma_balance' => 'Наш новый баланс на нашей группе кошелька',
			'our_new_summa_balance' => 'Наш новый суммарный баланс',
			'related_record' => 'Связанная запись',
			'approve' => 'Подтверждение',
			'timestamp' => 'Дата внесения',
			'color_row1' => 'Цвет строки 1',
			'color_row2' => 'Цвет строки 2',
			'color_select' => 'Цвет выделения',
			'color_approve' => 'Цвет подтверждения',
			'color_incomes' => 'Цвет доходов',
			'color_expenses' => 'Цвет затрат',

			'registration' => 'Регистрация на сайте',
			'timezone' => 'Часовой пояс',
			'sessionUsing' => 'Использование сессий',

		];
	}

}