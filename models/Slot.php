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
								'date' 
						],
						'required' 
				],
				[ 
						[ 
								'dayID',
								'agendaID' 
						],
						'integer' 
				],
				[ 
						[ 
								'content',
								'type',
								'date' 
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
				'date' => 'Date' 
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
	public function saveSlot($dayID, $agendaID, $content, $type, $date) {
		$this->dayID = $dayID;
		$this->agendaID = $agendaID;
		$this->content = $content;
		$this->type = $type;
		$this->date = $date;
		try {
			Slot::save ( true );
		} catch ( Exception $e ) {
			return false;
		}
		
		return true;
	}
	/*
	 * function __construct($dayID, $agendaID, $content, $type, $date) {
	 * $this->dayID = $dayID;
	 * $this->agendaID = $agendaID;
	 * $this->content = $content;
	 * $this->type = $type;
	 * $this->date = $date;
	 * }
	 */
}
