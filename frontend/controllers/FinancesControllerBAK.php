<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Finances;
use frontend\models\FinancesSearch;
use frontend\models\FinsharedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Wallets;
use frontend\models\Walletgroups;
use frontend\models\Categories;
use frontend\models\Allocation;
use frontend\models\WConverter;
use frontend\models\Motions;
use frontend\models\Settingsuser;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use common\models\User;
use frontend\models\CategoriesSearch;
use frontend\models\Currencies;
use yii\helpers\Json;

/**
 * FinancesController implements the CRUD actions for Finances model.
 */
class FinancesController extends LoginController
{

	public function beforeAction($action)
	{
		if (in_array(Yii::$app->controller->action->id, ['myfinances', 'finshared'])) Yii::$app->getUser()->setReturnUrl(Url::current());
		return parent::beforeAction($action); //проверка на залогиненность
	}

	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	private function getWLastRecord($currentRecord, $wallet_ids = false)
	{
		if (!$wallet_ids) $wallet_ids = Wallets::getAllWalletIDs();

		$lastRecord = Finances::find()
			->Where(['=', 'date', $currentRecord->date])
			->AndWhere(['in', 'wallet_id', $wallet_ids])
			->AndWhere(['=', 'currency_id', $currentRecord->currency_id])
			->AndWhere(['=', 'deleted', false])
			->AndWhere(['<', 'id', $currentRecord->id])
			->orderBy([
				'date' => SORT_DESC,
				'id'=> SORT_DESC
			])
			->one();

		//if there is no records at this date
		//we must find data of previous date
		if (!$lastRecord) {

		$lastRecord = Finances::find()
			->Where(['<', 'date', $currentRecord->date])
			->AndWhere(['in', 'wallet_id', $wallet_ids])
			->AndWhere(['=', 'currency_id', $currentRecord->currency_id])
			->AndWhere(['=', 'deleted', false])
			->orderBy([
				'date' => SORT_DESC,
				'id'=> SORT_DESC
			])
			->one();
		}

		//If this record is the first of all,
		//it must be filled with dummy data
		$lastRecord = $lastRecord ? $lastRecord : $this->fillEmptyModel($lastRecord);

		return $lastRecord;
	}




	private function getWalletgroupLastRecord($currentRecord)
	{
/*		$walletgroup_id = Wallets::getWalletgroupID($currentRecord->wallet_id);

		$walletgroup_ids = Wallets::getWalletsID($walletgroup_id);*/
		$wallet_ids = Wallets::getWalletIDsDirectly($currentRecord->wallet_id);
		//This code checks if $currentRecord is a new record or not
		//we must use integer instead of NULL in a regular mySQL query
		//because NULL and integer require different comparison operators

		if ($currentRecord->id) {
		//getting common last record for this date
		$myLastRecord = Finances::find()
			->Where(['=', 'date', $currentRecord->date])
			//->AndWhere(['=', 'user_id', $currentRecord->user_id])
			->AndWhere(['in', 'wallet_id', $currentRecord->wallet_id])
			//->AndWhere(['in', 'wallet_id', $wallet_ids])
			->AndWhere(['=', 'currency_id', $currentRecord->currency_id])
			->AndWhere(['=', 'deleted', false])
			->AndWhere(['<', 'id', $currentRecord->id])
			->orderBy([
				'date' => SORT_DESC,
				'id'=> SORT_DESC
			])
			->one();

		//$currentRecord->user_id == NULL means the record is new
		//and we must just get the last record from the query
		} else {

		//getting common last record for this date
		$myLastRecord = Finances::find()
			->Where(['=', 'date', $currentRecord->date])
			->AndWhere(['=', 'user_id', $currentRecord->user_id])
			->AndWhere(['in', 'wallet_id', $wallet_ids])
			->AndWhere(['=', 'currency_id', $currentRecord->currency_id])
			->AndWhere(['=', 'deleted', false])
			->orderBy([
				'date' => SORT_DESC,
				'id'=> SORT_DESC
			])
			->one();
		}

		//if there is no records at this date
		//we must find data of previous date
		if (!$myLastRecord) {

		$myLastRecord = Finances::find()
			->Where(['<', 'date', $currentRecord->date])
			->AndWhere(['=', 'user_id', $currentRecord->user_id])
			->AndWhere(['in', 'wallet_id', $wallet_ids])
			->AndWhere(['=', 'currency_id', $currentRecord->currency_id])
			->AndWhere(['=', 'deleted', false])
			->orderBy([
				'date' => SORT_DESC,
				'id'=> SORT_DESC
			])
			->one();

		}

		//If this record is the first of all,
		//it must be filled with dummy data
		$myLastRecord = $myLastRecord ? $myLastRecord : $this->fillEmptyModel($myLastRecord);

		return $myLastRecord;
	}

