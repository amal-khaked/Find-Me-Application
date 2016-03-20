<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property integer $postID
 * @property string $owner
 * @property string $content
 * @property string $time
 *
 * @property Notification[] $notifications
 * @property Staff $owner0
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner', 'content', 'time'], 'required'],
            [['owner', 'time'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'postID' => 'Post ID',
            'owner' => 'Owner',
            'content' => 'Content',
            'time' => 'Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['postID' => 'postID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner0()
    {
        return $this->hasOne(Staff::className(), ['formalemail' => 'owner']);
    }
}
