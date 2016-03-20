<?php

namespace app\controllers;

use Yii;
use app\models\Book;
use app\models\BookSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Slot;

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
		$searchModel = new BookSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
		
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
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
	
	public function actionBook() 
	{
		//handel if slotId not exist
		//handel student not exist
		$StudentID = $_GET ['StudentID'];
		$SlotID = $_GET ['SlotID'];
		$model = Slot::find()->where( [ 'slotID' => $SlotID])->one();
		$max = $model->numberOfBookers;
		$book = array ();
		$book = Book::find()->where( [ 'slotID' => $SlotID ] )->all();
		if($book==NULL)
		{
		
		}
		$check = FALSE;
		echo "hhhhhhhh";
		for($i = 0; $i < sizeof ($book ); $i++) 
		{
			if ($book [$i]->studentID == $StudentID) 
			{
				$check = TRUE;
				echo "noooooooooooo";
				break;
			}
				
		}
		$status = array ();
		
		if ($check == FALSE) 
		{
			$checkmax = $model->bookCount;
			if ($checkmax < $max)
			{
				echo "in max";
				$bookmodel = new Book;
				$bookmodel->studentID = $StudentID;
				$bookmodel->slotID = $SlotID;
				if ($bookmodel->save ())
				{
					$model->bookCount = $model->bookCount + 1;
					if ($model->save ()) 
					{
						$status ["status"] = "ok";
					}
				}
			}
			else 
			{
				$status["status"] = "Greater Than Max";
			}
		}
		else 
		{
			$status["status"] = "You are already booked";
		}
	echo	json_encode($status);
	}

}