	private function getCommonLastRecord($currentRecord)
	{

		$wallet_ids = Wallets::getWalletIDsDirectly($currentRecord->wallet_id);

		if ($currentRecord->id) {
		//getting common last record for this date
		$commonLastRecord = Finances::find()
			->Where(['=', 'date', $currentRecord->date])
			->AndWhere(['in', 'wallet_id', $wallet_ids])
			->AndWhere(['=', 'currency_id', $currentRecord->currency_id])
			->AndWhere(['=', 'deleted', false])
			->AndWhere(['<', 'id', $currentRecord->id])
			->orderBy([
				'date' => SORT_DESC,
				'id'=> SORT_DESC
			])
			->one();

		//$currentRecord->user_id == NULL means the record is new
		//and we must just get the last record from the query
		} else {

		//getting common last record for this date
		$commonLastRecord = Finances::find()
			->Where(['=', 'date', $currentRecord->date])
			->AndWhere(['in', 'wallet_id', $wallet_ids])
			->AndWhere(['=', 'currency_id', $currentRecord->currency_id])
			->AndWhere(['=', 'deleted', false])
			->orderBy([
				'date' => SORT_DESC,
				'id'=> SORT_DESC
			])
			->one();
		}

		//if there is no records at this date
		//we must find data of previous date
		if (!$commonLastRecord) {

		$commonLastRecord = Finances::find()
			->Where(['<', 'date', $currentRecord->date])
			->AndWhere(['in', 'wallet_id', $wallet_ids])
			->AndWhere(['=', 'currency_id', $currentRecord->currency_id])
			->AndWhere(['=', 'deleted', false])
			->orderBy([
				'date' => SORT_DESC,
				'id'=> SORT_DESC
			])
			->one();

		}

		//If the record is the first of all,
		//it must be filled with dummy data
		$commonLastRecord = $commonLastRecord ? $commonLastRecord : $this->fillEmptyModel($commonLastRecord);

		return $commonLastRecord;
	}


	private function getNextRecord($record)
	{
		if ($record->id) {

			$nextRecord = Finances::find()
				->Where(['=', 'date', $record->date])
/*				->AndWhere(['=', 'user_id', $record->user_id])
				->AndWhere(['=', 'wallet_id', $record->wallet_id])*/
				->AndWhere(['=', 'currency_id', $record->currency_id])
				->AndWhere(['=', 'deleted', false])
				->AndWhere(['>', 'id', $record->id])
				->orderBy([
					'date' => SORT_ASC,
					'id'=> SORT_ASC
				])
				->one();

		} else {

			$nextRecord = Finances::find()
				->Where(['=', 'date', $record->date])
/*				->AndWhere(['=', 'user_id', $record->user_id])
				->AndWhere(['=', 'wallet_id', $record->wallet_id])*/
				->AndWhere(['=', 'currency_id', $record->currency_id])
				->AndWhere(['=', 'deleted', false])
				->orderBy([
					'date' => SORT_ASC,
					'id'=> SORT_ASC
				])
				->one();

		}

		//if there is no records at this date
		//we must find data of previous date
		if (!$nextRecord) {

			$nextRecord = Finances::find()
				->Where(['>', 'date', $record->date])
/*				->AndWhere(['=', 'user_id', $record->user_id])
				->AndWhere(['in', 'wallet_id', $wallet_ids])*/
				->AndWhere(['=', 'currency_id', $record->currency_id])
				->AndWhere(['=', 'deleted', false])
				->orderBy([
					'date' => SORT_ASC,
					'id'=> SORT_ASC
				])
				->one();

		}

		return $nextRecord;
	}


	private function calcNewRecord($newRecord)
	{
		//последняя запись на моём текущем кошельке
		$myCurrentWalletLastRecord = $this->getWLastRecord($newRecord, $newRecord->wallet_id);
		//последняя запись на каком-либо моём кошельке в группе
		$myWalletgroupLastRecord = $this->getWLastRecord($newRecord, Wallets::getWalletIDsDirectly($newRecord->wallet_id, Yii::$app->user->identity->id));
		//последняя запись на каком-либо моём кошельке
		$myAnyWalletLastRecord = $this->getWLastRecord($newRecord, Wallets::findMyWallets(Yii::$app->user->identity->id));
		//последняя запись на каком-либо нашем кошельке в группе
		$commonWalletgroupLastRecord = $this->getWLastRecord($newRecord, Wallets::getWalletIDsDirectly($newRecord->wallet_id));
		//последняя запись на каком-либо нашем кошельке
		$commonWalletLastRecord = $this->getWLastRecord($newRecord);



		//старый баланс моего текущего кошелька такой валюты
		$newRecord->my_old_wallet_balance = $myCurrentWalletLastRecord->my_new_wallet_balance;
		//старый баланс моих кошельков такой ГРУППЫ такой валюты
		$newRecord->my_old_wgsumma_balance = $myWalletgroupLastRecord->my_new_wgsumma_balance;
		//старый баланс по всей моей такой валюте
		$newRecord->my_old_summa_balance = $myAnyWalletLastRecord->my_new_summa_balance;


	//	$newRecord->my_old_summa_balance = $commonWalletgroupLastRecord->my_new_summa_balance;
		//старый баланс всех наших кошельков такой ГРУППЫ такой валюты, он же our_old_wgsumma_balance
		$newRecord->our_old_wgsumma_balance = $commonWalletgroupLastRecord->our_new_wgsumma_balance;
		//старый баланс всех наших кошельков такой валюты
		$newRecord->our_old_summa_balance = $commonWalletLastRecord->our_new_summa_balance;

		switch ($newRecord->motion_id) {
			case '0':   // Расход
			case '8':   // Конвертация себе расход
				$newRecord->my_new_wallet_balance = $newRecord->my_old_wallet_balance - $newRecord->money;
				$newRecord->my_new_wgsumma_balance = $newRecord->my_old_wgsumma_balance - $newRecord->money; //xz
				$newRecord->my_new_summa_balance = $newRecord->my_old_summa_balance - $newRecord->money;
				$newRecord->our_new_summa_balance = $newRecord->our_old_summa_balance - $newRecord->money;
				$newRecord->our_new_wgsumma_balance = $newRecord->our_old_wgsumma_balance - $newRecord->money;
				break;

			case '2':   // Перевод
				$newRecord->my_new_wallet_balance = $newRecord->my_old_wallet_balance - $newRecord->money;
				$newRecord->my_new_wgsumma_balance = $newRecord->my_old_wgsumma_balance - $newRecord->money;
				$newRecord->my_new_summa_balance = $newRecord->my_old_summa_balance - $newRecord->money;
				$newRecord->our_new_summa_balance = $newRecord->our_old_summa_balance;
				$newRecord->our_new_wgsumma_balance = $newRecord->our_old_wgsumma_balance;
				break;


			case '4':   // Исх. себе
				$newRecord->my_new_wallet_balance = $newRecord->my_old_wallet_balance - $newRecord->money;
				$newRecord->my_new_wgsumma_balance = $newRecord->my_old_wgsumma_balance - $newRecord->money; //xz
				$newRecord->my_new_summa_balance = $newRecord->my_old_summa_balance;
				$newRecord->our_new_summa_balance = $newRecord->our_old_summa_balance;
				$newRecord->our_new_wgsumma_balance = $newRecord->our_old_wgsumma_balance - $newRecord->money;

			   break;

			case '1':   // Доход
			case '9':   // Конвертация себе приход
				$newRecord->my_new_wallet_balance = $newRecord->my_old_wallet_balance + $newRecord->money;
				$newRecord->my_new_wgsumma_balance = $newRecord->my_old_wgsumma_balance + $newRecord->money; //xz
				$newRecord->my_new_summa_balance = $newRecord->my_old_summa_balance + $newRecord->money;
				$newRecord->our_new_summa_balance = $newRecord->our_old_summa_balance + $newRecord->money;
				$newRecord->our_new_wgsumma_balance = $newRecord->our_old_wgsumma_balance + $newRecord->money;
				break;

			case '3':   // Привод
				$newRecord->my_new_wallet_balance = $newRecord->my_old_wallet_balance + $newRecord->money;
				$newRecord->my_new_wgsumma_balance = $newRecord->my_old_wgsumma_balance + $newRecord->money;
				$newRecord->my_new_summa_balance = $newRecord->my_old_summa_balance + $newRecord->money;
				$newRecord->our_new_summa_balance = $newRecord->our_old_summa_balance;
				$newRecord->our_new_wgsumma_balance = $newRecord->our_old_wgsumma_balance;
				break;

			case '5':   // Вх. себе
				$newRecord->my_new_wallet_balance = $newRecord->my_old_wallet_balance + $newRecord->money;
				$newRecord->my_new_wgsumma_balance = $newRecord->my_old_wgsumma_balance + $newRecord->money; //xz
				$newRecord->my_new_summa_balance = $newRecord->my_old_summa_balance;
				$newRecord->our_new_summa_balance = $newRecord->our_old_summa_balance;
				$newRecord->our_new_wgsumma_balance = $newRecord->our_old_wgsumma_balance + $newRecord->money;
				break;;
		}

		$newRecord->save();

	}


