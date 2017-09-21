<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Categories;

/**
 * CategoriesSearch represents the model behind the search form about `frontend\models\Categories`.
 */
class CategoriesSearch extends Categories
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id'], 'integer'],
			[['category'], 'safe'],
			[['cgroup_id'], 'integer'],
			[['deleted'], 'boolean'],
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
		$query = Categories::find()
		->Where(['deleted' => false]) //так мы скрываем категории, помеченные как удалённые
		->andWhere(['not in', 'id', [1, 2, 3, 4]]) //костыль для сокрытия категорий, которые автоматически присваиваются при переводах денег
		;

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$query->andFilterWhere([
			'id' => $this->id,
		]);

		$query->andFilterWhere(['like', 'category', $this->category]);

		return $dataProvider;
	}
}
