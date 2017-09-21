<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PlansSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Планы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plans-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать план', ['create', 'planType'=>0], ['class' => 'btn btn-success']) ?>
        <!-- <?= Html::a('Создать план по Типу кошелька', ['create', 'planType'=>1], ['class' => 'btn btn-success']) ?> -->
        <!-- <?= Html::a('Создать план по Кошельку', ['create', 'planType'=>2], ['class' => 'btn btn-success']) ?> -->
        <!-- <?= Html::a('Создать план по Валюте', ['create', 'planType'=>3], ['class' => 'btn btn-success']) ?> -->
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\ActionColumn'],
            //'id',
            'planname:ntext',
            'moneyline',
            'user_id',
            'currency_id',
            'walletgroup_id',
            'wallet_id',
            'motion_id',
            'category_id',
            'cgroup_id',
            'tag_search',
            //'date_from',
            //'date_to',
            'shared_plan',
            'common_plan',
            'difference',
            'summation',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
