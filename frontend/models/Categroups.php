<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "categroups".
 *
 * @property integer $id
 * @property string $cgroupname
 * @property boolean $system_group
 * @property boolean $deleted
 */
class Categroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categroups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cgroupname'], 'required'],
            [['cgroupname', ], 'safe'],
            [['system_group', 'deleted'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cgroupname' => 'Категория',
            'deleted' => 'Удалена',
        ];
    }

    public static function findCategroups()
    {
        $query_categroups=Categroups::find()
        ->Where(['deleted' => false])
        ->andWhere(['system_group' => false])
        ->all();

        //use yii\helpers\ArrayHelper;
        $categroups=ArrayHelper::map($query_categroups, 'id', 'cgroupname');
        return $categroups;
    }
}
