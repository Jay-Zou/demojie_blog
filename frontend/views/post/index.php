<?php
/**
 * Created by PhpStorm.
 * User: 14261
 * Date: 2018/4/11
 * Time: 下午 8:29
 */

use frontend\widgets\post\PostWidget;

$this->title = '文章';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-lg-9">
        <?= PostWidget::widget([
                'limit' => 6,   // 配置每页条数，如果一页可以显示，则不会显示分页导航栏
                'page' => true  // 是否显示分页
        ]); ?>
    </div>

    <div class="col-lg-3">

    </div>
</div>