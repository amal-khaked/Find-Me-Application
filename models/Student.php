<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "student".
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 *
 * @property Follow[] $follows
 * @property Notification[] $notifications
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'email', 'password'], 'required'],
            [['id', 'name', 'email', 'password'], 'string', 'max' => 50],
            [['email'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollows()
    {
        return $this->hasMany(Follow::className(), ['studentID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['notified' => 'id']);
    }
}
