<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "agenda".
 *
 * @property integer $agendaID
 * @property string $owner
 * @property string $lastUpdate
 * @property string $type
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
								'lastUpdate',
								'type' 
						],
						'required' 
				],
				[ 
						[ 
								'lastUpdate' 
						],
						'safe' 
				],
				[ 
						[ 
								'owner' 
						],
						'string',
						'max' => 50 
				],
				[ 
						[ 
								'type' 
						],
						'string',
						'max' => 15 
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
				'lastUpdate' => 'Last Update',
				'type' => 'Type' 
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
	public function saveAgenda(array $data = array(), array $bookbuffer = array()) {
		try {
			// check 3 staff exist
			if (sizeof ( $data ) < 36) {
				return 'not complete';
			}
			$this->type = 'perm';
			Agenda::save ( true );
			$agendaID = Yii::$app->db->getLastInsertID ();
			
			$slotnum = 0;
			
			for($index = 0; $index < sizeof ( $data ); $index ++) {
				$slot = new Slot ();
				$slot->saveSlot ( 80, $agendaID, $data [$index], 'perm', $this->lastUpdate, $index, $bookbuffer [$index] );
			}
		} catch ( Exception $e ) {
			return 'DB Error';
		}
		
		return 'inserted';
	}
	public function saveException(array $data = array(), array $bookbuffer = array(), array $slotnum = array()) {
		$this->type = 'temp';
		
		$exist = Agenda::find ()->where ( [ 
				'agendaID' => $this->agendaID,
				'type' => 'temp' 
		] )->one ();
		if ($exist != null) {
			return 'exception already exist update it';
		}
		Agenda::save ( true );
		$agendaID = Yii::$app->db->getLastInsertID ();
		for($index = 0; $index < sizeof ( $data ); $index ++) {
			$slot = new Slot ();
			$slot->saveSlot ( 80, $agendaID, $data [$index], 'temp', $this->lastUpdate, $slotnum [$index], $bookbuffer [$index] );
		}
		
		return 'inserted';
	}
	public function updateAgenda(array $data = array(), array $bookbuffer = array(), array $slotnum = array()) {
		$model = new Slot ();
		
		$exist = Agenda::find ()->where ( [ 
				'agendaID' => $this->agendaID 
		] )->one ();
		
		$permAgendaID = Agenda::find ()->where ( [ 
				'owner' => $exist ['owner'],
				'type' => 'perm' 
		] )->one ();
		Agenda::updateAll ( [ 
				'lastUpdate' => $this->lastUpdate 
		], [ 
				'agendaID' => $permAgendaID ['agendaID'] 
		] );
		
		$tempSlotIDs = array ();
		if ($exist ['type'] == 'temp') {
			$tempSlotIDs = $model->getIDs ( $this->agendaID );
		}
		
		$permSlotIDs = $model->getIDs ( $permAgendaID ['agendaID'] );
		
		$permindex = 0;
		$tempindex = 0;
		$index = 0;
		
		while ( $permindex < sizeof ( $permSlotIDs ) && $tempindex < sizeof ( $tempSlotIDs ) && $index < sizeof ( $data ) ) {
			if ($tempSlotIDs [$tempindex] ['slotnum'] == $slotnum [$tempindex]) {
				$model->updateSlot ( $data [$index], $bookbuffer [$tempindex], $slotnum [$tempindex], $tempSlotIDs [$tempindex] ['slotID'] );
				$tempindex ++;
				$index ++;
			} elseif ($permSlotIDs [$permindex] ['slotnum'] == $slotnum [$tempindex]) {
				$model->updateSlot ( $data [$index], $permAgendaID, $permSlotIDs [$index] ['slotID'] );
				$permindex ++;
				$index ++;
			}
		}
		while ( $tempindex < sizeof ( $tempSlotIDs ) && $index < sizeof ( $data ) ) {
			if ($tempSlotIDs [$tempindex] ['slotnum'] == $slotnum [$tempindex]) {
				$model->updateSlot ( $data [$index], $bookbuffer [$tempindex], $slotnum [$tempindex], $tempSlotIDs [$tempindex] ['slotID'] );
				$tempindex ++;
				$index ++;
			}
		}
		
		while ( $permindex < sizeof ( $permSlotIDs ) && $index < sizeof ( $data ) ) {
			if ($permSlotIDs [$permindex] ['slotnum'] == $slotnum [$tempindex]) {
				$model->updateSlot ( $data [$index], $permAgendaID, $permSlotIDs [$index] ['slotID'] );
				$permindex ++;
				$index ++;
			}
		}
		return 'updated';
	}
	public function showAgenda() {
		$model = new Slot ();
		
		$exist = Agenda::find ()->where ( [ 
				'agendaID' => $this->agendaID 
		] )->one ();
		
		$permAgendaID = Agenda::find ()->where ( [ 
				'owner' => $exist ['owner'],
				'type' => 'perm' 
		] )->one ();
		
		$tempSlotIDs = array ();
		if ($exist ['type'] == 'temp') {
			$tempSlotIDs = $model->getIDs ( $this->agendaID );
		}
		
		$permSlotIDs = $model->getIDs ( $permAgendaID ['agendaID'] );
		
		$permindex = 0;
		$tempindex = 0;
		$agendaForShow = array ();
		$index = 0;
		
		while ( $permindex < sizeof ( $permSlotIDs ) && $tempindex < sizeof ( $tempSlotIDs ) ) {
			if ($tempSlotIDs [$tempindex] ['slotnum'] == $permSlotIDs [$permindex]) {
				$agendforShow [$index] = $tempSlotIDs [$tempindex];
				$tempindex ++;
				$index ++;
			} else {
				$agendforShow [$index] = $permSlotIDs [$permindex];
				$permindex ++;
				$index ++;
			}
		}
		while ( $tempindex < sizeof ( $tempSlotIDs ) ) {		
			$agendforShow [$index] = $tempSlotIDs [$tempindex];
			$tempindex ++;
			$index ++;
		}
		
		while ( $permindex < sizeof ( $permSlotIDs ) ) {
			$agendforShow [$index] = $permSlotIDs [$permindex];
			$permindex ++;
			$index ++;
		}
		return $agendforShow;
	}
}
