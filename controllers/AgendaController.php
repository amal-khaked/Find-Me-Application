<?php

namespace app\controllers;

use Yii;
use app\models\Agenda;
use app\models\AgendaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Slot;

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
	public function actionCreateAgenda() {
		$model = new Agenda ();
		$model->owner = $_POST ['owner'];
		$model->lastUpdate = date ( "Y/m/d" );
		$content = json_decode ( $_POST ['data'] );
		$slotbuff = json_decode ( $_POST ['slotbuff'] );
		$status = $model->saveAgenda ( $content, $slotbuff );
		$value = array (
				'status' => $status 
		);
		echo json_encode ( $value );
	}
	/*
	 * public function actionUpdateAgenda() {
	 * $model = new Agenda ();
	 * $model->agendaID = $_GET ['agendaID'];
	 * $model->lastUpdate = date("Y/m/d");
	 * $data = $_GET ['data'];
	 * $slotbuff = $_GET ['slotbuff'];
	 * $slotnum = $_GET ['slotnum'];
	 * $status = $model->updateAgenda ( $data , $slotbuff, $slotnum );
	 * $value = array (
	 * 'status' => $status
	 * );
	 * echo json_encode ( $value );
	 * }
	 */
	public function actionUpdateExceptionAgenda() {
		$model = new Agenda ();
		$model->agendaID = $_POST ['agendaID'];
		$model->lastUpdate = date ( "Y/m/d" );
		$data = json_decode($_POST ['data']);
		$slotbuff = json_decode($_POST ['slotbuff']);
		$slotnum =json_decode ($_POST ['slotnum']);
		$status = $model->updateExceptionAgenda ( $data, $slotbuff, $slotnum );
		$value = array (
				'status' => $status 
		);
		echo json_encode ( $value );
	}
	public function actionUpdateAgenda() {
		$model = new Agenda ();
		$model->agendaID = $_POST ['agendaID'];
		$model->lastUpdate = date ( "Y/m/d" );
		$data = json_decode ( $_POST ['data'] );
		$slotbuff = json_decode ( $_POST ['slotbuff'] );
		$slotnum = json_decode ( $_POST ['slotnum'] );
		$status = $model->updateAgenda ( $data, $slotbuff, $slotnum );
		$value = array (
				'status' => $status 
		);
		// 'status' => "yes baby yes"
		
		echo json_encode ( $value );
	}
	public function actionCreateException() {
		$model = new agenda ();
		$model->owner = $_POST ['owner'];
		$model->lastUpdate = $_POST ['lastUpdate'];
		$content = json_decode ( $_POST ['data'] );
		$slotbuff = json_decode ( $_POST ['slotbuff'] );
		$slotnum = json_decode ( $_POST ['slotnum'] );
		$status = $model->saveException ( $content, $slotbuff, $slotnum );
		$value = array (
				'status' => $status 
		);
		// 'status' => $model->lastUpdate
		
		echo json_encode ( $value );
	}
	/*
	 * public function actionShowAgenda() {
	 * $model = new Agenda ();
	 * $model->owner = $_POST['owner'];
	 * $model->lastUpdate = date("Y/m/d");
	 * $status = $model->showAgenda();
	 * echo json_encode ( $status );
	 * }
	 */
	public function actionShowAgenda() {
		$model = new Agenda ();
		$model->agendaID = $_POST ['agendaID'];
		$status = $model->showAgenda ();
		echo json_encode ( $status );
	}
	public function actionGetAgendaInfo() {
		$owner = $_POST ['owner'];
		$date = $_POST ['date'];
		$exist = Agenda::find ()->where ( [ 
				'owner' => $owner,
				'lastUpdate' => $date ,
				'type' => 'temp'
		] )->asArray ()->one ();
		
		if ($exist == null) {
			$exist = Agenda::find ()->where ( [ 
					'owner' => $owner,
					'type' => 'perm' 
			] )->asArray ()->one ();
			if ($exist == null) {
				$exist = array (
						'Status' => 'notAgenda' 
				);
			}
		}
		echo json_encode ( $exist );
	}
	public function actionGetExceptions() {
		$owner = $_POST ['owner'];
		$exist = Agenda::find ()->select ( array (
				'lastUpdate',
				'agendaID' 
		) )->where ( [ 
				'owner' => $owner,
				'type' => 'temp' 
		] )->asArray ()->all ();
		
		echo json_encode ( $exist );
	}
	public function  actionIsException(){
		$owner = $_POST ['owner'];
	    $lastUpdate = $_POST ['date'];
	    
		$exist = Agenda::find ()->select (
				'lastUpdate'
	 )->where ( [
				'owner' => $owner,
				'type' => 'temp',
				'lastUpdate' => $lastUpdate
		] ) ->asArray ()->all ();
	
		echo json_encode ( $exist );
		
	}
}
