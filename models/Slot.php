<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "slot".
 *
 * @property integer $slotID
 * @property integer $agendaID
 * @property string $content
 * @property string $type
 * @property integer $slotnum
 * @property integer $numberOfBookers
 * @property integer $bookCount
 *
 * @property Book[] $books
 * @property Agenda $agenda
 */
class Slot extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slot';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agendaID', 'content', 'type', 'slotnum', 'numberOfBookers', 'bookCount'], 'required'],
            [['agendaID', 'slotnum', 'numberOfBookers', 'bookCount'], 'integer'],
            [['content', 'type'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'slotID' => 'Slot ID',
            'agendaID' => 'Agenda ID',
            'content' => 'Content',
            'type' => 'Type',
            'slotnum' => 'Slotnum',
            'numberOfBookers' => 'Number Of Bookers',
            'bookCount' => 'Book Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Book::className(), ['slotID' => 'slotID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgenda()
    {
        return $this->hasOne(Agenda::className(), ['agendaID' => 'agendaID']);
    }
    public function saveSlot( $agendaID, $content, $type, $slotnum, $numberOfBookers) {
    	$this->agendaID = $agendaID;
    	$this->content = $content;
    	$this->type = $type;
    	$this->slotnum = $slotnum;
    	$this->numberOfBookers = $numberOfBookers;
    	$this->bookCount = 0;
    
    	try {
    		Slot::save ( true );
    	} catch ( Exception $e ) {
    		return false;
    	}
    
    	return true;
    }
    public function getIDs ($agendaID){
    	return Slot::find ()->where ( [
    			'agendaID' => $agendaID
    	] )->asArray ()->orderBy ( [
    			'slotnum' => SORT_ASC
    	] )->all ();
    
    }
    public function updateSlot($content , $slotbuffer , $slotID){

    	$updated = Slot::updateAll (array ( 
				'content' => $content,
    			'numberOfBookers' => $slotbuffer
    			
		), [ 
				'slotID' => $slotID
		] );
    
    	return $updated;
    	
    }
}
