<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    // 导航栏
    NavBar::begin([
        'brandLabel' => Yii::t('common', 'brandLabel'),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    // 左侧菜单项
    $leftMenus = [
        ['label' => Yii::t('common', 'home'), 'url' => ['/site/index']],
        ['label' => Yii::t('common', 'post'), 'url' => ['/post/index']],
    ];

    // 右侧导航栏
    // 如果未登录，则显示注册和登录
    if (Yii::$app->user->isGuest) {
        $rightMenus[] = ['label' => Yii::t('common', 'signup'), 'url' => ['/site/signup']];
        $rightMenus[] = ['label' => Yii::t('common', 'login'), 'url' => ['/site/login']];
    } else {
        // 如果登录
        $rightMenus[] = [
            // 当图标不存在时，显示 alt 的内容
            'label' => '<img src="'.Yii::$app->params['avatar']['small'].'" alt="'.Yii::$app->user->identity->username.'">',
            'linkOptions' => ['class' => 'avatar'], // 使用 css 中的样式
            // 下拉列表
            'items' => [
                [
                    'label' => '<i class="fa fa-user-circle-o"></i> 个人中心',
                    'url' => ['site/logout'],
                    'linkOptions' => ['data-method' => 'psot'],
                ],
                [
                    'label' => '<i class="fa fa-sign-out"></i> 注销',
                    'url' => ['site/logout'],
                    'linkOptions' => ['data-method' => 'post'],
                ],
            ],
            // 给 ul 添加 class 属性，实现左对齐
            'dropDownOptions' => ['class'=>'dropdown-menu dropdown-menu-left'],
            // 'linkOptions' => ['data-method' => 'post', 'style' => 'padding:5px']

        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => $leftMenus,
    ]);


    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,    // 不编码 labels，这样才能显示图片
        'items' => $rightMenus,
    ]);
    NavBar::end();
    ?>


    <div class="container">
        <!-- 面包屑 -->
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>


<!-- 页脚 -->
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::t('common', 'pullLeft')) ?> <?= date('Y') ?></p>

        <!--<p class="pull-right"><?/*= Yii::powered() */?></p>-->
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
