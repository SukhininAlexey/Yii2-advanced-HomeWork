<?php

namespace common\models\task;

use Yii;

/**
 * This is the model class for table "comment_pics".
 *
 * @property int $id
 * @property string $file_name
 * @property string $view_name
 * @property int $comment_id
 *
 * @property Comments $comment
 */
class CommentPic extends \yii\db\ActiveRecord
{
    
    public $image;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment_pics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_name', 'view_name'], 'string'],
            [['comment_id'], 'integer'],
            [['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comment::className(), 'targetAttribute' => ['comment_id' => 'id']],
        ];
    }
    
    public function uploadImage($serial = 1){
        if($this->validate()){
            $this->file_name = "Picture_{$this->comment_id}_{$serial}.{$this->image->getExtension()}";
            $filename = "@webroot/img/big/" . $this->file_name;
            $smallFilename = "@webroot/img/small/" . $this->file_name;
            $this->view_name = $this->image->getBaseName();
            $this->image->saveAs(\Yii::getAlias($filename));
            $this->save();
            \yii\imagine\Image::thumbnail($filename, 200, 100)
                    ->save(\Yii::getAlias($smallFilename));
            return true;
        }
        return false;
    }

    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_name' => 'File Name',
            'view_name' => 'View Name',
            'comment_id' => 'Comment ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Comment::className(), ['id' => 'comment_id']);
    }
}
