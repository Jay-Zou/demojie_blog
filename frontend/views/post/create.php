<?php
/**
 * Created by PhpStorm.
 * User: 14261
 * Date: 2018/4/12
 * Time: 上午 10:03
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => '文章', 'url' => ['post/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-lg-9">
        <div class="panel-title box-title">
            <span>创建文章</span>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin() ?>

                <?=$form->field($model, 'title')->textInput(['maxlength' => true])  ?>

                <?=$form->field($model, 'cat_id')->dropDownList($cats)  ?>

                <!-- 图片上传框 -->
                <?= $form->field($model, 'label_img')->widget('common\widgets\file_upload\FileUpload',[
                    'config'=>[
                    ]
                ]) ?>

                <!-- 百度富文本编辑器 -->
                <?= $form->field($model, 'content')->widget('common\widgets\ueditor\Ueditor',[
                    'options'=>[
                        'initialFrameHeight' => 400,
                        // 'toolbars' => [],
                    ]
                ]) ?>


                <?= $form->field($model, 'label_img')->widget('common\widgets\input_tags\InputTags',[
                    'config'=>[
                    ]
                ]) ?>

            <div class="form-group">
                    <?=Html::submitButton('发布', ['class' => 'btn btn-success'])  ?>
                </div>

            <?php ActiveForm::end() ?>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="panel-title box-title">
            <span>注意事项</span>
        </div>
        <div class="panel-body">
            <p>1.没有</p>
            <p>2.没有</p>
        </div>
    </div>
</div>

