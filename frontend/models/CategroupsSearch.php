<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Categroups;

/**
 * CategroupsSearch represents the model behind the search form about `frontend\models\Categroups`.
 */
class CategroupsSearch extends Categroups
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'deleted'], 'integer'],
			[['cgroupname'], 'safe'],
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
		$query = Categroups::find()
		->Where(['deleted' => false]) //так мы скрываем категории, помеченные как удалённые
		;
		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			'id' => $this->id,
			'cgroupname' => $this->cgroupname,
			'deleted' => $this->deleted,
		]);

		return $dataProvider;
	}
}
