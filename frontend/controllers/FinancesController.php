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
use frontend\models\Categroups;
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
use frontend\models\Settingsadmin;
use frontend\models\Plans;

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

	/**
	 * Gets the last record.
	 *
	 * @param      model   	$currentRecord  The current record
	 * @param      boolean  $wallet_ids     The wallet identifiers
	 *
	 * @return     <type>   The w last record.
	 */
	private function getWLastRecord($currentRecord, $wallet_ids = false, $test=false)
	{
		//If there is no wallets, we must use them all
		if (!$wallet_ids) $wallet_ids = Wallets::getAllWalletIDs();

		//if there is no id, we must use next autoincrement id (+1 from max)
		$ids = $currentRecord->id ? $currentRecord->id : Finances::find()->select(['id'])->orderBy(['id'=>SORT_DESC])->one()->id + 1;

		$lastRecord = Finances::find()
			->Where(['=', 'date', $currentRecord->date])
			->AndWhere(['in', 'wallet_id', $wallet_ids])
			->AndWhere(['=', 'currency_id', $currentRecord->currency_id])
			->AndWhere(['=', 'deleted', false])
			->AndWhere(['<', 'id', $ids])
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
			->AndWhere(['in', 'wallet_id', $currentRecord->wallet_id])
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
		//if ($newRecord->id == 29) {print_r(Wallets::getWalletIDsDirectly($newRecord->wallet_id, $newRecord->user_id));exit;}
		$myWalletgroupLastRecord = $this->getWLastRecord($newRecord, Wallets::getWalletIDsDirectly($newRecord->wallet_id, $newRecord->user_id));
		//последняя запись на каком-либо моём кошельке
		$myAnyWalletLastRecord = $this->getWLastRecord($newRecord, Wallets::findMyWallets($newRecord->user_id), true);
		//последняя запись на каком-либо нашем кошельке в группе
		$commonWalletgroupLastRecord = $this->getWLastRecord($newRecord, Wallets::getWalletIDsDirectly($newRecord->wallet_id));
		//последняя запись на каком-либо нашем кошельке
		$commonWalletLastRecord = $this->getWLastRecord($newRecord, false);


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
			case '2':   // Перевод
			case '4':   // Исх. себе
				$newRecord->my_new_wallet_balance = $newRecord->my_old_wallet_balance - $newRecord->money;
				$newRecord->my_new_wgsumma_balance = $newRecord->my_old_wgsumma_balance - $newRecord->money; //xz
				$newRecord->my_new_summa_balance = $newRecord->my_old_summa_balance - $newRecord->money;
				$newRecord->our_new_summa_balance = $newRecord->our_old_summa_balance - $newRecord->money;
				$newRecord->our_new_wgsumma_balance = $newRecord->our_old_wgsumma_balance - $newRecord->money;
				break;

			case '1':   // Доход
			case '9':   // Конвертация себе приход
			case '3':   // Привод
			case '5':   // Вх. себе
				$newRecord->my_new_wallet_balance = $newRecord->my_old_wallet_balance + $newRecord->money;
				$newRecord->my_new_wgsumma_balance = $newRecord->my_old_wgsumma_balance + $newRecord->money; //xz
				$newRecord->my_new_summa_balance = $newRecord->my_old_summa_balance + $newRecord->money;
				$newRecord->our_new_summa_balance = $newRecord->our_old_summa_balance + $newRecord->money;
				$newRecord->our_new_wgsumma_balance = $newRecord->our_old_wgsumma_balance + $newRecord->money;
				break;
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
		->AndWhere(['=', 'deleted', false])
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

	private function calcRemainingRecords($newRecord = false)
	{
		if ($newRecord) {
			//поиск всех последующих записей
			$remainingRecords = $this->getRemainingRecords($newRecord);
			//пересчёт всех последующих записей, если они есть
			//var_dump($remainingRecords); exit;
			if ($remainingRecords) {
				foreach ($remainingRecords as $nextRecord) {
					$this->calcNewRecord($nextRecord);
				}
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

	public function isLast($model)
	{
		return $model->date < Finances::find()->orderBy('date DESC')->one()->date;
	}

	public function actionMyfinances()
	{
		$searchModel = new FinancesSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		if (!Yii::$app->session->get('session_using')) {
			$settingsUserModel = Settingsuser::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
			$settingsUser = $settingsUserModel ? $settingsUserModel->settings : false ;

			return $this->render('myfinances', [
			'motionTypes' => ArrayHelper::map(Motions::find()->orderBy('id')->asArray()->all(), 'type', 'name'),
			'categroups' => ArrayHelper::map(Categroups::find()->where(['deleted' => false])->orderBy('cgroupname')->asArray()->all(), 'id', 'cgroupname'),
			'categories' => Categories::findCategories(),
			'users' => ArrayHelper::map(User::find()->orderBy('realname')->asArray()->all(), 'id', 'realname'),
			'wallets' => ArrayHelper::map(Wallets::find()->where(['user_id'=>Yii::$app->user->identity->id])->andwhere(['deleted' => false])->orderBy('name')->asArray()->all(), 'id', 'name'),
			'walletGroups' => ArrayHelper::map(Walletgroups::find()->where(['deleted' => false])->orderBy('name')->asArray()->all(), 'id', 'name'),
			'currencies' => Currencies::findCurrencies(),

			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'my_balance' => Finances::getMyBalance(),
			'our_balance' => Finances::getOurBalance(),
			'my_balance2' => Finances::allBalances(Yii::$app->user->identity->id),
			'our_balance2' => Finances::allBalances(User::getAllUserids()),
			'settingsUser' => $settingsUser,
			'tags' => $this->getAllTags(true),
			]);
		}

		return $this->render('myfinances_ses', [
			'motionTypes' => ArrayHelper::map(Motions::find()->orderBy('id')->asArray()->all(), 'type', 'name'),
			'categroups' => ArrayHelper::map(Categroups::find()->where(['deleted' => false])->orderBy('cgroupname')->asArray()->all(), 'id', 'cgroupname'),
			'categories' => Categories::findCategories(),
			'users' => ArrayHelper::map(User::find()->orderBy('realname')->asArray()->all(), 'id', 'realname'),
			'walletGroups' => ArrayHelper::map(Walletgroups::find()->where(['deleted' => false])->orderBy('name')->asArray()->all(), 'id', 'name'),
			'wallets' => ArrayHelper::map(Wallets::find()->where(['user_id'=>Yii::$app->user->identity->id])->andwhere(['deleted' => false])->orderBy('name')->asArray()->all(), 'id', 'name'),
			'currencies' => Currencies::findCurrencies(),

			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'my_balance' => Finances::getMyBalance(),
			'our_balance' => Finances::getOurBalance(),
			'my_balance2' => Finances::allBalances(Yii::$app->user->identity->id),
			'our_balance2' => Finances::allBalances(User::getAllUserids()),
			'tags' => $this->getAllTags(true),
		]);


	}

	public function actionFinshared()
	{
		//$searchModel = new FinsharedSearch();
		$searchModel = new FinancesSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		if (!Yii::$app->session->get('session_using')) {
			$settingsAdminModel = Settingsadmin::find()->one();
			$settingsAdmin = $settingsAdminModel ? $settingsAdminModel->settings : false ;

			return $this->render('myfinances', [
				'motionTypes' => ArrayHelper::map(Motions::find()->orderBy('id')->asArray()->all(), 'type', 'name'),
				'categroups' => ArrayHelper::map(Categroups::find()->where(['deleted' => false])->orderBy('cgroupname')->asArray()->all(), 'id', 'cgroupname'),
				'categories' => Categories::findCategories(),
				'users' => ArrayHelper::map(User::find()->orderBy('realname')->asArray()->all(), 'id', 'realname'),
				'walletGroups' => ArrayHelper::map(Walletgroups::find()->where(['deleted' => false])->orderBy('name')->asArray()->all(), 'id', 'name'),
				'wallets' => ArrayHelper::map(Wallets::find()->where(['deleted' => false])->orderBy('name')->asArray()->all(), 'id', 'name'),
				'currencies' => Currencies::findCurrencies(),

				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'my_balance' => Finances::getMyBalance(),
				'our_balance' => Finances::getOurBalance(),
				'my_balance2' => Finances::allBalances(Yii::$app->user->identity->id),
				'our_balance2' => Finances::allBalances(User::getAllUserids()),
				'settingsAdmin' => $settingsAdmin,
				'tags' => $this->getAllTags(true),
			]);
		}

		return $this->render('myfinances_ses', [
				'motionTypes' => ArrayHelper::map(Motions::find()->orderBy('id')->asArray()->all(), 'type', 'name'),
				'categroups' => ArrayHelper::map(Categroups::find()->where(['deleted' => false])->orderBy('cgroupname')->asArray()->all(), 'id', 'cgroupname'),
				'categories' => Categories::findCategories(),
				'users' => ArrayHelper::map(User::find()->orderBy('realname')->asArray()->all(), 'id', 'realname'),
				'walletGroups' => ArrayHelper::map(Walletgroups::find()->where(['deleted' => false])->orderBy('name')->asArray()->all(), 'id', 'name'),
				'wallets' => ArrayHelper::map(Wallets::find()->where(['user_id'=>Yii::$app->user->identity->id])->andwhere(['deleted' => false])->orderBy('name')->asArray()->all(), 'id', 'name'),
				'currencies' => Currencies::findCurrencies(),

			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'my_balance' => Finances::getMyBalance(),
			'our_balance' => Finances::getOurBalance(),
			'my_balance2' => Finances::allBalances(Yii::$app->user->identity->id),
			'our_balance2' => Finances::allBalances(User::getAllUserids()),
			'tags' => $this->getAllTags(true),
		]);
	}




	/**
	 * Displays a single Finances model.
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}


	/**
	 * Adds income or outcome
	 * @param integer $motion_type
	 *
	 * @return mixed
	 */

	public function actionAddrecord($motion_type = 0)
	{
		$newRecord = new Finances();
		$newRecord->user_id = Yii::$app->user->identity->id;
		$newRecord->date = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd');
		$newRecord->motion_id = $motion_type;

		if ($newRecord->load(Yii::$app->request->post()) && $newRecord->validate()) {
			//$newRecord->tagValues = implode(",",$newRecord->finTags);
			//$newRecord->tagValues = $newRecord->finTags;

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
				'tags' => $this->getAllTags(true),
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

		//this redord's tags
		$recordsWithTags = Finances::find()->where(['id'=>$id])->with('tags')->all();
		$tagValues=[];
		foreach ($recordsWithTags as $recortg) {
			if ($recortg->tags) $tagValues = array_merge($tagValues, $recortg->getTagValues(true));
		}
		//$record->finTags = array_combine(array_combine($tagValues, $tagValues), Finances::getAllTags());
		//$record->tagValues = $tagValues;
		$record->finTags = array_combine($tagValues,$tagValues);

		if ($record->load(Yii::$app->request->post()) && $record->validate()) {

			//for tagging
			$record->tagValues = $record->finTags ? implode(",",$record->finTags) : false;

			$date = new \DateTime('now'); //for timestamps
			$record->edit_time = $date->getTimestamp();

			$this->calcNewRecord($record);
			$nextRecord = $this->getNextRecord($record);
			$this->calcRemainingRecords($nextRecord);

		if ($record->related_record) {

			$record_related = $this->findModel($record->related_record);
			$record_related->money = $record->money;
			$record_related->edit_time = $record->edit_time;
			$this->calcNewRecord($record_related);
			$nextRecord = $this->getNextRecord($record_related);
			$this->calcRemainingRecords($nextRecord);

		}

			return $this->goBack();

		} else {

			return $this->render('update', [
				'categories' => Categories::findCategories(),
				'currencies' => Currencies::findCurrencies(),
				'motion_types' => Motions::findMotions(),
				'my_wallet' => Wallets::findWallet(Yii::$app->user->identity->id),
				'model' => $record,
				'tags' => $this->getAllTags(true),
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
		if ($nextRecord) $this->calcNewRecord($nextRecord);
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
		$model->date = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd');

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
		$model->date = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd');

		//if ($model->load(Yii::$app->request->post()) && $model->validate()) {
		if ($model->load(Yii::$app->request->post()) ) {

			$model_from = new Finances();
			$model_from->motion_id = 8;

			$model_to = new Finances();
			$model_to->motion_id = 9;

			$model_from->user_id = $model->user_id;
			$model_to->user_id = $model->user_id;

			$model_from->money = $model->money;
			$model_to->money = ($model->action == 1) ? $model->money / $model->coefficient : $model->money * $model->coefficient;

			$model_to->money = round($model_to->money, 0, PHP_ROUND_HALF_DOWN);

			$model_from->currency_id = $model->old_currency_id;
			$model_to->currency_id = $model->new_currency_id;

			$model_from->tagValues = $model->tagValues;
			$model_to->tagValues = $model->tagValues;

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
			'tags' => $this->getAllTags(true),
		]);

	}

	public function actionUtransfer()	//передача денег в кошелёк другому пользователю
	{
		$model = new Allocation();
		$model->date = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd');

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


	/**
	 * Deletes an existing Finances model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionApprove($id)
	{
		$record = $this->findModel($id);

		$record->approve = $record->approve ? '' : Yii::$app->user->identity->id;

		if ($record->related_record) {
			$related_record = $this->findModel($record->related_record);
			$related_record->approve = $record->approve;
			$related_record->save();
		}

		$record->save();

			return $this->goBack();

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

	private function getAllTags($combine=false)
	{
		$allTags = array_merge(Plans::getAllTags(), Finances::getAllTags());
		if ($combine) $allTags = array_combine($allTags, $allTags);
//print_r($allTags);exit;
			return $allTags;
	}

	public function actionTesting()
	{



		return $this->render('testing', [

			//'settingsAdmin' => $settingsAdmin->name_id,

			]
			);
	}

}
