<?php

namespace app\controllers;

use Yii;
use app\models\Book;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller {
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
	 * Lists all Book models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => Book::find () 
		] );
		
		return $this->render ( 'index', [ 
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Displays a single Book model.
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
	 * Creates a new Book model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Book ();
		
		if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->bookID 
			] );
		} else {
			return $this->render ( 'create', [ 
					'model' => $model 
			] );
		}
	}
	
	/**
	 * Updates an existing Book model.
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
					'id' => $model->bookID 
			] );
		} else {
			return $this->render ( 'update', [ 
					'model' => $model 
			] );
		}
	}
	
	/**
	 * Deletes an existing Book model.
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
	 * Finds the Book model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id        	
	 * @return Book the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Book::findOne ( $id )) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
	public function actionCreateBook() {
		$model = new Book ();
		$model->slotID = $_POST ['slotID'];
		$model->date = $_POST ['Date'];
		$model->content = $_POST ['content'];
		$model->studentID = $_POST ['booker'];
		$status = array ();
		
		$exist = Book::find ()->select ( 'studentID' )->where ( [ 
				'slotID' => $model->slotID,
				'date' => $model->date,
				'studentID' => $model->studentID 
		] )->all ();
		
		if (! $exist) {
			$model->save ();
			$status ["status"] = "ok";
		} else {
			$status ["status"] = "false";
		}
		
		echo json_encode ( $status );
	}
	public function actionCancelBook() {
		$model = new Book ();
		$model->slotID = $_POST ['slotID'];
		$model->date = $_POST ['Date'];
		$model->studentID = $_POST ['booker'];
		$status = array ();
		
		$exist = Book::deleteAll ( [ 
				'slotID' => $model->slotID,
				'date' => $model->date,
				'studentID' => $model->studentID 
		] );
		
		$status ["status"] = "deleted";
		
		echo json_encode ( $status );
	}
	public function actionMyBooks() {
		$model = new Book ();
		$model->studentID = $_POST ['booker'];
		$date = date ( "Y/m/d" );
		
		$exist = Book::find ()->select ( array (
				'slotID',
				'date',
				'content' 
		) )->where ( [ 
				'studentID' => $model->studentID 
		] )->andWhere ( [ 
				'>=',
				'date',
				$date 
		] )->asArray ()->all ();
		
		echo json_encode ( $exist );
	}
	public function actionGetBookContent() {
		$model = new Book ();
		$model->slotID = $_POST ['slotID'];
		$model->date = $_POST ['date'];
		
		$exist = Book::find ()->select ( 'content' )->where ( [ 
				'slotID' => $model->slotID,
				'date' => $model->date 
		] )->asArray ()->all ();
		
		echo json_encode ( $exist );
	}
}
