<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Plans */

$title = 'Создать план';

$this->title = $title;

$this->params['breadcrumbs'][] = ['label' => 'Планы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plans-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model,
		'planType' => $planType,
		'currency_id' => $currency_id,
		'walletgroup_id' => $walletgroup_id,
		'categroups' => $categroups,
		'motions' => $motions,
		'tags' => $tags,
	]) ?>

</div>
