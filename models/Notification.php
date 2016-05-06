<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property integer $notificationID
 * @property integer $postID
 * @property integer $notified
 *
 * @property Post $post
 * @property Student $notified0
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postID', 'notified'], 'required'],
            [['postID', 'notified'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'notificationID' => 'Notification ID',
            'postID' => 'Post ID',
            'notified' => 'Notified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['postID' => 'postID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotified0()
    {
        return $this->hasOne(Student::className(), ['id' => 'notified']);
    }
}
