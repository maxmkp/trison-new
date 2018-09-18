<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
global $userGroup;
if (!empty($_SESSION['user']['user_group'])) $userGroup = $_SESSION['user']['user_group'];
else $userGroup = 99;
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
    NavBar::begin([
        'brandLabel' => Html::img( '/uploads/logo.png', $options = ['width' => '150px'] ),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    if (!Yii::$app->user->isGuest) {
        if (($userGroup==1)||($userGroup==20)) {
            $items = [
                ['label' => 'Тарифы', 'url' => ['/cities/update-rates']],
                ['label' => 'Инциденты', 'url' => ['/accident']],
                ['label' => 'Работы', 'url' => ['/works']],
                ['label' => 'Инженеры', 'url' => ['/engineers']],
                ['label' => 'Магазины', 'url' => ['/stores']],
                ['label' => 'Оборудование', 'url' => ['/equipment']],
                ['label' => 'Города', 'url' => ['/cities']]
            ];
        } else {
            $items = [
                ['label' => 'Инциденты', 'url' => ['/accident/index?sort=-act_date&AccidentSearch%5Bacc_status%5D=OPEN']],
                ['label' => 'Работы', 'url' => ['/works']],
                ['label' => 'Инженеры', 'url' => ['/engineers']],
                ['label' => 'Магазины', 'url' => ['/stores']],
                ['label' => 'Оборудование', 'url' => ['/equipment']],
                ['label' => 'Города', 'url' => ['/cities']]
            ];
        }

        $items[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . $_SESSION['user']['username'] . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    } else {
        $items[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $items[] = ['label' => 'Login', 'url' => ['/site/login']];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items
    ]);
    NavBar::end();
    ?>

    <div class="container">
<!--        --><?//= Breadcrumbs::widget([
//            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
//        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; KubRu <?= date('Y') ?></p>

        <div id="google_translate_element" style="position: absolute;    left: 0;    right: 0;    margin: 0 auto;    width: 145px;"></div><script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,es,ru', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, multilanguagePage: true}, 'google_translate_element');
            }
        </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
