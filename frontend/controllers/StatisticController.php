<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Finances;
use frontend\models\Plans;
use frontend\models\Wallets;
use yii\helpers\ArrayHelper;
use frontend\models\Currencies;
use frontend\models\Categories;
use common\models\User;

class StatisticController extends LoginController
{

	public function beforeAction($action)
	{
		if (in_array(Yii::$app->controller->action->id, ['myfinances', 'finshared'])) Yii::$app->getUser()->setReturnUrl(Url::current());
		return parent::beforeAction($action); //проверка на залогиненность
	}

	public function actionIndex()
	{
	$formatter = \Yii::$app->formatter;
	/* ---- Расчёт общих графиков ---- */

/* ---- Графики общего количества денег по всем валютам ---- */

//Найти все имеющиеся валюты
$currencies_all=Currencies::find()
	->Where(['deleted' => false])
	->asArray()
	->all();

$i=0;// счётчик планов
//перебор всех валют
foreach ($currencies_all as $ckey => $cvalue) {

	//Поиск финальных общего значения по валюте за текущую дату
/*	$result_all=Finances::find()
	->select(['date', 'our_new_summa_balance', 'currency_id'])
	->Where(['<>', 'deleted', true])
	->andWhere(['currency_id'=> $cvalue['id']])
	->orderBy(['date' => SORT_ASC, 'timestamp' => SORT_DESC])
	->groupBy(['date'])
	->all();*/

$commandtext='SELECT * from (SELECT `our_new_summa_balance`, `date`, `timestamp`, `currency_id` FROM `finances` WHERE `deleted` = false AND `currency_id` = '.$cvalue['id'].' ORDER BY `date` ASC, `timestamp` DESC) x GROUP BY `date`;';
$connection = Yii::$app->getDb();
$command = $connection->createCommand($commandtext);
$resultc = $command->queryAll();

		$sPlanresult=Array(); //Сброс массива с результатами по текущей валюте
		$maxmin=Array(); //Сброс массива для вычисления max и min значений для границ графика
			//Перебор найденных данных, составление массива данных для графика по текущей валюте
			foreach ($resultc as $key => $value) {

				$datejs =$value['date'].' 00:00:00';
				$dd=$formatter->asTimestamp($datejs);
/*				echo '<br /><br />';
				echo $datejs.' = '.$value['date'].' = '.$dd; */
				$sPlanresult[]=['name'=>$cvalue['name_g'], 'date'=>$dd, 'amount'=>$value['our_new_summa_balance']];
				$maxmin[] = $value['our_new_summa_balance'];
			}

		$staticPlans[$i]['planname']='Общий объём '.$cvalue['name_g']; //Название графика
		$staticPlans[$i]['sPlanresult']=$sPlanresult;
		$staticPlans[$i]['max'] = (count($maxmin)>1) ? max($maxmin)+100 : 100;
		$staticPlans[$i]['min'] = (count($maxmin)>1) ? min($maxmin)-100 : -100;
		$staticPlans[$i]['moneyline']=0;
	$i++; //счётчик массива для нового графика

}
/* ---- End of Графики общего количества денег по всем валютам ---- */

/* ---- График, показывающий несвоевременность вноса данных в систему ---- */

	//Поиск финальных общего значения по валюте за текущую дату
	$result_lies=Finances::find()
	->select(['id', 'date', 'timestamp'])
	->Where(['<>', 'deleted', true])
	->orderBy(['date' => SORT_ASC, 'timestamp' => SORT_DESC])
	->groupBy(['timestamp'])
	->all();

	$lPlanresult=Array(); //Сброс массива с результатами по несвоевременным внесениям
	//$maxmin=Array(); //Сброс массива для вычисления max и min значений для границ графика
	//Перебор найденных данных, составление массива данных для графика
$datethis=0;
	foreach ($result_lies as $key => $value) {

		//$this_timestamp = $formatter->asDate($value['timestamp'], 'Y-MM-dd');
		$this_timestamp = date('Y-m-d', $value['timestamp']);

	if($datethis != $value['date']) {
		if ($value['date'] != $this_timestamp) {
			//echo $formatter->asDate('1451065314', 'Y-MM-dd').'<br/>';
			//echo $formatter->asDate('1451394390', 'Y-MM-dd').'<br/>';
			//echo date('Y-m-d', $value['timestamp']);
			//	echo '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
			//	echo 'valuetimestamp '.$value['timestamp'].'  valuedate'.$value['date'].'  this_timestamp'.$this_timestamp;exit;
				$datejs =$value['date'].' 00:00:00';
				$dd = $formatter->asTimestamp($datejs);
				$lPlanresult[] = ['name'=>'Дата', 'date'=>$dd, 'lyingdate'=>'1'];
				//$maxmin[] = $value['our_new_summa_balance'];
				$datethis = $value['date'];
			} else {
				$datejs2 = $value['date'].' 00:00:00';
				$dd2 = $formatter->asTimestamp($datejs2);
				$lPlanresult[] = ['name'=>'Дата', 'date'=>$dd2, 'lyingdate'=>'0'];
			}
		}
	}

		$lyingPlan['planname']='Несвоевременные вносы '; //Название графика
		$lyingPlan['planresult']=$lPlanresult;
		//$lyingPlans[$i]['max']=max($maxmin)+100;
		//$lyingPlans[$i]['min']=min($maxmin)-100;
		$lyingPlan['moneyline']=1;

/* ---- End Of График, показывающий несвоевременность вноса данных в систему ---- */


/* ---- Общий график трат и доходов по валютам ---- */
$date = new \DateTime('now');
$date_to=$formatter->asDate($date, 'Y-MM-dd');
$date->modify('-30 day');
$date_from=$formatter->asDate($date, 'Y-MM-dd');

$сPlanresult=Array();

foreach ($currencies_all as $ckey => $cvalue) {

$expences = 'SELECT SUM(`money`) FROM `finances` WHERE `date` < "'.$date_to.'" AND `date` > "'.$date_from.'" AND `deleted` = false AND `motion_id` = 0 AND `currency_id` = '.$cvalue['id'].';';
$incomes = 'SELECT SUM(`money`) FROM `finances` WHERE `date` < "'.$date_to.'" AND `date` > "'.$date_from.'" AND `deleted` = false AND `motion_id` = 1 AND `currency_id` = '.$cvalue['id'].';';

$connection = Yii::$app->getDb();
$command1 = $connection->createCommand($expences);
$resultc1 = $command1->queryAll();
$command2 = $connection->createCommand($incomes);
$resultc2 = $command2->queryAll();


$сPlanresult['expences'][]=floatval($resultc1[0]['SUM(`money`)']);
$сPlanresult['incomes'][]=floatval($resultc2[0]['SUM(`money`)']);
// $сPlanresult['expences'][]=intval($resultc1[0]['SUM(`money`)']);
// $сPlanresult['incomes'][]=intval($resultc2[0]['SUM(`money`)']);
$сPlanresult['currencies'][]=$cvalue['name'];


}


$cPlan[]=[
	'name' => 'Расходы',
	'color'=> 'red',
	'data' => $сPlanresult['expences'],
    'pointPadding' => 0.2,
    'pointPlacement' => 0
];

$cPlan[]=[
	'name' => 'Доходы',
	'color'=> 'green',
	'data' => $сPlanresult['incomes'],
    'pointPadding' => 0.4,
    'pointPlacement' => 0
];


$comparisonPlan['planname']='Сравнение  '; //Название графика
$comparisonPlan['planresult']=$сPlanresult;
$comparisonPlan['cPlan']=$cPlan;
$comparisonPlan['max']=intval(max(max([$сPlanresult['incomes'],$сPlanresult['expences']])))+1000;















/* ---- End Of Общий график трат и доходов по валютам ---- */


	/* ---- End of Расчёт общих графиков  ---- */

$current_userid = Yii::$app->user->identity->id;
// все планы текущего юзера и шареных планов
$plans = Plans::find()
	->where(['user_id' => $current_userid])
	->orWhere(['and',
		['<>', 'user_id', $current_userid],
		['<>', 'shared_plan', 0]
		])
	->asArray()
	->all();

//сброс используемых массивов


$i=0;
	//Перебор всех планов
	foreach ($plans as $key => $value) {
		$walletsID = [];
		$categoriesID = [];


		//Смотрим, общий это план или нет (чтобы считать по всем юзерам или только текущему),
		//А так же способ расчёта - по создателю плана, текущему юзеру или всем юзерам
		$users=Array(); //Сброс массива юзеров
	if ($value['common_plan'] == 1) {
		$usersArray = \Yii::$app->db->createCommand('SELECT `id` FROM `user` group by `id`')->queryAll();
		foreach ($usersArray as $keyUarray => $valueUarray) {
			$users[] = $valueUarray['id'];
		}
	} elseif ($value['common_plan'] == 0) {
		switch ($value['shared_plan']) {
			case 0:
			case 2:
			$users = Yii::$app->user->identity->id;
			break;
		case 1:
			$users = $value['user_id'];
			break;
		}
	}



	if (isset($value['wallet_id'])) { //Если пользователь выбрал кошелёк, то всё ок, идём дальше
	$value['wallet_id'] = [$value['wallet_id'], $value['wallet_id']]; //костыль
	$walletsID = $value['wallet_id'];
	} else { //Если пользователь не выбрал кошелёк,
		if (isset($value['walletgroup_id'])) { //Если пользователь выбрал Тип кошельков,
					//Кошельки выбранного типа
					$wallets=Wallets::find()
					->select('id')
					->where(['walletgroup_id' => $value['walletgroup_id']])
					->andWhere(['user_id' => $users])
					->asArray()->all();

					if (empty($wallets)) { //Проверка на существование кошельков в данном Типе
						$walletsID=NULL;
					} else {
						foreach ($wallets as $keyid => $valueid) {
							$walletsID[] = $valueid['id'];
						}
						$walletsID[]=$valueid['id']; //костыль
					}
		} else {
			$walletsID=NULL;
		}
	}
	// категории
	if (isset($value['category_id'])) { //Если пользователь выбрал Тип затрат, то всё ок, идём дальше
	$value['category_id'] = [$value['category_id'], $value['category_id']]; //костыль
	$categoriesID = $value['category_id'];

	} else { //Если пользователь не выбрал категорию,
		if (isset($value['cgroup_id'])) { //Если пользователь выбрал Тип кошельков,
					//Кошельки выбранного типа
					$categories=Categories::find()
					->select('id')
					->where(['cgroup_id' => $value['cgroup_id']])
					->asArray()->all();

					if (empty($categories)) { //Проверка на существование кошельков в данном Типе
						$categoriesID=NULL;
					} else {
						foreach ($categories as $keyid => $valueid) {
							$categoriesID[] = $valueid['id'];
						}
						$categoriesID[]=$valueid['id']; //костыль
					}
		} else {
			$categoriesID=NULL;
		}
	}

	$tagResult=$this->getTaggedRecords($value['id']);

			switch ($value['difference']) {
				case 0: //не разностный

					$planresult = $this->nondiffPlan($users, $value['currency_id'], $walletsID, $value['motion_id'], $categoriesID, $value['date_from'], $value['date_to'], $tagResult, $value['summation']);

					break; //не разностный

				case 1: //разностный

					$planresult = $this->diffPlan($users, $value['currency_id'], $walletsID, $value['motion_id'], $categoriesID, $value['date_from'], $value['date_to'], $tagResult, $value['summation']);

					break; //разностный план

			}

				$maxmin=array();
				foreach ($planresult as $keypr => $valuepr) {
					$maxmin[] = $valuepr['amount'];
				}
				$maxmin[] = $value['moneyline'];

				$allPlans[$i]['planresult']=$planresult;
				$allPlans[$i]['max']=max($maxmin)+100;
				$allPlans[$i]['min']=min($maxmin)-100;
				$allPlans[$i]['planname']=$value['planname'];
				$allPlans[$i]['moneyline']=$value['moneyline'];
$i++;
}


		if (!isset($allPlans)) $allPlans=0;
		if (!isset($staticPlans)) $staticPlans=0;

		return $this->render('index', [

			'allPlans' => $allPlans, //массив всех планов для highcharts
			'staticPlans' => $staticPlans, //массив всех статичных планов для highcharts
			'lyingPlan' => $lyingPlan, //План по несвоевременным вносам
			'comparisonPlan' => $comparisonPlan, //План по несвоевременным вносам
			'my_balance' => Finances::getMyBalance(),
			'our_balance' => Finances::getOurBalance(),
			'my_balance2' => Finances::allBalances(Yii::$app->user->identity->id),
			'our_balance2' => Finances::allBalances(User::getAllUserids())
			]);
	}

