<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MyWallet */

$this->title = 'Обновить мой кошелёк: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои кошелькbи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="my-wallet-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'wallets' => $wallets,
    ]) ?>

</div>
