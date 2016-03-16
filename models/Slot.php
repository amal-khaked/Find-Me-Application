<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "slot".
 *
 * @property integer $slotID
 * @property integer $dayID
 * @property integer $agendaID
 * @property string $content
 * @property string $type
 * @property string $date
 * @property integer $slotnum
 * @property integer $numberOfBookers
 * @property integer $bookCount
 *
 * @property Book[] $books
 * @property Day $day
 * @property Agenda $agenda
 */
class Slot extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'slot';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'dayID',
								'agendaID',
								'content',
								'type',
								'date',
								'slotnum',
								'numberOfBookers',
								'bookCount' 
						],
						'required' 
				],
				[ 
						[ 
								'dayID',
								'agendaID',
								'slotnum',
								'numberOfBookers',
								'bookCount' 
						],
						'integer' 
				],
				[ 
						[ 
								'date' 
						],
						'safe' 
				],
				[ 
						[ 
								'content',
								'type' 
						],
						'string',
						'max' => 50 
				] 
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'slotID' => 'Slot ID',
				'dayID' => 'Day ID',
				'agendaID' => 'Agenda ID',
				'content' => 'Content',
				'type' => 'Type',
				'date' => 'Date',
				'slotnum' => 'Slotnum',
				'numberOfBookers' => 'Number Of Bookers',
				'bookCount' => 'Book Count' 
		];
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getBooks() {
		return $this->hasMany ( Book::className (), [ 
				'slotID' => 'slotID' 
		] );
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getDay() {
		return $this->hasOne ( Day::className (), [ 
				'dayID' => 'dayID' 
		] );
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getAgenda() {
		return $this->hasOne ( Agenda::className (), [ 
				'agendaID' => 'agendaID' 
		] );
	}
	public function saveSlot($dayID, $agendaID, $content, $type, $date, $slotnum, $numberOfBookers) {
		$this->dayID = $dayID;
		$this->agendaID = $agendaID;
		$this->content = $content;
		$this->type = $type;
		$this->date = $date;
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
	public function updateSlot($content , $slotbuffer , $slotnum , $slotID){
		Slot::updateAll ( [
				'content' => $content,
				'numberOfBookers' => $slotbuffer
		], [
				'slotnum' => $slotnum,
				'slotID' => $slotID
		] );
	}
}
