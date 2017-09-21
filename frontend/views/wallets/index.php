<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use app\models\Finances;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\MyWalletSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои кошельки';
$this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['site/settings']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="my-wallet-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать мой кошелёк', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => 0],
        //'showOnEmpty' => false, // Не показывать виджет при пустой таблице.
        //'filterModel' => $searchModel, //скрываем фильтр
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

        //    'id',
            'name',
        //    'user_id',
        //    'walletgroup_id',
            [
                'attribute'=>'walletgroup_id',
                'label'=>'Тип кошелька',
                // получаем Названия типов кошельков по их id
                'value' => function ($model) {
                    return $model->getWtype(); // функцию getWtype см. в модели MyWallet.php
                }
            ],
/*            [
              //  'attribute'=>'motion_type',
                'label'=>'Сумма',
                //'format'=>'text',
                // получаем Названия кошельков по их id

                'value' => function ($model) {
                    return $model->getWamount(); // функцию getWamount см. в модели MyWallet.php
                }
            ],*/
        //    'deleted',

            [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url,$model) {
                    return Html::a(
                    '<span class="glyphicon glyphicon-pencil"></span>',
                    $url);
                },
                        ],
            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
