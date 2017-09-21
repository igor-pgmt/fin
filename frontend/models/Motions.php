<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "motions".
 *
 * @property integer $id
 * @property integer $type
 * @property string $name
 */
class Motions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'motions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'required'],
            [['type'], 'integer'],
            [['name'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип движения',
            'name' => 'Название движения',
        ];
    }

    public static function findMotions()
    {
        //use app\models\Wallets;
        $query_motions=Motions::find()
        ->all();
        //use yii\helpers\ArrayHelper;
        $motions=ArrayHelper::map($query_motions,'type','name');
        return $motions;
    }
}
