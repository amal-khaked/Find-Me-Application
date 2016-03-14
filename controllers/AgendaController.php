<?php

namespace app\controllers;

use Yii;
use app\models\Agenda;
use app\models\AgendaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AgendaController implements the CRUD actions for Agenda model.
 */
class AgendaController extends Controller {
	public function behaviors() {
		return [ 
				'verbs' => [ 
						'class' => VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post' 
								] 
						] 
				] 
		];
	}
	
	/**
	 * Lists all Agenda models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new AgendaSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
		
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Displays a single Agenda model.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionView($id) {
		return $this->render ( 'view', [ 
				'model' => $this->findModel ( $id ) 
		] );
	}
	
	/**
	 * Creates a new Agenda model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Agenda ();
		
		if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->agendaID 
			] );
		} else {
			return $this->render ( 'create', [ 
					'model' => $model 
			] );
		}
	}
	
	/**
	 * Updates an existing Agenda model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel ( $id );
		
		if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->agendaID 
			] );
		} else {
			return $this->render ( 'update', [ 
					'model' => $model 
			] );
		}
	}
	
	/**
	 * Deletes an existing Agenda model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel ( $id )->delete ();
		
		return $this->redirect ( [ 
				'index' 
		] );
	}
	
	/**
	 * Finds the Agenda model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id        	
	 * @return Agenda the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Agenda::findOne ( $id )) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
	public function actionSaveAgenda() {
		$model = new Agenda ();
		$model->owner = $_GET ['owner'];
		$model->lastUpdate = date("Y/m/d");
		$data = $_GET ['data'];
		$status = $model->saveAgenda ( $data );
		$value = array (
				'status' => $status 
		);
		echo json_encode ( $value );
	}
	public function actionUpdateAgenda() {
		$model = new Agenda ();
		$model->agendaID = $_GET ['agendaID'];
		$agendaDate = $_GET ['agendaDate'];
		$model->lastUpdate = date("Y/m/d");
		$data = $_GET ['data'];
		$status = $model->updateAgenda ( $data );
		$value = array (
				'status' => $status 
		);
		echo json_encode ( $value );
	}
	public function actionException() {
		$model = new Agenda ();
		$model->agendaID = $_GET ['agendaID'];
		$model->lastUpdate = $_GET ['lastUpdate'];
		$data = $_GET ['data'];
		$status = $model->updateAgenda ( $data );
		$value = array (
				'status' => $status
		);
		echo json_encode ( $value );
	}
	//&owner=ahmed@fci
	public function actionShowAgenda() {
		$model = new Agenda ();
		$model->agendaID = $_GET ['agendaID'];
		$model->lastUpdate = $_GET ['date'];
		$status = $model->showAgenda();
		$value = array (
				'status' => $status,	
		);
		echo json_encode ( $value );
	}
}