	/**
	 * Gets the remaining records.
	 *
	 * @param      model  $newRecord  The new record
	 *
	 * @return     array of models (can be NULL)
	 */
	private function getRemainingRecords($newRecord)
	{

		$remainingRecords = Finances::find()
		->Where(['>=', 'date', $newRecord->date])
		->AndWhere(['=', 'currency_id', $newRecord->currency_id])
		->AndWhere(['=', 'deleted', 0])
		->orderBy([
			'date' => SORT_ASC,
			'id'=> SORT_ASC
		])
		->all();


		return $remainingRecords;
	}

	/**
	 * Calculates the remaining records.
	 *
	 * @param      model  $newRecord  The new record
	 */

	private function calcRemainingRecords($newRecord)
	{
		//поиск всех последующих записей
		$remainingRecords = $this->getRemainingRecords($newRecord);
		//пересчёт всех последующих записей, если они есть
		//var_dump($remainingRecords); exit;
		if ($remainingRecords) {
			foreach ($remainingRecords as $nextRecord) {
				echo ' nextRecord id='.$nextRecord->id;
				$this->calcNewRecord($nextRecord);
			}
		}
	}


	private function getEditTime()
	{
		$date = new \DateTime('now'); //for timestamps
		$timestamp = $date->getTimestamp();

		return $timestamp;
/*		$cleanCommand = "UPDATE `finances` SET `update_time` = '0' WHERE `id` = '".$id."';";
		\Yii::$app->db->createCommand($cleanCommand)->execute();*/
	}



	private function fillEmptyModel($model)
	{
			$model = new Finances();
			$model->date = '0000-00-00';
			$model->my_old_wallet_balance = 0;
			$model->my_old_wgsumma_balance = 0;
			$model->my_old_summa_balance = 0;
			$model->our_old_wgsumma_balance = 0;
			$model->our_old_summa_balance = 0;
			$model->my_new_wallet_balance = 0;
			$model->my_new_wgsumma_balance = 0;
			$model->my_new_summa_balance = 0;
			$model->our_new_wgsumma_balance = 0;
			$model->our_new_summa_balance = 0;

			return $model;
	}






