	public function nondiffPlan($users, $currency, $walletsID, $motion, $category, $date_from, $date_to, $tagResult, $summation=false)
	{

		if ($walletsID != NULL) {$operators = 'IN';} else {$operators = 'NOT IN'; $walletsID = [0, 0];}
		if ($category != NULL) {$operatorc = 'IN';} else {$operatorc = 'NOT IN'; $category = [0, 0];}
		if (!empty($tagResult)) {$operatort = 'IN';} else {$operatort = 'NOT IN'; $tagResult = false;}
		if ($motion != NULL) {$operatorm = 'IN';} else {$operatorm = 'NOT IN'; $motion = false;}

		$formatter = \Yii::$app->formatter;
		$planresult1 = Finances::find()
			->select (['date, SUM(money) as daily'])
			->Where(['IN', 'user_id', $users])
			->andWhere([$operatort, 'finances.id', $tagResult])
			->andWhere(['currency_id' => $currency])
			->andWhere([$operators, 'wallet_id', $walletsID])
			->andWhere([$operatorm, 'motion_id', $motion])
			// ->andWhere(['motion_id' => $motion])
			->andWhere([$operatorc, 'category_id', $category])
			->andWhere(['>=', 'date', $date_from])
			->andWhere(['<=', 'date', $date_to])
			->andWhere(['<>', 'deleted', 1])
			->groupBy(['date'])
			->asArray()->all();

/*
echo '<br /><br /><br /><br /><br />';

echo 'dates '.$date_from.' to '.$date_to.'<br />';
echo 'users: '.'<br />';
print_r($users);
echo '<br />';
echo 'currency: '.$currency.'<br />';
echo 'motion: '.$motion.'<br />';
echo 'operators '.$operators.'<br />';
print_r($walletsID);
echo '<br />';
echo 'operatorc '.$operatorc.'<br />';
print_r($category);
echo '<br />';


echo '<br /><br />tagresult:';
print_r($tagResult);
echo '<br /><br />planresult1:';
print_r($planresult1);
*/


			//костыль
	if (empty($planresult1)) {return $planresult1;}

	$currency = Currencies::findOne($currency);
	$narast=0; //Число нарастающей суммы
		foreach ($planresult1 as $key => $value) {
			$datejs =$value['date'].' 00:00:00';
			$dd=$formatter->asTimestamp($datejs);
			if ($summation==true){$narast+=$value['daily'];} else {$narast=$value['daily'];}
			$planresultArray[]=['name'=>$currency->name, 'date'=>$dd, 'amount'=>$narast];
		}

		return $planresultArray;
	}

