<?php

namespace frontend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use common\models\User;
use yii\helpers\Html;
use frontend\models\Currencies;
use frontend\models\Wallets;
use creocoder\taggable\TaggableBehavior;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "finances".
 *
 * @property integer $id
 * @property integer $money
 * @property integer $motion_id
 * @property integer $category_id
 * @property integer $user_id
 * @property integer $wallet_id
 * @property string $date
 * @property string $comment
 * @property integer $my_old_wallet_balance
 * @property integer $my_old_summa_balance
 * @property integer $my_old_wgsumma_balance
 * @property integer $my_new_wallet_balance
 * @property integer $my_new_wgsumma_balance
 * @property integer $my_new_summa_balance
 * @property integer $our_old_wgsumma_balance
 * @property integer $our_old_summa_balance
 * @property integer $our_new_wallet_balance
 * @property integer $our_new_wgsumma_balance
 * @property integer $our_new_summa_balance
 * @property integer $related_record
 * @property integer $approve
 * @property integer $timestamp
 * @property integer $update_time
 * @property integer $edit_time
 * @property integer $deleted
 *
 * @property Motions $motionType
 * @property Wallet $wallet
 * @property User $user
 * @property Categories $name
 */
class Finances extends \yii\db\ActiveRecord
{

//for tagging
public $finTags=[];