	/**
	 * Lists all Finances models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionTesting()
	{






$newRecord=New Finances();
$newRecord->argh = 666;

echo $newRecord->argh;
exit;



echo '<br />';
echo '<br />';
echo '<br />';
echo '<br />';
echo '<br />';
echo '<br />';

$myLastRecord=$this->getCommonLastRecord($newRecord);


var_dump($myLastRecord);


/*
$insertQuery = "INSERT INTO `finances` (id,money,motion_id,category_id,user_id,wallet_id,currency_id,date,comment,my_old_wallet_balance,my_old_summa_balance,my_new_wallet_balance,my_new_summa_balance,our_old_wgsumma_balance,our_old_summa_balance,our_new_wgsumma_balance,our_new_summa_balance,timestamp,update_time,deleted) VALUES ('666',".rand(10, 10000).",".rand(0, 8).",12,2,3,1,'20".rand(10, 17)."-0".rand(1, 9)."-".rand(0, 2).rand(0, 9)."', 'my test comment 123456  my comment test 234', ".rand(10, 10000).",".rand(10, 10000).",".rand(10, 10000).",".rand(10, 10000).",".rand(10, 10000).",".rand(10, 10000).",".rand(10, 10000).",".rand(10, 10000).",1474638200,0,0); ";


if (\Yii::$app->db->createCommand($insertQuery)->execute() != TRUE) {
	//echo "New record created successfully".'<br />';
	echo 'error';
}
*/



/*
for ($i=0; $i < 1000; $i++) {
$insertQuery = "";
for ($j=0; $j < 100; $j++) {
	# code...

$insertQuery .= "INSERT INTO `finances` (id,money,motion_id,category_id,user_id,wallet_id,currency_id,date,comment,my_old_wallet_balance,my_old_summa_balance,my_new_wallet_balance,my_new_summa_balance,our_old_wgsumma_balance,our_old_summa_balance,our_new_wgsumma_balance,our_new_summa_balance,timestamp,update_time,deleted) VALUES ('',".rand(10, 10000).",".rand(0, 8).",12,2,3,1,'20".rand(10, 17)."-0".rand(1, 9)."-".rand(0, 2).rand(0, 9)."', 'my test comment 123456  my comment test 234', ".rand(10, 10000).",".rand(10, 10000).",".rand(10, 10000).",".rand(10, 10000).",".rand(10, 10000).",".rand(10, 10000).",".rand(10, 10000).",".rand(10, 10000).",1474638200,0,0); ";
}



if (\Yii::$app->test->createCommand($insertQuery)->execute() != TRUE) {
	//echo "New record created successfully".'<br />';


	echo 'error';

}

}
*/





