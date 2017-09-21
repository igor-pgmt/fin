<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Finances */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Finances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finances-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'money',
            'motion_id',
            'category_id',
            'user_id',
            'wallet_id',
            'date',
            'comment',
            'my_old_wallet_balance',
            'my_old_wgsumma_balance',
            'my_old_summa_balance',
            'my_new_wallet_balance',
            'my_new_summa_balance',
            'my_new_wgsumma_balance',
            'our_old_wgsumma_balance',
            'our_old_summa_balance',
            'our_new_wgsumma_balance',
            'our_new_summa_balance',
            'timestamp:datetime',
            'deleted',
        ],
    ]) ?>

</div>
