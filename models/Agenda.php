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
			if (sizeof ( $data ) < 36) {
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
			$slotnum = 0;
			
			for($index = 0; $index < sizeof ( $data ); $index ++) {
				if ($index % 6 == 0) {
					$day = new Day ();
					$day->SaveDay ( $agendaID, $weekDays [$weekIndex] );
					$dayID = Yii::$app->db->getLastInsertID ();
					$weekIndex ++;
				}
				$slot = new Slot ();
				$slot->saveSlot ( $dayID, $agendaID, $data [$index], 'perm', $this->lastUpdate, $index );
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
		$exist = Agenda::find ()->where ( [ 
				'agendaID' => $this->agendaID 
		] )->one ();
		if (! $exist) {
			return 'not found';
		}
		$agenda = Slot::find ()->where ( [ 
				'agendaID' => $this->agendaID 
		] )->asArray ()->orderBy ( [ 
				'slotnum' => SORT_ASC 
		] )->all ();
		$agendforShow = array ();
		$perm;
		$agendaForShowCounter = 0;
		for($i = 0; $i < sizeof ( $agenda ); $i ++) {
			$inserted = false;
			if ($agenda [$i] ['type'] == 'temp' && $agenda [$i] ['date'] == $this->lastUpdate) {
				$agendforShow [$agendaForShowCounter] = $agenda [$i];
				$agendaForShowCounter ++;
				$inserted = true;
			}
			if ($agenda [$i] ['type'] == 'perm') {
				$perm = $agenda [$i];
			} else {
				$slotnum = $agenda [$i] ['slotnum'];
				
				for($index = $i + 1;; $index ++) {
					if ($index == sizeof ( $agenda )) {
						break;
					}
					
					if ($slotnum == $agenda [$index] ['slotnum']) {
						$i++;
						if ($agenda [$index] ['type'] == 'perm') {
							$perm = $agenda [$index];
						}
						if ($agenda [$index] ['type'] == 'temp' && $agenda [$index] ['date'] == $this->lastUpdate) {
							
							$agendforShow [$agendaForShowCounter] = $agenda [$i];
							$agendaForShowCounter ++;
							$inserted = true;
							break;
						}
					} else {
						break;
					}
				}
			}
			if ($inserted == false) {
				
				$agendforShow [$agendaForShowCounter] = $perm;
				$agendaForShowCounter ++;
			}
		}
		
		return $agendforShow ;
	}
}
