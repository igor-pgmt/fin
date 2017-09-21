<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Todo */

$this->title = 'Создать заметку';
$this->params['breadcrumbs'][] = ['label' => 'Заметки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="todo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
