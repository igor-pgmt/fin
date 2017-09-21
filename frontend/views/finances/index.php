<?php
/* @var $this yii\web\View */
$this->title = 'Деньгоучёт';
use yii\helpers\Html;
// use yii\helpers\Url;
?>
<div class="site-index">
    <div class="jumbotron" style="text-align: center; margin: -50px Auto;">
        <h1>Деньгоучёт</h1>
		<?= Html::img('/img/logocat.png', ['alt' => 'Logocat']) ?>
        <p class="lead"></p>

		<?= Html::a('Доход', ['/finances/addrecord', 'motion_type' => 1], ['class'=>'btn btn-primary']) ?>
		<!-- <br/><br/> -->

		<?= Html::a('Траты', ['/finances/addrecord', 'motion_type' => 0], ['class'=>'btn btn-primary']) ?>
		<br/><br/>

		<?= Html::a('Перевод', ['/finances/utransfer', 'motion_type' => 2], ['class'=>'btn btn-default']) ?>

		<?= Html::a('Распределить', ['/finances/wtransfer'], ['class'=>'btn btn-default']) ?>

		<?= Html::a('Конвертировать', ['/finances/wconvert'], ['class'=>'btn btn-default']) ?>
		<br/><br/>

        <a class="btn btn-lg btn-success" href="/finances/myfinances">Мои данные</a>
        <a class="btn btn-lg btn-success" href="/finances/finshared">Общие данные</a>
        <a class="btn btn-lg btn-success" href="/wallets/info">Мои деньги</a>
    </div>
</div>
