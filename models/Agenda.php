<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agenda".
 *
 * @property integer $agendaID
 * @property string $owner
 * @property string $lastUpdate
 * @property string $type
 *
 * @property Staff $owner0
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
	public function getSlots() {
		return $this->hasMany ( Slot::className (), [ 
				'agendaID' => 'agendaID' 
		] );
	}
	public function saveAgenda(array $data = array(), array $bookbuffer = array()) {
		try {
			$this->type = 'perm';
			Agenda::save ( true );
			$agendaID = Yii::$app->db->getLastInsertID ();
			
			$slotnum = 0;
			
			for($index = 0; $index < sizeof ( $data ); $index ++) {
				$slot = new Slot ();
				$slot->saveSlot ( $agendaID, $data [$index], 'perm', $index, $bookbuffer [$index] );
			}
		} catch ( Exception $e ) {
			return 'DB Error';
		}
		return $agendaID;
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
			$slot->saveSlot ( $agendaID, $data [$index], 'temp', $slotnum [$index], $bookbuffer [$index] );
		}
		
		return 'inserted';
	}
	public function updateExceptionAgenda(array $data = array(), array $bookbuffer = array(), array $slotnum = array()) {
		$model = new Slot ();
		$tempSlotIDs = array ();
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
		$tempSlotIDs = $model->getIDs ( $this->agendaID );
		$tempindex = 0;
		
		for($index = 0; $index < sizeof ( $data );) {
			if ($tempindex == sizeof ( $tempSlotIDs )) {
				break;
			}
			if ($tempSlotIDs [$tempindex] ['slotnum'] == $slotnum [$index]) {
				echo $model->updateSlot ( $data [$index], $bookbuffer [$index], $tempSlotIDs [$index] ['slotID'] );
				$tempindex ++;
				$index ++;
			} elseif ($tempSlotIDs [$tempindex] ['slotnum'] < $slotnum [$index]) {
				$tempindex ++;
			} else {
				$model->saveSlot ( 80, $this->agendaID, $data [$index], 'temp', $this->lastUpdate, $slotnum [$index], $bookbuffer [$index] );
				$index ++;
			}
		}
		return 'updated';
	}
	public function updateAgenda(array $data = array(), array $bookbuffer = array(), array $slotnum = array()) {
		$model = new Slot ();
		$permAgendaID = array ();
		$resylt;
		Agenda::updateAll ( [ 
				'lastUpdate' => $this->lastUpdate 
		], [ 
				'agendaID' => $this->agendaID 
		] );
		$permAgendaID = $model->getIDs ( $this->agendaID );
		$permSlotIDs = $model->getIDs ( $this->agendaID );
		
		$permindex = 0;
		$b = 1;
		for($index = 0; $index < sizeof ( $data ); $permindex ++) {
			
			if ($permAgendaID [$permindex] ['slotnum'] == $slotnum [$index]) {
				echo $model->updateSlot ( $data [$index], $bookbuffer [$index], $permSlotIDs [$permindex] ['slotID'] );
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
		$agendforShow = array ();
		$index = 0;
		
		while ( $permindex < sizeof ( $permSlotIDs ) && $tempindex < sizeof ( $tempSlotIDs ) ) {
			if ($tempSlotIDs [$tempindex] ['slotnum'] == $permSlotIDs [$permindex]) {
				$agendforShow [$index] ['slotID'] = $tempSlotIDs [$tempindex] ['slotID'];
				$agendforShow [$index] ['maxBookers'] = $tempSlotIDs [$tempindex] ['numberOfBookers'];
				$agendforShow [$index] ['bookCount'] = $tempSlotIDs [$tempindex] ['bookCount'];
				$agendforShow [$index] ['content'] = $tempSlotIDs [$tempindex] ['content'];
				$tempindex ++;
				$index ++;
			} else {
				$agendforShow [$index] ['slotID'] = $permSlotIDs [$permindex] ['slotID'];
				$agendforShow [$index] ['maxBookers'] = $permSlotIDs [$permindex] ['numberOfBookers'];
				$agendforShow [$index] ['bookCount'] = $permSlotIDs [$permindex] ['bookCount'];
				$agendforShow [$index] ['content'] = $permSlotIDs [$permindex] ['content'];
				
				$permindex ++;
				$index ++;
			}
		}
		
		while ( $tempindex < sizeof ( $tempSlotIDs ) ) {
			$agendforShow [$index] ['slotID'] = $tempSlotIDs [$tempindex] ['slotID'];
			$agendforShow [$index] ['maxBookers'] = $tempSlotIDs [$tempindex] ['numberOfBookers'];
			$agendforShow [$index] ['bookCount'] = $tempSlotIDs [$tempindex] ['bookCount'];
			$agendforShow [$index] ['content'] = $tempSlotIDs [$tempindex] ['content'];
			$tempindex ++;
			$index ++;
		}
		
		while ( $permindex < sizeof ( $permSlotIDs ) ) {
			$agendforShow [$index] ['slotID'] = $permSlotIDs [$permindex] ['slotID'];
			$agendforShow [$index] ['maxBookers'] = $permSlotIDs [$permindex] ['numberOfBookers'];
			$agendforShow [$index] ['bookCount'] = $permSlotIDs [$permindex] ['bookCount'];
			$agendforShow [$index] ['content'] = $permSlotIDs [$permindex] ['content'];
			$permindex ++;
			$index ++;
		}
		return $agendforShow;
	}
	/*
	 * public function showAgenda() {
	 * $model = new Slot ();
	 *
	 * $exist = Agenda::find ()->where ( [
	 * 'owner' => $this->owner,
	 * 'lastUpdate' => $this->lastUpdate
	 * ] )->one ();
	 * if ($exist == null) {
	 * $exist = Agenda::find ()->where ( [
	 * 'owner' => $this->owner,
	 * 'type' => 'perm'
	 * ] )->one ();
	 * }
	 * $permAgendaID = Agenda::find ()->where ( [
	 * 'owner' => $exist ['owner'],
	 * 'type' => 'perm'
	 * ] )->one ();
	 *
	 * $tempSlotIDs = array ();
	 * if ($exist ['type'] == 'temp') {
	 * $tempSlotIDs = $model->getIDs ( $this->agendaID );
	 * }
	 *
	 * $permSlotIDs = $model->getIDs ( $permAgendaID ['agendaID'] );
	 *
	 * $permindex = 0;
	 * $tempindex = 0;
	 * $agendforShow = array ();
	 * $index = 0;
	 *
	 * while ( $permindex < sizeof ( $permSlotIDs ) && $tempindex < sizeof ( $tempSlotIDs ) ) {
	 * if ($tempSlotIDs [$tempindex] ['slotnum'] == $permSlotIDs [$permindex]) {
	 * $agendforShow [$index] ['slotID'] = $tempSlotIDs [$tempindex] ['slotID'];
	 * $agendforShow [$index] ['maxBookers'] = $tempSlotIDs [$tempindex] ['numberOfBookers'];
	 * $agendforShow [$index] ['bookCount'] = $tempSlotIDs [$tempindex] ['bookCount'];
	 * $agendforShow [$index] ['content'] = $tempSlotIDs [$tempindex] ['content'];
	 * $tempindex ++;
	 * $index ++;
	 * } else {
	 * $agendforShow [$index] ['slotID'] = $permSlotIDs [$permindex] ['slotID'];
	 * $agendforShow [$index] ['maxBookers'] = $permSlotIDs [$permindex] ['numberOfBookers'];
	 * $agendforShow [$index] ['bookCount'] = $permSlotIDs [$permindex] ['bookCount'];
	 * $agendforShow [$index] ['content'] = $permSlotIDs [$permindex] ['content'];
	 *
	 * $permindex ++;
	 * $index ++;
	 * }
	 * }
	 *
	 * while ( $tempindex < sizeof ( $tempSlotIDs ) ) {
	 * $agendforShow [$index] ['slotID'] = $tempSlotIDs [$tempindex] ['slotID'];
	 * $agendforShow [$index] ['maxBookers'] = $tempSlotIDs [$tempindex] ['numberOfBookers'];
	 * $agendforShow [$index] ['bookCount'] = $tempSlotIDs [$tempindex] ['bookCount'];
	 * $agendforShow [$index] ['content'] = $tempSlotIDs [$tempindex] ['content'];
	 * $tempindex ++;
	 * $index ++;
	 * }
	 *
	 * while ( $permindex < sizeof ( $permSlotIDs ) ) {
	 * $agendforShow [$index] ['slotID'] = $permSlotIDs [$permindex] ['slotID'];
	 * $agendforShow [$index] ['maxBookers'] = $permSlotIDs [$permindex] ['numberOfBookers'];
	 * $agendforShow [$index] ['bookCount'] = $permSlotIDs [$permindex] ['bookCount'];
	 * $agendforShow [$index] ['content'] = $permSlotIDs [$permindex] ['content'];
	 * $permindex ++;
	 * $index ++;
	 * }
	 * return $agendforShow;
	 * }
	 */
}
