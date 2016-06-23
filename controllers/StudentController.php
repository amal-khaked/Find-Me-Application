<?php

namespace app\controllers;

use Yii;
use app\models\Student;
use app\models\StudentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StudentController implements the CRUD actions for Student model.
 */
class StudentController extends Controller {
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
	 * Lists all Student models.
	 * 
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new StudentSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
		
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Displays a single Student model.
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
	 * Creates a new Student model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Student ();
		
		if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->id 
			] );
		} else {
			return $this->render ( 'create', [ 
					'model' => $model 
			] );
		}
	}
	
	/**
	 * Updates an existing Student model.
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
					'id' => $model->id 
			] );
		} else {
			return $this->render ( 'update', [ 
					'model' => $model 
			] );
		}
	}
	
	/**
	 * Deletes an existing Student model.
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
	 * Finds the Student model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * 
	 * @param integer $id        	
	 * @return Student the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Student::findOne ( $id )) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}

	public function actionLoginstudent() {
		$email = $_POST ['email'];
		$pass = $_POST ['pass'];
		$status=array();
		//echo $email . " " . $pass;
		$model = Student::find()->where(['email' => $email , 'password' => $pass])->one();
		if($model==null)
		{
			$status["status"]="faild";
		}
		else {
			$status["status"]="ok";
			$status["name"] = $model->name;
				
		$status["email"] = $model->email;
		$status["id"] = $model->id;
		
		
		}
		return json_encode($status);
	}
	public function actionSignupstudent() {
			$name = $_POST['name'];
		
			$id = $_POST['id'];
		
			$email = $_POST['email'];
	
			$pass = $_POST['pass'];
		
		$model = new Student ;
		$model->name = $name;
		$model->id = $id;
		$model->email = $email;
		$model->password = $pass;
		$status = array();
		if($model->save()){
			$status["status"] = "ok";
			$status["name"]=$name;
			$status["email"]=$email;
			$status["id"]=$id;
			
		}
		else 
			$status["status"] = "failed";
		return json_encode($status);
		
	}
	
	
}
