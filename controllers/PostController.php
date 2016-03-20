<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller {
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
	 * Lists all Post models.
	 * 
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new PostSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
		
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Displays a single Post model.
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
	 * Creates a new Post model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Post ();
		
		if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->postID 
			] );
		} else {
			return $this->render ( 'create', [ 
					'model' => $model 
			] );
		}
	}
	
	/**
	 * Updates an existing Post model.
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
					'id' => $model->postID 
			] );
		} else {
			return $this->render ( 'update', [ 
					'model' => $model 
			] );
		}
	}
	
	/**
	 * Deletes an existing Post model.
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
	 * Finds the Post model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * 
	 * @param integer $id        	
	 * @return Post the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Post::findOne ( $id )) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
	public function actionGetmyposts() {
		$id = $_GET ['id'];
		$model = array ();
		$model = Post::findAll ( ([ 
				'owner' => $id 
		]) );
		
		$status = array ();
		if ($model == Null) {
			$status ["status"] = "faild";
		} else {
			for($i = 0; $i < sizeof ( $model ); $i ++) {
				$status ["content"] [$i] = $model [$i]->content;
				$status ["time"] [$i] = $model [$i]->time;
			}
		}
		return json_encode ( $status );
	}
	public function actionGetpost() {
		$id = $_GET ['id'];
		$follow = new Follow ();
		$follow->studentID = $id;
		$model = array ();
		$model = Follow::findAll ( ([ 
				'studentID' => $id 
		]) );
		$status = array ();
		if ($model == Null) {
			$status ["status"] = "faild";
		} else {
			for($j = 0; $j < sizeof ( $model ); $j ++) {
				$status ["follower"] [$j] = $model [$j]->staffID;
				// $staff_id=$model[$j]->staffID;
			}
		}
		$posts = array ();
		$statuss = array ();
		$i = 0;
		for($i = 0; $i < sizeof ( $model ); $i ++) {
			$staff_id = $model [$i]->staffID;
			
			$posts = Post::findAll ( ([ 
					'owner' => $staff_id 
			]) );
			if ($posts == Null) {
			} else {
				for($m = 0; $m < sizeof ( $posts ); $m ++) {
					$statuss ["content"] [$m] = $posts [$m]->content;
					$statuss ["owner"] [$m] = $posts [$m]->owner;
					$statuss ["time"] [$m] = $posts [$m]->time;
				}
			}
		}
		return json_encode ( $statuss );
	}
}
