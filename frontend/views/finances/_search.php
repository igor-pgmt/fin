<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\FinancesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finances-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'money') ?>

    <?= $form->field($model, 'motion_id') ?>

    <?= $form->field($model, 'category_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'wallet_id') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'my_old_wallet_balance') ?>

    <?php // echo $form->field($model, 'my_old_summa_balance') ?>

    <?php // echo $form->field($model, 'my_new_wallet_balance') ?>

    <?php // echo $form->field($model, 'my_new_summa_balance') ?>

    <?php // echo $form->field($model, 'our_old_wgsumma_balance') ?>

    <?php // echo $form->field($model, 'our_old_summa_balance') ?>

    <?php // echo $form->field($model, 'our_new_wgsumma_balance') ?>

    <?php // echo $form->field($model, 'our_new_summa_balance') ?>

    <?php // echo $form->field($model, 'timestamp') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
