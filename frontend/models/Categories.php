<?php

namespace frontend\models;

use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property string $category
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'cgroup_id'], 'required'],
            [['deleted'], 'boolean'],
            [['category'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Источник затрат или доходов',
            'cgroup_id' => 'Категория затрат или доходов',
            'deleted' => 'Удалена',
        ];
    }

    public static function findCategories()
    {
        //use app\models\Categories;
        $query_categories=Categories::find()
        ->Where(['deleted'=>false])
        ->andWhere(['not in', 'id', [1, 2, 3, 4]]) //Костыль
        ->all();

        //use yii\helpers\ArrayHelper;
        $categories=ArrayHelper::map($query_categories, 'id', 'category');
        return $categories;
    }

}
