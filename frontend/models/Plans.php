<?php

namespace frontend\models;

use Yii;
use creocoder\taggable\TaggableBehavior;

/**
 * This is the model class for table "plans".
 *
 * @property integer $id
 * @property integer $moneyline
 * @property integer $plantype
 * @property integer $user_id
 * @property integer $currency_id
 * @property integer $walletgroup_id
 * @property integer $wallet_id
 * @property integer $motion_id
 * @property integer $category_id
 * @property integer $cgroup_id
 * @property integer $tag_search
 * @property string $date_from
 * @property string $date_to
 * @property boolean $common_plan
 * @property boolean $shared_plan
 * @property boolean $difference
 * @property boolean $summation
 * @property string $planname
 */
class Plans extends \yii\db\ActiveRecord
{

public $planTags=[];

	public function behaviors()
	{
		// timestamp в бд в таблицу finances
		return [
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
		return 'plans';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['planname', 'moneyline', 'plantype', 'user_id', 'currency_id', 'motion_id', 'date_from', 'date_to'], 'required'],
			[['user_id', 'currency_id', 'walletgroup_id', 'wallet_id', 'motion_id', 'category_id', 'cgroup_id'], 'integer'],
			[['tag_search', 'common_plan', 'difference', 'summation'], 'boolean'],
			[['shared_plan'], 'integer'],
			[['planname'], 'string'],
			[['tagValues', 'planTags'], 'safe'],
			[['date_from', 'date_to'], 'default', 'value' => date('YYYY-mm-dd')],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'moneyline' => 'Плановое значение',
			'plantype' => 'Тип плана',
			'user_id' => 'Пользователь',
			'currency_id' => 'Валюта',
			'walletgroup_id' => 'Тип кошелька',
			'wallet_id' => 'Кошелёк',
			'motion_id' => 'Тип движения',
			'category_id' => 'Траты\доходы',
			'cgroup_id' => 'Категория Трат\доходов',
			'tag_search' => 'Точное совпадение набора тегов',
			'date_from' => 'Дата начала плана',
			'date_to' => 'Дата окончания плана',
			'shared_plan' => 'Доступность',
			'common_plan' => 'Общий план',
			'difference' => 'Разностный план',
			'summation' => 'Нарастающий график',
			'planname' => 'Название плана',
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
		return new PlansQuery(get_called_class());
	}

	//for tagging
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('{{%plans_tag_assn}}', ['plan_id' => 'id']);
    }

	public static function getAllTags()
	{
		//if (!$users) $users = User::getAllUserids();
		//for tagging
		$recordsWithTags = Plans::find()
		//	->where(['user_id' => $users])
			->with('tags')
			->all();
		$tagValues=[];
		foreach ($recordsWithTags as $record) {
		    if ($record->tags) $tagValues = array_merge($tagValues, $record->getTagValues(true));
		}
		//$tags = array_combine($tagValues, $tagValues);//это список всех уникальных тегов, который мы передаём во вьюху для выпадающей подсказки

		return $tagValues;
	}

}