		return $this->render('testing', [

		]);

	}

	public function isLast($model)
	{
		return $model->date < Finances::find()->orderBy('date DESC')->one()->date;
	}

	public function actionMyfinances()
	{
		$searchModel = new FinancesSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('myfinances', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'my_balance' => Finances::getMyBalance(),
			'our_balance' => Finances::getOurBalance(),
			'my_balance2' => Finances::allBalances(Yii::$app->user->identity->id),
			'our_balance2' => Finances::allBalances(User::getAllUserids())
		]);
	}

	public function actionFinshared()
	{
		$searchModel = new FinsharedSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('finshared', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'my_balance' => Finances::getMyBalance(),
			'our_balance' => Finances::getOurBalance(),
			'my_balance2' => Finances::allBalances(Yii::$app->user->identity->id),
			'our_balance2' => Finances::allBalances(User::getAllUserids())
		]);
	}

	/**
	 * Displays a single Finances model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Finances model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
/*
	public function actionCreate()
	{
		$model = new Finances();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

*/



	/**
	 * Добавляет Доход или траты.
	 * @param integer $motion_id
	 * здесь нужен для того, чтобы задать тип движения денег по-умолчанию (0=траты) на случай, если кто-то умышленно удалит из адресной строки параметр motion_id
	 * @param integer $wallet_id
	 * @return mixed
	 */

	public function actionAddrecord($motion_type = 0)
	{
		$newRecord = new Finances();
		$newRecord->user_id = Yii::$app->user->identity->id;
		$newRecord->date = new \DateTime('now');
		$newRecord->motion_id = $motion_type;

		if ($newRecord->load(Yii::$app->request->post()) && $newRecord->validate()) {


			$this->calcNewRecord($newRecord);

			//Расчёт всех последующих записей
			$this->calcRemainingRecords($newRecord);


			do {
				//Этот блок отвечает за сохранение данных в форме, когда нажимаем кнопку "Ещё",
				//В нём вы обнуляем те поля, которые не хотим видеть в форме заполненными
				if(isset($_POST['new'])) {
					$newRecord->money=NULL;
					$newRecord->comment=NULL;
					//tips:
					//$newRecord->currency_id=NULL;
					//$newRecord->wallet_id=NULL;
					//$newRecord->category_id=NULL;
					//$newRecord->date = new \DateTime('now');
					break;
				} else {
					return $this->goBack();
				}
			}while (0);
		}

		return $this->render('addrecord', [
			'motion_id' => $motion_type, // передаём во вьюху, чтобы определить надписи.
			'categories' => Categories::findCategories(),
				'currencies' => Currencies::findCurrencies(),
				'my_wallet' => Wallets::findWallet(Yii::$app->user->identity->id),
				'model' => $newRecord,
			]);
	}

	/**
	 * Updates an existing Finances model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{


		$record = $this->findModel($id);

		if ($record->load(Yii::$app->request->post()) && $record->validate()) {

$date = new \DateTime('now'); //for timestamps
$record->edit_time = $date->getTimestamp();
			$this->calcNewRecord($record);
			$this->calcRemainingRecords($record);


		if ($record->related_record) {
			$record_related = $this->findModel($record->related_record);
			$record_related->edit_time = $record->edit_time;
			$this->calcNewRecord($record_related);
			$this->calcRemainingRecords($record_related);
/*
			$nextRecord = $this->getNextRecord($record_related);
			if ($nextRecord) {
				$this->calcNewRecord($nextRecord);
				$this->calcRemainingRecords($nextRecord);
			}*/


		}

			return $this->goBack();
		} else {
			return $this->render('update', [
				'categories' => Categories::findCategories(),
				'currencies' => Currencies::findCurrencies(),
				// 'motion_types' => $this->findMotionTypes(),
				'motion_types' => Motions::findMotions(),
				'my_wallet' => Wallets::findWallet(Yii::$app->user->identity->id),
				'model' => $record,
			]);
		}
	}

	/**
	 * Deletes an existing Finances model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		//we can just delete a record from db like this:
		//$this->findModel($id)->delete();
		//but we will hide records instead of real deletion
		$record = $this->findModel($id);
		$record->deleted = true;
		$record->save();

		$nextRecord = $this->getNextRecord($record);
		echo '<br />';
		echo '<br />';
		echo '<br />';

		if ($nextRecord) {
echo '<br />';
echo '<br />';
echo '<br />';

			//echo $nextRecord->id; exit;
			$this->calcNewRecord($nextRecord);

		}
		$this->calcRemainingRecords($record);

		if ($record->related_record) {
			$record_related = $this->findModel($record->related_record);

			$record_related->deleted = true;
			$record_related->save();

			$nextRecord = $this->getNextRecord($record_related);
			if ($nextRecord) {
				$this->calcNewRecord($nextRecord);
				$this->calcRemainingRecords($nextRecord);
			}

		}



			return $this->goBack();

	}


	public function actionWtransfer() //передача денег в другой кошель
	{

		$model = new Allocation();
		$model->user_id = Yii::$app->user->identity->id;
		$model->date = new \DateTime('now');

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {

			$model_from = new Finances();
			$model_from->motion_id = 4;

			$model_to = new Finances();
			$model_to->motion_id = 5;

			$model_from->user_id = $model->user_id;
			$model_to->user_id = $model->user_id;
			$model_from->money = $model->money;
			$model_to->money = $model->money;
			$model_from->currency_id = $model->currency_id;
			$model_to->currency_id = $model->currency_id;
			$model_from->date = $model->date;
			$model_to->date = $model->date;
			$model_from->wallet_id = $model->old_wallet_id;
			$model_to->wallet_id = $model->new_wallet_id;
			$model_to->comment = 'Автоматический комментарий перевода денег с кошелька '.$model_from->wallet_id.' на кошелёк '.$model_to->wallet_id;
			$model_from->comment = $model->comment;

			$model_from->category_id = 1; //id=1 в таблице categories
			$model_to->category_id = 2; //id=2 в таблице categories

			$this->calcNewRecord($model_from);
			$this->calcRemainingRecords($model_from);
			$model_to->related_record = $model_from->id;
			$model_to->save();
			print_r($this->calcNewRecord($model_to));
			$this->calcRemainingRecords($model_to);
			$model_from->related_record = $model_to->id;
			$model_from->save();

		return $this->goBack();
		}

		// фишка исключительно для красоты:
		// выбираем второй ключ в массиве кошельков, чтобы его подставить в дропдаун
		$my_wallet=Wallets::findWallet(Yii::$app->user->identity->id);
		$model->new_wallet_id=array_keys($my_wallet, next($my_wallet));
		// конец фишки

		return $this->render('wtransfer', [
			'my_wallet' => $my_wallet,
			'currencies' => Currencies::findCurrencies(),
			'model' => $model,
		]);

	}

	public function actionWconvert() //Конвертирование денег пользователя из валюты в валюту с любого своего  кошелька на любой свой кошелёк
	{

		$model = new WConverter();
		$model->user_id = Yii::$app->user->identity->id;
		//$model->date = new \DateTime('now');
		$model->date = new \DateTime('now');

		//if ($model->load(Yii::$app->request->post()) && $model->validate()) {
		if ($model->load(Yii::$app->request->post()) ) {

//$date = new \DateTime('now');



			$model_from = new Finances();
			$model_from->motion_id = 8;

			$model_to = new Finances();
			$model_to->motion_id = 9;

//$model_from->timestamp=$date->getTimestamp();
//$model_to->timestamp=$model_from->timestamp;


			$model_from->user_id = $model->user_id;
			$model_to->user_id = $model->user_id;

			$model_from->money = $model->money;
			$model_to->money = ($model->action == 1) ? $model->money / $model->coefficient : $model->money * $model->coefficient;

			$model_to->money = round($model_to->money, 0, PHP_ROUND_HALF_DOWN);

			$model_from->currency_id = $model->old_currency_id;
			$model_to->currency_id = $model->new_currency_id;

			$model_from->date = $model->date;
			$model_to->date = $model->date;
			$model_from->wallet_id = $model->old_wallet_id;
			$model_to->wallet_id = $model->new_wallet_id;
			$model_to->comment = 'Автоматический комментарий конвертации денег с кошелька '.$model_from->wallet_id.' на кошелёк '.$model_to->wallet_id;
			$model_from->comment = $model->comment;

			$model_from->category_id = 1; //id=1 в таблице categories
			$model_to->category_id = 2; //id=2 в таблице categories

			$this->calcNewRecord($model_from);
			$this->calcRemainingRecords($model_from);
			$model_to->related_record = $model_from->id;
			$model_to->save();
			$this->calcNewRecord($model_to);
			$this->calcRemainingRecords($model_to);
			$model_from->related_record = $model_to->id;
			$model_from->save();
			//$this->updateTimeCleaner($model_to->id);
			//$this->updateTimeCleaner($model_from->id);

		return $this->goBack();
		}

		// фишка исключительно для красоты:
		// выбираем второй ключ в массиве кошельков, чтобы его подставить в дропдаун
		$my_wallet=Wallets::findWallet(Yii::$app->user->identity->id);
		$model->new_wallet_id=array_keys($my_wallet,next($my_wallet));
		//$my_currency=$this->findCurrencies(Yii::$app->user->identity->id);
		$my_currency=Currencies::findCurrencies(Yii::$app->user->identity->id);
		$model->new_currency_id=array_keys($my_currency,next($my_currency));
		// конец фишки

		return $this->render('wconvert', [
			'my_wallet' => $my_wallet,
			//'currencies' => $this->findCurrencies(),
			'currencies' => Currencies::findCurrencies(),
			'model' => $model,
		]);

	}

	public function actionUtransfer()	//передача денег в кошелёк другому пользователю
	{
		$model = new Allocation();
		$model->date = new \DateTime('now');

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {

			$model_from = new Finances();
			$model_from->motion_id = 2;

			$model_to = new Finances();
			$model_to->motion_id = 3;

			$model_from->user_id = Yii::$app->user->identity->id;
			$model_to->user_id = $model->user_id;
			$model_from->money = $model->money;
			$model_to->money = $model->money;
			$model_from->currency_id = $model->currency_id;
			$model_to->currency_id = $model->currency_id;
			$model_from->date = $model->date;
			$model_to->date = $model->date;
			$model_from->wallet_id = $model->old_wallet_id;
			$model_to->wallet_id = $model->new_wallet_id;
			$model_from->comment = $model->comment;
			$model_to->comment = 'Автоматический комментарий перевода денег с кошелька '.$model_from->wallet_id.' Пользователя '.$model_from->user_id.' на кошелёк '.$model_to->wallet_id. ' пользователя '.$model_to->user_id;

			$model_from->category_id = 3; //id=1 в таблице categories
			$model_to->category_id = 4; //id=2 в таблице categories

			$this->calcNewRecord($model_from);
			$this->calcRemainingRecords($model_from);
			$model_to->related_record = $model_from->id;
			$model_to->save();
			$this->calcNewRecord($model_to);
			$this->calcRemainingRecords($model_to);
			$model_from->related_record = $model_to->id;
			$model_from->save();
			//$this->updateTimeCleaner($model_to->id);
			//$this->updateTimeCleaner($model_from->id);

			return $this->goBack();
		}

		$my_wallet = Wallets::findWallet(Yii::$app->user->identity->id);
		$recipient = ArrayHelper::map(
			User::find() 					//TODO: исключить удалённых\неактивных
			->Where(['<>', 'id', Yii::$app->user->identity->id])
			->all(),
		'id','realname');

		//$his = (Yii::$app->user->identity->id == 1) ? 2 : 1 ;//TODO! сделать зависимый дропдаун
		// $his_wallet = Wallets::findWallet($his); //TODO! сделать зависимый дропдаун

		return $this->render('utransfer', [
			'my_wallet' => $my_wallet,
			'currencies' => Currencies::findCurrencies(),
			'recipient' => $recipient,
			//'his_wallet' => $his_wallet,
			'model' => $model,
		]);

	}

	public function actionUtsubcat() {
		$out = [];
		if (isset($_POST['depdrop_parents'])) {
			$parents = $_POST['depdrop_parents'];
			if ($parents != null) {
				$cat_id = $parents[0];

				//$out = self::getSubCatList($cat_id);

				$output = ArrayHelper::map(
					Wallets::find()
					->Where(['deleted'=>false])
					->andWhere(['user_id'=>$cat_id])
					->all(),
				'id','name');

				foreach ($output as $key => $value) {
					$out[] = ['id'=>$key, 'name'=>$value];
				}

				// the getSubCatList function will query the database based on the
				// cat_id and return an array like below:
				// [
				//    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
				//    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
				// ]
				echo Json::encode(['output'=>$out, 'selected'=>'']);
				return;
			}
		}
		echo Json::encode(['output'=>'', 'selected'=>'']);
	}

	/**
	 * Вспомогательная функция для калькуляции части данных в модели Finances
	 * Вызывыется при добавлении Доходов\Затрат в actionAaddrecord, переводе денег из кошелька в кошелёк в actionWtransfer
	 * @param model $model
	 * здесь нужен для того, чтобы задать тип движения денег по-умолчанию (0=траты) на случай, если кто-то умышленно удалит из адресной строки параметр motion_type
	 * @return mixed
	 */
	public function calculate($model, $timecalc='<', $calc='<=', $notin='not in', $notarr=false)

	{

		if (!isset($model->timestamp)) {
			$date = new \DateTime('now');
			$model->timestamp = $date->getTimestamp();
		}

		// my_old_wallet_balance Старый баланс на текущем кошельке;
		$myWalletDohod = Finances::find()
		->where(['user_id' => $model->user_id])
		->andWhere(['motion_id' => [1,3,5,9]])
		->andWhere(['wallet_id' => $model->wallet_id])
		->andWhere(['currency_id' => $model->currency_id])
		->andWhere(['deleted' => false])
		->andWhere([$calc, 'date', $model->date])
		->andWhere([$notin, 'id', $notarr])
		->andWhere([$timecalc, 'timestamp', $model->timestamp])
		->sum('money');

		$myWalletRashod = Finances::find()
		->where(['user_id' =>  $model->user_id ])
		->andWhere(['motion_id' => [0,2,4,8]])
		->andWhere(['wallet_id' => $model->wallet_id])
		->andWhere(['currency_id' => $model->currency_id])
		->andWhere(['deleted' => false])
		->andWhere([$calc, 'date', $model->date])
		->andWhere([$notin, 'id', $notarr])
		->andWhere([$timecalc, 'timestamp', $model->timestamp])
		->sum('money');

		$my_old_wallet_balance = $myWalletDohod - $myWalletRashod; // Старый баланс на текущем кошельке;
		$model->my_old_wallet_balance = $my_old_wallet_balance;

		// my_old_summa_balance Старый баланс на всех моих кошельках;
		$mySummaDohod = Finances::find()
		->where(['user_id' => $model->user_id  ])
		->andWhere(['motion_id' => [1,3,5,9]])
		->andWhere(['currency_id' => $model->currency_id])
		->andWhere(['deleted' => false])
		->andWhere([$calc, 'date', $model->date])
		->andWhere([$notin, 'id', $notarr])
		->andWhere([$timecalc, 'timestamp', $model->timestamp])
		->sum('money');
		$mySummaRashod = Finances::find()
		->where(['user_id' => $model->user_id ])
		->andWhere(['motion_id' => [0,2,4,8]])
		->andWhere(['currency_id' => $model->currency_id])
		->andWhere(['deleted' => false])
		->andWhere([$calc, 'date', $model->date])
		->andWhere([$notin, 'id', $notarr])
		->andWhere([$timecalc, 'timestamp', $model->timestamp])
		->sum('money');
		$my_old_summa_balance = $mySummaDohod - $mySummaRashod; // Старый баланс на всех моих кошельках;
		$model->my_old_summa_balance = $my_old_summa_balance;

		// our_old_wgsumma_balance Наш старый баланс на таком типе кошелька
		$walletgroup_id = Wallets::find()
		->where(['id' => $model->wallet_id])
		->andWhere(['deleted' => false])
		->select('walletgroup_id'); //получаем id типа кошелька

		$wallets = Wallets::find()
		->where(['walletgroup_id' => $walletgroup_id])
		->andWhere(['deleted' => false])
		->select('id'); //получаем id всех кошельков такого типа

		$OurOldWalletDohod = Finances::find()
		->where(['IN', 'wallet_id', $wallets])
		->andWhere(['motion_id' => [1,3,5,9]])
		->andWhere(['currency_id' => $model->currency_id])
		->andWhere(['deleted' => false])
		->andWhere([$calc, 'date', $model->date])
		->andWhere([$notin, 'id', $notarr])
		->andWhere([$timecalc, 'timestamp', $model->timestamp])
		->sum('money'); // Наш доход на таком типе кошелька
		$OurOldWalletRashod = Finances::find()
		->where(['IN', 'wallet_id', $wallets])
		->andWhere(['motion_id' => [0,2,4,8]])
		->andWhere(['currency_id' => $model->currency_id])
		->andWhere(['deleted' => false])
		->andWhere([$calc, 'date', $model->date])
		->andWhere([$notin, 'id', $notarr])
		->andWhere([$timecalc, 'timestamp', $model->timestamp])
		->sum('money'); // Наш расход на таком типе кошелька

		$our_old_wgsumma_balance = $OurOldWalletDohod - $OurOldWalletRashod; //Наш старый баланс на таком типе кошелька
		$model->our_old_wgsumma_balance = $our_old_wgsumma_balance;

		// our_old_summa_balance Наш старый суммарный баланс
		$OurOldSummaDohod = Finances::find()
		->Where(['motion_id' => [1,3,5,9]])
		->andWhere(['currency_id' => $model->currency_id])
		->andWhere(['deleted' => false])
		->andWhere([$calc, 'date', $model->date])
		->andWhere([$notin, 'id', $notarr])
		->andWhere([$timecalc, 'timestamp', $model->timestamp])
		->sum('money');
		$OurOldSummaRashod = Finances::find()
		->Where(['motion_id' => [0,2,4,8]])
		->andWhere(['currency_id' => $model->currency_id])
		->andWhere(['deleted' => false])
		->andWhere([$calc, 'date', $model->date])
		->andWhere([$notin, 'id', $notarr])
		->andWhere([$timecalc, 'timestamp', $model->timestamp])
		->sum('money');
		$our_old_summa_balance = $OurOldSummaDohod - $OurOldSummaRashod; // Наш старый суммарный баланс
		$model->our_old_summa_balance = $our_old_summa_balance;


		switch ($model->motion_id) {
			case '0':   // Расход
			case '8':   // Конвертация себе расход
				$model->my_new_wallet_balance = $my_old_wallet_balance - $model->money;
				$model->my_new_summa_balance = $my_old_summa_balance - $model->money;
				$model->our_new_summa_balance = $our_old_summa_balance - $model->money;
				$model->our_new_wgsumma_balance = $our_old_wgsumma_balance - $model->money;
				break;

			case '2':   // Перевод
				$model->my_new_wallet_balance = $my_old_wallet_balance - $model->money;
				$model->my_new_summa_balance = $my_old_summa_balance - $model->money;
				$model->our_new_summa_balance = $our_old_summa_balance;
				$model->our_new_wgsumma_balance = $our_old_wgsumma_balance;
				break;


			case '4':   // Исх. себе
				$model->my_new_wallet_balance = $my_old_wallet_balance - $model->money;
				$model->my_new_summa_balance = $my_old_summa_balance;
				$model->our_new_summa_balance = $our_old_summa_balance;
				$model->our_new_wgsumma_balance = $our_old_wgsumma_balance - $model->money;

			   break;

			case '1':   // Доход
			case '9':   // Конвертация себе приход
				$model->my_new_wallet_balance = $my_old_wallet_balance + $model->money;
				$model->my_new_summa_balance = $my_old_summa_balance + $model->money;
				$model->our_new_summa_balance = $our_old_summa_balance + $model->money;
				$model->our_new_wgsumma_balance = $our_old_wgsumma_balance + $model->money;
				break;

			case '3':   // Привод
				$model->my_new_wallet_balance = $my_old_wallet_balance + $model->money;
				$model->my_new_summa_balance = $my_old_summa_balance + $model->money;
				$model->our_new_summa_balance = $our_old_summa_balance;
				$model->our_new_wgsumma_balance = $our_old_wgsumma_balance;
				break;

			case '5':   // Вх. себе
				$model->my_new_wallet_balance = $my_old_wallet_balance + $model->money;
				$model->my_new_summa_balance = $my_old_summa_balance;
				$model->our_new_summa_balance = $our_old_summa_balance;
				$model->our_new_wgsumma_balance = $our_old_wgsumma_balance + $model->money;

			default:
				# code...
				break;;

			}
	}

	public function reCalculate($model, $findOperator='>', $timecalc='<')
	{

		$mdates=array();
		$marray=array();

		$arRecalc = ArrayHelper::toArray($this->findRecords($model, $findOperator)); //после вставки, исключая

		if (isset($arRecalc)){

			$replacesQuery = ' id = VALUES (id), money = VALUES (money), motion_id = VALUES (motion_id), category_id = VALUES (category_id), user_id = VALUES (user_id), wallet_id = VALUES (wallet_id), currency_id = VALUES (currency_id), date = VALUES (date), comment = VALUES (comment), my_old_wallet_balance = VALUES (my_old_wallet_balance), my_old_summa_balance = VALUES (my_old_summa_balance), my_new_wallet_balance = VALUES (my_new_wallet_balance), my_new_summa_balance = VALUES (my_new_summa_balance), our_old_wgsumma_balance = VALUES (our_old_wgsumma_balance), our_old_summa_balance = VALUES (our_old_summa_balance), our_new_wgsumma_balance = VALUES (our_new_wgsumma_balance), our_new_summa_balance = VALUES (our_new_summa_balance), timestamp = VALUES (timestamp), update_time = VALUES (update_time), deleted = VALUES (deleted)';

		$updateColumns = '(id,money,motion_id,category_id,user_id,wallet_id,currency_id,date,comment,my_old_wallet_balance,my_old_summa_balance,my_new_wallet_balance,my_new_summa_balance,our_old_wgsumma_balance,our_old_summa_balance,our_new_wgsumma_balance,our_new_summa_balance,timestamp,update_time,deleted)';




foreach ($arRecalc as $key => $value) {

$model = new Finances();
$model->id = $arRecalc[$key]['id'];
$model->money = $arRecalc[$key]['money'];
$model->motion_id = $arRecalc[$key]['motion_id'];
$model->category_id = $arRecalc[$key]['category_id'];
$model->user_id = $arRecalc[$key]['user_id'];
$model->wallet_id = $arRecalc[$key]['wallet_id'];
$model->currency_id = $arRecalc[$key]['currency_id'];
$model->date = $arRecalc[$key]['date'];
$model->comment = $arRecalc[$key]['comment'];
$model->my_old_wallet_balance = $arRecalc[$key]['my_old_wallet_balance'];
$model->my_old_summa_balance = $arRecalc[$key]['my_old_summa_balance'];
$model->my_new_wallet_balance = $arRecalc[$key]['my_new_wallet_balance'];
$model->my_new_summa_balance = $arRecalc[$key]['my_new_summa_balance'];
$model->our_old_wgsumma_balance = $arRecalc[$key]['our_old_wgsumma_balance'];
$model->our_old_summa_balance = $arRecalc[$key]['our_old_summa_balance'];
$model->our_new_wgsumma_balance = $arRecalc[$key]['our_new_wgsumma_balance'];
$model->our_new_summa_balance = $arRecalc[$key]['our_new_summa_balance'];
$model->timestamp = $arRecalc[$key]['timestamp'];
$model->update_time = $arRecalc[$key]['update_time'];
$model->deleted = $arRecalc[$key]['deleted'];

	if (empty($marray)){

		$mdates = Finances::find()
			->where(['date' => $model->date ])
			//->andWhere(['user_id' => Yii::$app->user->identity->id])
			->andWhere(['deleted' => false])
			->andWhere(['currency_id' => $model->currency_id])
			->select('id')
			->asArray()
			->all()
			;

		foreach ($mdates as $key => $value) {
			$marray[]=$value['id'];
		}
	}

	$this->calculate($model, $timecalc, '<=', 'not in', $marray);


foreach ($marray as $keym => $valuem) {
		if ($valuem == $model->id) {unset($marray[$keym]); break;}
}

$model->update_time = ($model->update_time == false) ? 0 : 1;
$model->deleted = ($model->deleted == false) ? 0 : 1;

 $valuesQuery = '('.$model->id.','.$model->money.','.$model->motion_id.','.$model->category_id.','.$model->user_id.','.$model->wallet_id.','.$model->currency_id.', "'.$model->date.'","'.$model->comment.'",'.$model->my_old_wallet_balance.','.$model->my_old_summa_balance.','.$model->my_new_wallet_balance.','.$model->my_new_summa_balance.','.$model->our_old_wgsumma_balance.','.$model->our_old_summa_balance.','.$model->our_new_wgsumma_balance.','.$model->our_new_summa_balance.','.$model->timestamp.','.$model->update_time.','.$model->deleted.')';
$updateQuery = 'INSERT INTO `finances`'.' '.$updateColumns.' VALUES '.$valuesQuery.' ON DUPLICATE KEY UPDATE'.$replacesQuery;

echo "<br/><br/><br/><br/><br/><br/>".$updateQuery;


// \Yii::$app->db->createCommand($updateQuery)->execute();




}


}

	}

/*

		public function findMotionTypes()
	{
		//Хардкод для редактирования сделанной записи по доходам или расходам
		$motion_types=['0' => 'Траты', '1' => 'Доход'];

		//use yii\helpers\ArrayHelper;
		//$motion_types=ArrayHelper::map($query_motiontypes, 'id', 'currency');
		return $motion_types;
	}

*/




	public function findRecords($model,$operator='<=') {// нахождение записей до\после вставки
		$result = Finances::find()
		//->select('*')
		->where([$operator, 'date', $model->date])
		//->andwhere(['=', 'user_id', $model->user_id])
		//->andwhere(['=', 'wallet_id', $model->wallet_id])
		->andwhere(['=', 'currency_id', $model->currency_id])
		->andwhere(['deleted' => false])
		->orderBy(['date' => SORT_ASC, 'timestamp' => SORT_ASC])
		->asArray()
		->all()
		;

	   return $result;
	}

	/**
	 * Finds the Finances model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Finances the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Finances::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('Запрошенная страница не существует.');
		}
	}



}
