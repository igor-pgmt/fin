<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Finances */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finances-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'money')->textInput() ?>

    <?= $form->field($model, 'motion_id')->textInput() ?>

    <?= $form->field($model, 'category_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'wallet_id')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'my_old_wallet_balance')->textInput() ?>

    <?= $form->field($model, 'my_old_summa_balance')->textInput() ?>

    <?= $form->field($model, 'my_new_wallet_balance')->textInput() ?>

    <?= $form->field($model, 'my_new_summa_balance')->textInput() ?>

    <?= $form->field($model, 'our_old_wgsumma_balance')->textInput() ?>

    <?= $form->field($model, 'our_old_summa_balance')->textInput() ?>

    <?= $form->field($model, 'our_new_wgsumma_balance')->textInput() ?>

    <?= $form->field($model, 'our_new_summa_balance')->textInput() ?>

    <?= $form->field($model, 'timestamp')->textInput() ?>

    <?= $form->field($model, 'deleted')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
