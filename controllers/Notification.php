<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property integer $notificationID
 * @property integer $postID
 * @property string $notified
 * @property string $sender
 * @property string $type
 * @property string $seen
 *
 * @property Post $post
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
            [['postID', 'notified', 'sender', 'type', 'seen'], 'required'],
            [['postID'], 'integer'],
            [['notified', 'sender'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 30],
            [['seen'], 'string', 'max' => 15]
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
            'sender' => 'Sender',
            'type' => 'Type',
            'seen' => 'Seen',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['postID' => 'postID']);
    }
}
