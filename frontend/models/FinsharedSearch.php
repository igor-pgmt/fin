<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Finances;

/**
 * FinancesSearch represents the model behind the search form about `frontend\models\Finances`.
 */
class FinsharedSearch extends Finances
{

	public $currency_name;
	public $realName;
	public $motionType;
	public $categoryName;
	public $walletName;
	public $walletgroup_id;
	public $cgroup_id;
	public $tag;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return 	[
					[
						[
							'id',
							'money',
							'motion_id',
							'category_id',
							'user_id',
							'cgroup_id',
							'wallet_id',
							'walletgroup_id',
							'currency_id',

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

							'approve',
							'related_record',
							'timestamp'
						],
					'integer'],

			[['deleted'], 'boolean'],
			[['date', 'comment', 'currency_name', 'realName', 'motionType', 'categoryName', 'walletName', 'tag'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = Finances::find()
		->where(['finances.deleted' => false])
		;

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pagesize' => 15,
			],
		]);

	 $dataProvider->setSort([
	 	//упорядочиваем по дате внесения
	 	'defaultOrder' => ['date' => SORT_DESC, 'id' => SORT_DESC,],

	 	//Настраиваем возможность сортировки
		'attributes' => [

			'id',
			'money',
			'motion_id',
			'cgroup_id',
			'category_id',
			'user_id',
			'tag',
			'wallet_id',
			'currency_id',
			'currency_name' => [
				'asc' => ['currencies.name' => SORT_ASC],
				'desc' => ['currencies.name' => SORT_DESC],
			],
			'realName' => [
				'asc' => ['user.realname' => SORT_ASC],
				'desc' => ['user.realname' => SORT_DESC],
			],
			'motionType' => [
				'asc' => ['motions.name' => SORT_ASC],
				'desc' => ['motions.name' => SORT_DESC],
			],
			'categoryName' => [
				'asc' => ['categories.name' => SORT_ASC],
				'desc' => ['categories.name' => SORT_DESC],
			],
			'walletName' => [
				'asc' => ['wallets.name' => SORT_ASC],
				'desc' => ['wallets.name' => SORT_DESC],
			],
			'walletgroup_id' => [
				'asc' => ['walletgroups.name' => SORT_ASC],
				'desc' => ['walletgroups.name' => SORT_DESC],
			],

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

		]
	]);

/*
 		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}*/

	if (!($this->load($params) && $this->validate())) {
		/**
		 * Жадная загрузка данных моделей
		 * для работы сортировки.
		 */

		$query->joinWith(['wallets']);
//		$query->joinWith(['categories']);
		$query->joinWith(['currencies']);
		$query->joinWith(['motions']);
		$query->joinWith(['user']);
		return $dataProvider;
	}

		$query->andFilterWhere([
			//'finances.id' => $this->id,
			//'money' => $this->money,
			'motion_id' => $this->motion_id,
			'category_id' => $this->category_id,
			'finances.user_id' => $this->user_id,
			'walletgroup_id' => $this->walletgroup_id,
			'wallet_id' => $this->wallet_id,
			'currency_id' => $this->currency_id,
			//'date' => $this->date,
			'my_old_wallet_balance' => $this->my_old_wallet_balance,
			'my_old_wgsumma_balance' => $this->my_old_wgsumma_balance,
			'my_old_summa_balance' => $this->my_old_summa_balance,
			'my_new_wallet_balance' => $this->my_new_wallet_balance,
			'my_new_wgsumma_balance' => $this->my_new_wgsumma_balance,
			'my_new_summa_balance' => $this->my_new_summa_balance,
			'our_old_wgsumma_balance' => $this->our_old_wgsumma_balance,
			'our_old_summa_balance' => $this->our_old_summa_balance,
			'our_new_wgsumma_balance' => $this->our_new_wgsumma_balance,
			'our_new_summa_balance' => $this->our_new_summa_balance,
			'related_record' => $this->related_record,
			'approve' => $this->approve,
			//'timestamp' => $this->timestamp,
			'deleted' => $this->deleted,
		]);

		// $query->andFilterWhere(['like', 'timestamp', $this->timestamp]);

		$query->andFilterWhere(['like', 'finances.id', $this->id]);
		$query->andFilterWhere(['like', 'money', $this->money]);
		$query->andFilterWhere(['like', 'comment', $this->comment]);
		$query->andFilterWhere(['like', 'date', $this->date]);

		$query->joinWith(['currencies' => function ($q) {
			$q->where('currencies.name LIKE "%' . $this->currency_name . '%"');
		}]);
		$query->joinWith(['user' => function ($q) {
			$q->where('user.realname LIKE "%' . $this->realName . '%"');
		}]);
		$query->joinWith(['motions' => function ($q) {
			$q->where('motions.name LIKE "%' . $this->motionType . '%"');
		}]);

		$query->joinWith(['walletgroups' => function ($q) {
			$q->where('walletgroups.name LIKE "%' . $this->walletgroupName . '%"');
		}]);
		$query->andWhere('wallets.name LIKE "%' . $this->walletName . '%"');

		$query->joinWith(['categroups' => function ($q) {
			$q->where('categroups.cgroupname LIKE "%' . $this->categroupName . '%"');
		 }]);

		$query->andwhere('categories.name LIKE "%' . $this->categoryName . '%"');

		// $query->joinWith(['categories' => function ($q) {
		// 	$q->where('categories.name LIKE "%' . $this->categoryName . '%"');
		// }]);

		if ($this->tag != false ) {

			$query->joinWith(['tags t']);
			$query->andWhere(['t.name' => $this->tag]);

		}

		return $dataProvider;
	}


}
