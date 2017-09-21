<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Wallets */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Типы кошельков', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wallets-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
