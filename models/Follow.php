<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "follow".
 *
 * @property integer $followID
 * @property string $staffID
 * @property string $studentID
 *
 * @property Staff $staff
 * @property Student $student
 */
class Follow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'follow';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staffID', 'studentID'], 'required'],
            [['staffID', 'studentID'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'followID' => 'Follow ID',
            'staffID' => 'Staff ID',
            'studentID' => 'Student ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasOne(Staff::className(), ['formalemail' => 'staffID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['id' => 'studentID']);
    }
    public function getfollowing( $staffID){
    
    	$status =array();
    	$model = Follow::find()->where(['staffID' => $staffID])->all();
    	if($model == NULL)
    	{
    		$status["status"] = "null" ;
    	}
    	else
    
    	{
    
    		 
    		for($i=0; $i<sizeof($model) ; $i++)
    		{
    			$status["status"]  = "ok" ;
    			$status["studentID"][$i] = $model[$i] -> studentID;
    
    
    		}
    	}
    
    	return ($status);
    	//$result['studID'] = $model -> staffID ;
    	//$result['staID'] = $model -> staffID ;
    
    	// echo json_encode($result);
    
    
    
    }
}
