<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Dropdown;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
</head>
<body>
	<?php $this->beginBody() ?>
	<div class="wrap">

		<?php
			NavBar::begin([
				'brandLabel' => 'Финансовая система',
				'brandUrl' => Yii::$app->homeUrl,
				//'brandUrl' => ['/site/index'],
				'options' => [
					'class' => 'navbar-inverse navbar-fixed-top',
				],
			]);

			if (Yii::$app->user->isGuest) {
				$menuItems=[
					['label' => 'Вход', 'url' => ['/site/login']],
				];

				$registration = isset($this->params['registration']) ? $this->params['registration'] : false ;
				if ($registration) {
					$menuItems[]=
					['label' => 'Регистрация', 'url' => ['/site/signup']];
				}
							} else {
				$menuItems = [
					['label' => 'Главная', 'url' => ['/site/index']],
				//['label' => 'Мои Финансы', 'url' => ['/finances/myfinances']],

				/* Вариант дропдауна */
				/*['label' => 'Финансы', 'items' => [
						[
						'label' => 'Мои данные',
						'url' => ['/finances/myfinances'],
						],
						[
						'label' => 'Общие данные',
						'url' => ['/finances/finshared'],
						],
					],],*/
					[
						'label' => 'Операции',
						'items' => [
							['label' => 'Траты', 'url' => '/finances/addrecord?motion_type=0'],
							'<li class="divider"></li>',
							['label' => 'Доход', 'url' => '/finances/addrecord?motion_type=1'],
							'<li class="divider"></li>',
							['label' => 'Перевод', 'url' => '/finances/utransfer'],
							'<li class="divider"></li>',
							['label' => 'Распределить', 'url' => '/finances/wtransfer'],
							'<li class="divider"></li>',
							['label' => 'Конвертировать', 'url' => '/finances/wconvert'],
						],
					],
					[
						'label' => 'Финансы',
						'items' => [
							['label' => 'Мои данные', 'url' => '/finances/myfinances'],
							'<li class="divider"></li>',
							/*'<li class="dropdown-header">Все столбцы</li>',*/
							['label' => 'Общие данные', 'url' => '/finances/finshared'],
							'<li class="divider"></li>',
							['label' => 'Мои деньги', 'url' => '/wallets/info'],
						],
					],

					//['label' => 'Общие данные', 'url' => ['/finances/finshared']],


					['label' => Html::img(Url::base().'/img/logout.png').'(' . Yii::$app->user->identity->realname . ')',
						'url' => ['/site/logout'],
						'linkOptions' => ['data-method' => 'post']
					]
				];

/**/
			//    ['label' => 'About', 'url' => ['/site/about']],
			//    ['label' => 'Contact', 'url' => ['/site/contact']],
//echo $baseUrl; echo $img;
				//if (Yii::$app->user->identity->username == "igor") {                $menuItems[] = ['label' => 'Книгоучёт', 'url' => ['/books/index']];  }
						$menuItems[] = [
							'label' => Html::img(Url::base().'/img/statistic.png'),
							'url' => ['/statistic'],
							'optinons'=>['class'=>'my-menu-li'],
							'linkOptions'=>['class'=>'my-menu-a'],
						];

						$menuItems[] = [
							'label' => Html::img(Url::base().'/img/settings2.png'),
							'url' => ['/settings'],
							'optinons'=>['class'=>'my-menu-li'],
							'linkOptions'=>['class'=>'my-menu-a'],
						];
			}


			echo Nav::widget([
				'options' => ['class' => 'navbar-nav navbar-right'],
				'items' => $menuItems,
				'encodeLabels' => false,

			]);
			NavBar::end();
		?>


		<div class="container" <?php if ( in_array($this->context->action->id, ['addrecord','wtransfer' ,'utransfer', 'create', 'update', 'wconvert', 'view', 'info'])) echo 'style="max-width:600px"'; ?>>
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
		<?= Alert::widget() ?>
		<?= $content ?>
		</div>
	</div>

	<footer class="footer">
		<div class="container">
		<p class="pull-left">&copy; Деньгоучёт <?= date('Y') ?></p>
		</div>
	</footer>

	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
