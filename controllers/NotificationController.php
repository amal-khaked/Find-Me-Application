<?php

namespace app\controllers;

use app\models\Follow;
use yii\rest\IndexAction;
use app\models\Notification;

class NotificationController extends \yii\web\Controller {
	public function actionIndex() {
		return $this->render ( 'index' );
	}
	public function actionSetnotification() {
		$sender = $_POST ['senderID'];
		$typeid = $_POST ['typeID'];
		$type = $_POST ['type'];
		$status = array ();
		
		if ($sender == NULL || $typeid == NULL || $type == NULL) // application/json
{
			$status ['status'] = "You Must Enter All Fieldes";
		} else {
			
			$followmodel = new Follow ();
			$recive = array ();
			$arr = array ();
			$recive = $followmodel->getfollowing ( $sender );
			
			$check = 0;
			for($i = 0; $i < sizeof ( $recive ); $i ++) {
				
				$notificationModel = new Notification ();
				$notificationModel->sender = $sender;
				$notificationModel->postID = $typeid;
				$notificationModel->type = $type;
				$notificationModel->seen = "0 ";
				$notificationModel->notified = $recive ["studentID"] [$i];
				
				if ($notificationModel->save ()) {
					$check = 1;
				} else {
					$check = - 1;
				}
			}
			
			if ($check == 1) {
				$status ["status"] = "ok";
			} else if ($check == - 1) {
				$status ["status"] = "faild";
			}
		}
		
		return json_encode ( $status );
	}
	public function actionGetpostnotification() {
		$studentID = $_POST ['studentID'];
		$model = array ();
		$value = "0";
		$model = Notification::find ()->where ( [ 
				'notified' => $studentID,
				'seen' => $value 
		] )->all ();
		
		// $model=Notification::findAll ((['notified'=>$studentID]) & (['seen' =>$value]) );
		
		$status = array ();
		if ($model == Null) {
			$status ["status"] = "null";
		} else {
			for($i = 0; $i < sizeof ( $model ); $i ++) {
				$status ["status"] = "ok";
				
				$status ["type"] [$i] = $model [$i]->type;
				$status ["postID"] [$i] = $model [$i]->postID;
				$status ["sender"] [$i] = $model [$i]->sender;
			}
		}
		return json_encode ( $status );
	}
	public function actionSendUpdateNotifi() {
		$sender = $_POST ['owner'];
		$typeid = $_POST ['agendaID'];
		$type = $_POST ['type'];
		
		$followmodel = new Follow ();
		$recive = array ();
		$recive = $followmodel->getfollowing ( $sender );
		
		for($i = 0; $i < sizeof ( $recive ); $i ++) {
			$notificationModel = new Notification ();
			$notificationModel->sender = $sender;
			$notificationModel->postID = $typeid;
			$notificationModel->type = $type;
			$notificationModel->seen = "0 ";
			$notificationModel->notified = $recive ["studentID"] [$i];
			
			$notificationModel->save ();
		}
		$value = array (
				'status' => "ok"
		);
	
		
		return json_encode ( $value );
	}
}
