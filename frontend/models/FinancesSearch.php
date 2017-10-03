<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\data\ActiveDataProvider;
use frontend\models\Finances;

/**
 * FinancesSearch represents the model behind the search form about `frontend\models\Finances`.
 */
class FinancesSearch extends Finances
{

	public $currency_name;
	public $realName;
	public $motionType;
	public $walletgroup_id;
	public $walletName;
	public $cgroup_id;
	public $categoryName;

	public $tag;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
				[
					[
						'id',
						'money',
						'motion_id',
						'category_id',
						'user_id',
						'wallet_id',
						'cgroup_id',
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

						'related_record',
						'approve',
						'timestamp',

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

		if (Yii::$app->controller->action->id == 'myfinances') {
			$user_ids = Yii::$app->user->identity->id;
		} else {
			$user_ids = User::getAllUserids();
		}

		$query = Finances::find()
		->where(['finances.deleted' => false])
		->andWhere(['finances.user_id' => $user_ids]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pagesize' => 25,
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


	 if (!($this->load($params) && $this->validate())) {

			 // uncomment the following line if you do not want to return any records when validation fails
			 // $query->where('0=1');
		return $dataProvider;
	 }

		$query->andFilterWhere([

			//'id' => $this->id,
			//'money' => $this->money,
			'motion_id' => $this->motion_id,
			'category_id' => $this->category_id,
			//'wallet_id' => $this->wallet_id,
			'walletgroup_id' => $this->walletgroup_id,
			'cgroup_id' => $this->cgroup_id,
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

		$query->joinWith(['motions' => function ($q) {
			$q->where('motions.name LIKE "%' . $this->motionType . '%"');
		}]);

		$query->joinWith(['walletgroups' => function ($q) {
			$q->where('walletgroups.name LIKE "%' . $this->walletgroupName . '%"');
		}]);

		$query->joinWith(['categroups' => function ($q) {
			$q->where('categroups.cgroupname LIKE "%' . $this->categroupName . '%"');
		 }]);

		//we do not need here wallets and categories JOINs because we are using JOINing with viaTable (see Finances model)

		if ($this->tag != false ) {

			$query->joinWith(['tags t']);
			$query->andWhere(['t.name' => $this->tag]);

		}

		return $dataProvider;
	}


}
