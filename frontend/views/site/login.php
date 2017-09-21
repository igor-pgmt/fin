<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <div class="row" style="text-align:center;">
<h1><?= Html::encode($this->title) ?></h1>
        <!-- <div class="col-lg-12"> -->

            <?php $form = ActiveForm::begin(['id' => 'slick-login']); ?>

                <?= $form->field($model, 'username')->textinput(['placeholder' => "логин"]) ?>

                <?= $form->field($model, 'password')->passwordInput(['placeholder' => "пароль"]) ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    Забыли пароль? <?= Html::a('Сбросить', ['site/request-password-reset']) ?>.
                </div>

                <input type="submit" class = 'btn btn-primary' value="ВОЙТИ">

                    <!-- <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button', 'id' => 'slick-login']) ?> -->


            <?php ActiveForm::end(); ?>

        <!-- </div> -->
    </div>
</div>
