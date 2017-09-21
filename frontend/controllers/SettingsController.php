<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\Settings;
use frontend\models\Settingsuser;
use frontend\models\Settingsadmin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
/**
 * Site controller
 */
class SettingsController extends LoginController
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout', 'signup'],
				'rules' => [
					[
						'actions' => ['signup'],
						'allow' => true,
						'roles' => ['?'],
					],
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],

		];
	}

	public function beforeAction($action)
	{
		if (in_array(Yii::$app->controller->action->id, ['settingsuser', 'settingsadmin']))

			switch (Yii::$app->controller->action->id) {
				case 'settingsuser':
					Yii::$app->getUser()->setReturnUrl('/finances/myfinances');
					break;

				case 'settingsadmin':
					Yii::$app->getUser()->setReturnUrl('/finances/finshared');
					break;
			}

		return parent::beforeAction($action); //проверка на залогиненность
	}

	/**
	 * @inheritdoc
	 */

	private function settingsSerialize($model, $settings)
	{
					$model->settings = [
						'name_id' => $settings->name_id,
						'name_money' => $settings->name_money,
						'name_motion_id' => $settings->name_motion_id,
						'name_category_id' => $settings->name_category_id,
						'name_user_id' => $settings->name_user_id,
						'name_categroup_id' => $settings->name_categroup_id,
						'name_tags' => $settings->name_tags,
						'name_walletgroup_id' => $settings->name_walletgroup_id,
						'name_wallet_id' => $settings->name_wallet_id,
						'name_currency_id' => $settings->name_currency_id,
						'name_date' => $settings->name_date,
						'name_comment' => $settings->name_comment,
						'name_my_old_wallet_balance' => $settings->name_my_old_wallet_balance,
						'name_my_old_wgsumma_balance' => $settings->name_my_old_wgsumma_balance,
						'name_my_old_summa_balance' => $settings->name_my_old_summa_balance,
						'name_my_new_wallet_balance' => $settings->name_my_new_wallet_balance,
						'name_my_new_wgsumma_balance' => $settings->name_my_new_wgsumma_balance,
						'name_my_new_summa_balance' => $settings->name_my_new_summa_balance,
						'name_our_old_wgsumma_balance' => $settings->name_our_old_wgsumma_balance,
						'name_our_old_summa_balance' => $settings->name_our_old_summa_balance,
						'name_our_new_wgsumma_balance' => $settings->name_our_new_wgsumma_balance,
						'name_our_new_summa_balance' => $settings->name_our_new_summa_balance,
						'name_related_record' => $settings->name_related_record,
						'name_approve' => $settings->name_approve,
						'name_timestamp' => $settings->name_timestamp,
						'id' => $settings->id,
						'money' => $settings->money,
						'motion_id' => $settings->motion_id,
						'categroup_id' => $settings->categroup_id,
						'category_id' => $settings->category_id,
						'user_id' => $settings->user_id,
						'tags' => $settings->tags,
						'walletgroup_id' => $settings->walletgroup_id,
						'wallet_id' => $settings->wallet_id,
						'currency_id' => $settings->currency_id,
						'date' => $settings->date,
						'comment' => $settings->comment,
						'my_old_wallet_balance' => $settings->my_old_wallet_balance,
						'my_old_wgsumma_balance' => $settings->my_old_wgsumma_balance,
						'my_old_summa_balance' => $settings->my_old_summa_balance,
						'my_new_wallet_balance' => $settings->my_new_wallet_balance,
						'my_new_wgsumma_balance' => $settings->my_new_wgsumma_balance,
						'my_new_summa_balance' => $settings->my_new_summa_balance,
						'our_old_wgsumma_balance' => $settings->our_old_wgsumma_balance,
						'our_old_summa_balance' => $settings->our_old_summa_balance,
						'our_new_wgsumma_balance' => $settings->our_new_wgsumma_balance,
						'our_new_summa_balance' => $settings->our_new_summa_balance,
						'related_record' => $settings->related_record,
						'approve' => $settings->approve,
						'timestamp' => $settings->timestamp,
						'color_row1' => $settings->color_row1,
						'color_row2' => $settings->color_row2,
						'color_select' => $settings->color_select,
						'color_approve' => $settings->color_approve,
						'color_incomes' => $settings->color_incomes,
						'color_expenses' => $settings->color_expenses,
					];
	}

	private function getTimeZoneList()
	{
		$timezone_identifiers = \DateTimeZone::listIdentifiers();
		for ($i=0; $i < count($timezone_identifiers); $i++) {
			$timezoneListing[]=['id' => $timezone_identifiers[$i], 'name' => $timezone_identifiers[$i]];
		}

		return ArrayHelper::map($timezoneListing, 'id', 'name');
	}


	public function actionSettings()
	{
		return $this->render('settings');
	}

	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->render('index');
		//return $this->redirect('/site/login',302);
	}


	public function actionSettingsuser()
	{
		$model = Settingsuser::findOne(['user_id' => Yii::$app->user->identity->id]);
		if (!$model) {$model = new Settingsuser(); $model->user_id = Yii::$app->user->identity->id;}

		$settings = new Settings();
		$settings->attributes = $model ? $model->settings : false ;

		$settings->timezone = $model->timezone;

		if ($settings->load(Yii::$app->request->post())) {
			if ($settings->validate()) {
					$this->settingsSerialize($model, $settings);
					$model->timezone = $settings->timezone;
					$model->save();
					if (isset($model->timezone) && ($model->timezone !=0)) Yii::$app->timezone = $model->timezone;
					Yii::$app->session->remove('settingsUser');
					$settingsUser = Settingsuser::findOne(['user_id' => Yii::$app->user->identity->id])->settings;
					if ($settingsUser) Yii::$app->session->set('settingsUser', $settingsUser);
				return $this->goBack();
			}
		}

		return $this->render('settingsuser', [
			'settings' => $settings,
			'timezoneList' => $this->getTimeZoneList(),
		]);
	}

	public function actionSettingsadmin()
	{
		$model = Settingsadmin::find()->One();
		$settings = new Settings();
		$settings->attributes = $model->settings;
		$settings->registration = $model->registration;
		$settings->timezone = $model->timezone;
		$settings->sessionUsing = $model->session_using;

		if ($settings->load(Yii::$app->request->post())) {
			if ($settings->validate()) {
					$this->settingsSerialize($model, $settings);
					$model->registration = $settings->registration;
					$model->timezone = $settings->timezone;
					$model->session_using = $settings->sessionUsing;
					$model->save();
					Yii::$app->timezone = $model->timezone;
					Yii::$app->session->remove('settingsAdmin');
					$settingsAdmin = Settingsadmin::find()->One()->settings;
					if ($settingsAdmin) Yii::$app->session->set('settingsAdmin', $settingsAdmin);

				return $this->goBack();
			}
		}

		return $this->render('settingsadmin', [
			'settings' => $settings,
			'timezoneList' => $this->getTimeZoneList(),

		]);
	}




	/**
	 * Displays contact page.
	 *
	 * @return mixed
	 */
	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
				Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
			} else {
				Yii::$app->session->setFlash('error', 'There was an error sending email.');
			}

			return $this->refresh();
		} else {
			return $this->render('contact', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays about page.
	 *
	 * @return mixed
	 */
	public function actionAbout()
	{
		return $this->render('about');
	}

	/**
	 * Signs user up.
	 *
	 * @return mixed
	 */
	public function actionSignup()
	{
		$model = new SignupForm();
		if ($model->load(Yii::$app->request->post())) {
			if ($user = $model->signup()) {
				if (Yii::$app->getUser()->login($user)) {
					return $this->goHome();
				}
			}
		}

		return $this->render('signup', [
			'model' => $model,
		]);
	}

	/**
	 * Requests password reset.
	 *
	 * @return mixed
	 */
	public function actionRequestPasswordReset()
	{
		$model = new PasswordResetRequestForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail()) {
				Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

				return $this->goHome();
			} else {
				Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
			}
		}

		return $this->render('requestPasswordResetToken', [
			'model' => $model,
		]);
	}

	/**
	 * Resets password.
	 *
	 * @param string $token
	 * @return mixed
	 * @throws BadRequestHttpException
	 */
	public function actionResetPassword($token)
	{
		try {
			$model = new ResetPasswordForm($token);
		} catch (InvalidParamException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			Yii::$app->session->setFlash('success', 'New password was saved.');

			return $this->goHome();
		}

		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}
}
