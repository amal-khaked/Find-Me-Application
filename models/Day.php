<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "day".
 *
 * @property integer $dayID
 * @property integer $agendaID
 * @property string $name
 *
 * @property Agenda $agenda
 * @property Slot[] $slots
 */
class Day extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'day';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agendaID', 'name'], 'required'],
            [['agendaID'], 'integer'],
            [['name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dayID' => 'Day ID',
            'agendaID' => 'Agenda ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgenda()
    {
        return $this->hasOne(Agenda::className(), ['agendaID' => 'agendaID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlots()
    {
        return $this->hasMany(Slot::className(), ['dayID' => 'dayID']);
    }
    public function SaveDay($agendaID , $name){
    	try {
    		$this->agendaID=$agendaID;
    		$this->name=$name;
    		
    		Day::save ( true );
    	} catch ( Exception $e ) {
    		return false;
    	}
    	
    	return true;
    }
}
