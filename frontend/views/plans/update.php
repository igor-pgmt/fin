<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Plans */

$this->title = 'Изменить План: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Планы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="plans-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'moneyline' => $moneyline,
        'planType' => $planType,
        'currency_id' => $currency_id,
        'walletgroup_id' => $walletgroup_id,
        'wallet_id' => $wallet_id,
        'wallet_id' => $wallet_id,
        'categroups' => $categroups,
        'category_id' => $category_id,
        'motions' => $motions,
        'date_from' => $date_from,
        'tags' => $tags,
/*      'shared_plan' => $shared_plan,
        'difference' => $difference,
        'summation' => $summation,*/
    ]) ?>

</div>
