<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agenda".
 *
 * @property integer $agendaID
 * @property string $owner
 * @property string $lastUpdate
 *
 * @property Staff $owner0
 * @property Day[] $days
 * @property Slot[] $slots
 */
class Agenda extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'agenda';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'owner',
								'lastUpdate' 
						],
						'required' 
				],
				[ 
						[ 
								'owner',
								'lastUpdate' 
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
				'agendaID' => 'Agenda ID',
				'owner' => 'Owner',
				'lastUpdate' => 'Last Update' 
		];
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getOwner0() {
		return $this->hasOne ( Staff::className (), [ 
				'formalemail' => 'owner' 
		] );
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getDays() {
		return $this->hasMany ( Day::className (), [ 
				'agendaID' => 'agendaID' 
		] );
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getSlots() {
		return $this->hasMany ( Slot::className (), [ 
				'agendaID' => 'agendaID' 
		] );
	}
	public function saveAgenda(array $data = array()) {
		try {
			// check 3 staff exist
			if (sizeof ( $data ) == 36) {
				return 'not complete';
			}
			Agenda::save ( true );
			$agendaID = Yii::$app->db->getLastInsertID ();
			
			$weekDays = array (
					'satrday',
					'sunday',
					'monday',
					'tuseday',
					'wensday',
					'tharsday' 
			);
			
			$weekIndex = 0;
			$dayID = 0;
			for($index = 0; $index < sizeof ( $data ); $index ++) {
				if ($index % 6 == 0) {
					$day = new Day ();
					$day->SaveDay ( $agendaID, $weekDays [$weekIndex] );
					$dayID = Yii::$app->db->getLastInsertID ();
					$weekIndex ++;
				}
				$slot = new Slot ();
				$slot->saveSlot ( $dayID, $agendaID, $data [$index], 'perm', $this->lastUpdate );
			}
		} catch ( Exception $e ) {
			return 'DB Error';
		}
		
		return 'inserted';
	}
	public function updateAgenda(array $data = array()) {
		if (sizeof ( $data ) < 36) {
			return 'not complete';
		}
		
		$exist = Agenda::find ()->where ( [ 
				'agendaID' => $this->agendaID 
		] )->one ();
		if (! $exist) {
			return 'not found';
		}
		
		$updated = Agenda::updateAll ( [ 
				'lastUpdate' => $this->lastUpdate 
		], [ 
				'agendaID' => $this->agendaID 
		] );
		
		
			try {
				$slotIDs = Slot::find ()->select ( 'slotID' )->where ( [ 
						'agendaID' => $this->agendaID 
				] )->asArray ()->all ();
				
				for($index = 0; $index < sizeof ( $data ); $index ++) {
					
					$du = Slot::updateAll ( [ 
							'content' => $data [$index] 
					], [ 
							'agendaID' => $this->agendaID,
							'slotID' => $slotIDs [$index] ['slotID'] 
					] );
				}
			} catch ( Exception $e ) {
				return 'DB Error';
			}
		
		return 'updated';
	}
	public function showAgenda() {
		$exist =Agenda::find ()->where ( [
				'agendaID' => $this->agendaID] )->one ();
		if (! $exist) {
			return 'not found';
		}
		$agenda = Slot::find ()->select ( 'slotID' , 'content' )->where ( [
				'agendaID' => $this->agendaID
		] )->asArray ()->all ();
		
		
		echo 'no';
	}
}