	public function diffPlan($users, $currency, $walletsID, $motion, $category, $date_from, $date_to, $tagResult, $summation=false)
	{
		if ($walletsID != NULL) {$operators = 'IN';} else {$operators = 'NOT IN'; $walletsID = [0, 0];}
		if ($category != NULL) {$operatorc = 'IN';} else {$operatorc = 'NOT IN'; $category = [0, 0];}
		if (!empty($tagResult)) {$operatort = 'IN';} else {$operatort = 'NOT IN'; $tagResult = false ;}
		if ($motion != NULL) {$operatorm = 'IN';} else {$operatorm = 'NOT IN'; $motion = false;}

		$formatter = \Yii::$app->formatter;
		$amount=0;
		$this_date = $date_from;
		$this_date_full = $this_date.' 00:00:00';
		$dd=$formatter->asTimestamp($this_date_full);
		$currency = Currencies::findOne($currency);

		$ii=0;

		while ($this_date <= $date_to) {

			$ii++;
			if ($ii>1000) {exit;}
				$planresult1 = Finances::find()
					->select (['date, SUM(money) as daily'])
					->Where(['IN', 'user_id', $users])
					->andWhere([$operatort, 'finances.id', $tagResult])
					->andWhere(['currency_id' => $currency])
					->andWhere([$operators, 'wallet_id', $walletsID])
			        ->andWhere([$operatorm, 'motion_id', $motion])
					// ->andWhere(['motion_id' => $motion])
					->andWhere([$operatorc, 'category_id', $category])
					->andWhere(['=', 'date', $this_date])
					->andWhere(['<>', 'deleted', 1])
					->groupBy(['date'])
					->asArray()->all();

				$motion_rev = ($motion == 1) ? 0 : 1; //получаем обратное движение денег. Расчёт только по доходам и расходам без учёта прочих движений

				$planresult2 = Finances::find()
					->select (['date, SUM(money) as daily'])
					->Where(['IN', 'user_id', $users])
					->andWhere([$operatort, 'finances.id', $tagResult])
					->andWhere(['currency_id' => $currency])
					->andWhere([$operators, 'wallet_id', $walletsID])
					->andWhere(['motion_id' => $motion_rev])
					->andWhere([$operatorc, 'category_id', $category])
					->andWhere(['=', 'date', $this_date])
					->andWhere(['<>', 'deleted', 1])
					->groupBy(['date'])
					->asArray()->all();

					$presult1 = isset($planresult1[0]['daily']) ? $planresult1[0]['daily'] : 0;
					$presult2 = isset($planresult2[0]['daily']) ? $planresult2[0]['daily'] : 0;

					//minus one day
					$prev_date = date('Y-m-d', $dd-86400);
					$prev2_date = isset($prev2_date) ? $prev2_date :  date('Y-m-d', $dd);
					$planres[$prev2_date] = (!isset($planres[$prev2_date])) ? 0 : $planres[$prev2_date];

				if (!(($presult1==0) AND ($presult2==0))) {
					if ($summation==true){$amount=$planres[$prev2_date]+$presult1 - $presult2;} else {$amount=$presult1 - $presult2;}

					$planres[$this_date] = $amount;

					$datejs = strtotime($this_date);
					$planresultArray[]=['name'=>$currency->name, 'date'=>$datejs, 'amount'=>$amount];
					$prev2_date = date('Y-m-d', $dd);
				}
					//plus 1 day
					$dd+=86400;
					$this_date = date('Y-m-d', $dd);
					//$this_date = date('Y-m-d', $dd+86400);

		}

		//костыль
		$planresultArray = isset($planresultArray) ? $planresultArray : Array() ;

		$planresult[] = ['name'=>$currency->name, 'data'=>$planresultArray];

			return $planresultArray;
	}



	/*--- functions ---*/
	private function getTaggedRecords($id)
	{
				//this redord's tags
			$record = Plans::find()->where(['id'=>$id])->with('tags')->one();

			$tagValues=[];
			if ($record->tags) $tagValues = array_merge($tagValues, $record->getTagValues(true));

			$atw = array_combine($tagValues, $tagValues);

			$atw=implode(",", $atw);

			switch ($record->tag_search) {
				case false:
					$tagResult = Finances::find()
						->Where(['deleted'=>false])
						->anyTagValues($atw)
						->select (['finances.id'])
						->column();
					break;

				case true:
					$tagResult = Finances::find()
						->Where(['deleted'=>false])
						->allTagValues($atw)
						->select (['finances.id'])
						->column();
					break;
			}

				return $tagResult;
	}


}
