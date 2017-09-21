<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Plans;

/**
 * PlansSearch represents the model behind the search form about `frontend\models\Plans`.
 */
class PlansSearch extends Plans
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'moneyline', 'plantype', 'user_id', 'currency_id', 'walletgroup_id', 'wallet_id', 'motion_id', 'category_id', 'cgroup_id'], 'integer'],
            [['tag_search', 'shared_plan', 'common_plan', 'difference', 'summation'], 'boolean'],
            [['planname'], 'safe'],
            [['date_from', 'date_to'], 'default', 'value' => date('YYYY-mm-dd')],
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
        $query = Plans::find();

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
            'moneyline' => $this->moneyline,
            'plantype' => $this->plantype,
            'user_id' => $this->user_id,
            'currency_id' => $this->currency_id,
            'walletgroup_id' => $this->walletgroup_id,
            'wallet_id' => $this->wallet_id,
            'motion_id' => $this->motion_id,
            'category_id' => $this->category_id,
            'cgroup_id' => $this->cgroup_id,
            'tag_search' => $this->tag_search,
            'shared_plan' => $this->shared_plan,
            'common_plan' => $this->common_plan,
            'difference' => $this->difference,
            'difference' => $this->summation,
        ]);

        //$query->andFilterWhere(['like', 'time', $this->time]);
        //$query->andFilterWhere(['like', 'date_from', $this->date_from]);
        //$query->andFilterWhere(['like', 'date_to', $this->date_to]);
        $query->andFilterWhere(['like', 'planname', $this->planname]);

        return $dataProvider;
    }
}
