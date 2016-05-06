<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book".
 *
 * @property integer $bookID
 * @property string $studentID
 * @property integer $slotID
 * @property string $content
 * @property string $date
 *
 * @property Slot $slot
 * @property Student $student
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studentID', 'slotID', 'content', 'date'], 'required'],
            [['slotID'], 'integer'],
            [['date'], 'safe'],
            [['studentID', 'content'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bookID' => 'Book ID',
            'studentID' => 'Student ID',
            'slotID' => 'Slot ID',
            'content' => 'Content',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlot()
    {
        return $this->hasOne(Slot::className(), ['slotID' => 'slotID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['id' => 'studentID']);
    }
}
