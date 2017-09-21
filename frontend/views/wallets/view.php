<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MyWallet */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои кошельки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="my-wallet-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать мой кошелёк', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Реально удалить кошелёк?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        //    'id',
            'name',
        //    'user_id',
            'walletgroup_id',
        //    'deleted',
        ],
    ]) ?>

</div>