	public function behaviors()
	{
		// timestamp в бд в таблицу finances
		return [
			'timestamp' => [
				'class' => TimestampBehavior::className(),
				'attributes' => [
					\yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'timestamp',
				],
				'value' => function() { return date('U');} // unix timestamp
			],
			'update_time' => [
				'class' => TimestampBehavior::className(),
				'attributes' => [
					\yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
				],
				'value' => function() { if (Yii::$app->controller->action->id=='update') return date('U');} // unix timestamp
			],
			//for tagging
			'taggable' => [
				'class' => TaggableBehavior::className(),
				// 'tagValuesAsArray' => false,
				// 'tagRelation' => 'tags',
				// 'tagValueAttribute' => 'name',
				// 'tagFrequencyAttribute' => 'frequency',
				],
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'finances';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[
				[
					'money',
					'category_id',
					'user_id',
					'wallet_id',
					'currency_id',
					'date'
				],
			'required'],

			[
				[
					'id',
					'motion_id',
					'category_id',
					'user_id',
					'wallet_id',
					'currency_id',
					'related_record',
					'approve',
				],
			'integer'],
			[
				[
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
				],
			'integer', 'integerOnly' => false,],
			[['money'], 'integer', 'integerOnly' => false, ],
			['money', 'match', 'pattern' => '/^[-+]?[0-9]{1,10}([.][0-9]{0,10})?+(?:[eE][-+]?[0-9]+)?$/i'],
			[['timestamp', 'update_time', 'edit_time', 'tagValues', 'finTags', 'tag'], 'safe'],
			[['deleted'], 'boolean'],
			[['date'], 'default', 'value' => date('YYYY-mm-dd')],
			[['comment'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'money' => 'Сумма',
			'motion_id' => 'Тип',
			'category_id' => 'Категория',
			'user_id' => 'User ID',
			'wallet_id' => 'Кошелёк',
			'currency_id' => 'Валюта',
			'date' => 'Дата',
			'comment' => 'Комментарий',

			'my_old_wallet_balance' => 'Было в к моём кошельке',
			'my_old_wgsumma_balance' => 'Было в моих кошельках такого типа',
			'my_old_summa_balance' => 'Было у меня такой валюты вообще',
			'my_new_wallet_balance' => 'Стало в моём кошельке',
			'my_new_wgsumma_balance' => 'Стало в моих кошельках такого типа',
			'my_new_summa_balance' => 'Стало у меня такой валюты вообще',
			'our_old_wgsumma_balance' => 'Было у нас в кошельках такого типа',
			'our_old_summa_balance' => 'Было такой валюты у нас вообще',
			'our_new_wgsumma_balance' => 'Стало у нас в кошельках такого типа',
			'our_new_summa_balance' => 'Стало такой валюты у нас вообще',

			'related_record' => 'Связанная запись',
			'approve' => 'Подтверждение',
			'timestamp' => 'Внесено',
			'update_time' => 'Обновлено системой',
			'edit_time' => 'Отредактировано',
			'deleted' => 'Удалён',
		];
	}

	//for tagging
	public function transactions()
	{
		return [
			self::SCENARIO_DEFAULT => self::OP_ALL,
		];
	}

	//for tagging
	public static function find()
	{
		return new FinancesQuery(get_called_class());
	}

	//for tagging
	public function getTags()
	{
		return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
			->viaTable('{{%finances_tag_assn}}', ['fin_id' => 'id']);
	}

	//for tagging
	public function getTagName()
	{

		$tag = $this->tags;

	if (gettype($tag)=='string') {
		return false;
	}
	if (isset($tag[0]) && is_object($tag[0])) {
		$tags = implode(', ', ArrayHelper::getColumn($tag, 'name'));
		return $tags;}

	return false;

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	/*    public function getMotionType()
	{
		return $this->hasOne(Motions::className(), ['type' => 'motion_type']);
	}*/
	public function getMotions()
	{
		return $this->hasOne(Motions::className(), ['type' => 'motion_id']);
	}

	public function getMotionType() // получаем типы движения по их id
	{
		$motion_type = $this->motions;
		return $motion_type ? $motion_type->name : '';
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getWallets()
	{
		return $this->hasOne(Wallets::className(), ['id' => 'wallet_id']);
	}

	public function getWalletName() // получаем кошельки по их id
	{
		$wallet = $this->wallets;
		return $wallet ? $wallet->name : '';
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getWalletgroups()
	{
		return $this->hasOne(Walletgroups::className(), ['id' => 'walletgroup_id'])
			->viaTable('{{%wallets}}', ['id' => 'wallet_id']);
	}

	public function getWalletgroupName() // получаем кошельки по их id
	{
		$walletgroup = $this->walletgroups;
		return $walletgroup ? $walletgroup->name : '';
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategroups()
	{
		return $this->hasOne(Categroups::className(), ['id' => 'cgroup_id'])
			->viaTable('{{%categories}}', ['id' => 'category_id']);
	}

	public function getCategroupName() // получаем кошельки по их id
	{
		$categroup = $this->categroups;
		return $categroup ? $categroup->cgroupname : '';
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
		public function getUserName() // получаем логин юзера по его id
	{
		$user = $this->user;
		return $user ? $user->username : '';
	}
		public function getRealName() // получаем Имя юзера по его id
	{
		$user = $this->user;
		return $user ? $user->realname : '';
	}
		public function getUserByID()
	{
		return $this->hasOne(User::className(), ['id' => 'approve']);
	}
		public function getRealNameByID() // получаем Имя юзера по его id
	{
		$user = $this->userByID;
		return $user ? $user->realname : '';
	}

		public function getDateFrom() // получаем Имя юзера по его id
	{
		return $this->date;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategories()
	{
		return $this->hasOne(Categories::className(), ['id' => 'category_id']);
	}

	public function getCategoryName() // получаем категории по их id
	{
		$category = $this->categories;
		return $category ? $category->name : '';
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */

	public function getCurrencies()
	{
		return $this->hasOne(Currencies::className(), ['id' => 'currency_id']);
	}

	public function getCurrency_name()
	{
		$currency = $this->currencies;
		return $currency ? $currency->name : '';
	}

		public static function getMyBalance()
	{
		$query_currencies=Currencies::find()
		->where(['deleted' => false])
		->asArray()
		->all();

		foreach ($query_currencies as $key => $value) {

			$query_currencies[$key]['my_sum']= Finances::findMyBalance($value['id']);
		}

		return $query_currencies;
	}

	public static function getOurBalance()
	{
		$query_currencies=Currencies::find()
		->where(['deleted' => false])
		->asArray()
		->all();

		foreach ($query_currencies as $key => $value) {

			$query_currencies[$key]['my_sum']= Finances::findOurBalance($value['id']);
		}

		return $query_currencies;
	}

		public static function findMyBalance($currency_id = 1)
	{
		$query_mybalance=Finances::find()
		->Where(['user_id' => Yii::$app->user->identity->id])
		->andWhere(['currency_id' => $currency_id])
		->andWhere(['deleted' => false])
		->orderBy(['date' => SORT_DESC, 'id' => SORT_DESC])
		->limit(1)
		->one();

		return isset($query_mybalance) ? $query_mybalance->my_new_summa_balance : 0;
	}


	public static function findOurBalance($currency_id = 1)
	{
		$query_ourbalance=Finances::find()
		->Where(['currency_id' => $currency_id])
		->andWhere(['deleted' => false])
		->orderBy(['date' => SORT_DESC, 'id' => SORT_DESC])
		//->orderBy('id DESC')
		->limit(1)
		->one();

		return isset($query_ourbalance) ? $query_ourbalance->our_new_summa_balance : 0;
	}

	//	public static function allBalances($users, $currenciesOperator='NOT IN', $currencies=false, $walletOperator='NOT IN', $wallets=false, $dateOperator='NOT IN', $dates=false)
	public static function allBalances($users)
	{
		$query_currencies=Currencies::find()
		->where(['deleted' => false])
		->asArray()
		->all();

		foreach ($query_currencies as $key => $value) {

		// balance on a current wallet;
		$bDohod = Finances::find()
		->where(['IN', 'user_id', $users])
		->andWhere(['motion_id' => [1,3,5,9]])
		->andWhere(['currency_id' => $value['id']])
		->andWhere(['deleted' => false])
	//	->andWhere([$dateOperator, 'date', $dates])
		//->andWhere([$notin, 'id', $notarr])
		->sum('money');

		$bRashod = Finances::find()
		->where(['IN', 'user_id', $users])
		->andWhere(['motion_id' => [0,2,4,8]])
	//	->andWhere([$walletOperator, 'wallet_id', $wallets])
//		->andWhere(['in', 'wallet_id', $walletIDs])
		->andWhere(['currency_id' => $value['id']])
		->andWhere(['deleted' => false])
	//	->andWhere([$dateOperator, 'date', $dates])
		//->andWhere([$notin, 'id', $notarr])
		->sum('money');

		$raz = $bDohod - $bRashod;
		$query_currencies[$key]['my_sum']= $raz;

		}

		return $query_currencies;
	}

	public static function getAllTags()
	{
		//for tagging
		$recordsWithTags = Finances::find()
			->where(['deleted' => false])
		//	->andWhere(['user_id' => $users])
			->with('tags')
			->all();
		$tagValues=[];
		foreach ($recordsWithTags as $record) {
			if ($record->tags) $tagValues = array_merge($tagValues, $record->getTagValues(true));
		}

		return $tagValues;
	}

	public function getEditIcon($url, $model)
	{
		return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['my-action', 'id'=>$model->id]);

	}

	public function getDeleteIcon($url)
	{
		return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['my-action', 'id'=>$model->id]);

	}
}
