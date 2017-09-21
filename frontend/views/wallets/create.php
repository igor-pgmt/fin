<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MyWallet */

$this->title = 'Создать мой кошелёк';
$this->params['breadcrumbs'][] = ['label' => 'Мои кошельки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="my-wallet-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'wallets' => $wallets,
    ]) ?>

</div>
