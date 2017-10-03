<?php

namespace frontend\models;

use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property string $name
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
            [['name', 'cgroup_id'], 'required'],
            [['system_field', 'deleted'], 'boolean'],
            [['name'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Источник затрат или доходов',
            'cgroup_id' => 'Категория затрат или доходов',
            'system_field' => 'Системное поле',
            'deleted' => 'Удалена',
        ];
    }

    public static function findCategories()
    {
        //use app\models\Categories;
        $query_categories=Categories::find()
        ->Where(['deleted' => false])
        ->andWhere(['system_field' => false])
        ->all();

        //use yii\helpers\ArrayHelper;
        $categories=ArrayHelper::map($query_categories, 'id', 'name');
        return $categories;
    }

}
