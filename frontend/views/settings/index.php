<?php
/* @var $this yii\web\View */
$this->title = 'Настройки';
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="site-index">

	<div class="jumbotron" style="text-align: center;">
		<h1>Настройки</h1>

		<p class="lead"></p>
<?php if (Yii::$app->user->identity->id == 1) { ?>

		<br/><br/>

		<?= Html::a('Валюты', ['/currencies'], ['class'=>'btn btn-default']) ?>
 <?= Html::a('Настройки системы', ['settingsadmin'], ['class'=>'btn btn-default']) ?>
 <?php } ?>

 <?= Html::a('Настройки', ['settingsuser'], ['class'=>'btn btn-default']) ?>
<br/><br/>
		<?= Html::a('Типы кошельков', ['/walletgroups'], ['class'=>'btn btn-default']) ?>
		<?= Html::a('Мои кошельки', ['/wallets'], ['class'=>'btn btn-default']) ?>
		<br/><br/>
		<?= Html::a('Категории трат и доходов', ['/categroups'], ['class'=>'btn btn-default']) ?>
		<?= Html::a('Статьи трат и доходов', ['/categories'], ['class'=>'btn btn-default']) ?>


		<br/><br/>

		<?= Html::a('Заметки', ['/todo'], ['class'=>'btn btn-default']) ?>
		<br/><br/>

		<?= Html::a('Планы-графики', ['/plans'], ['class'=>'btn btn-default']) ?>
		<br/><br/>

		<!-- <?= Html::a('Планы-данные', ['/plans/data'], ['class'=>'btn btn-default']) ?> -->
		<br/><br/>
	</div>


</div>
