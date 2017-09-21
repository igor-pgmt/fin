<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Finances */

$this->title = 'Create Finances';
$this->params['breadcrumbs'][] = ['label' => 'Finances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finances-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
