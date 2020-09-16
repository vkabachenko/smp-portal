<?php

/* @var $this yii\web\View */
/* @var $attributes array */
/* @var $section1 array */
/* @var $section2 array */
/* @var $section3 array */
/* @var $section4 array */
/* @var $section5 array */

$items = [];

if (!empty($section1)) {
    $items[] =             [
        'label' => 'Раздел 1',
        'content' => $this->render('_view-section-table', ['attributes' => $attributes, 'section' => $section1])
    ];
}

if (!empty($section2)) {
    $items[] =             [
        'label' => 'Раздел 2',
        'content' => $this->render('_view-section-table', ['attributes' => $attributes, 'section' => $section2])
    ];
}

if (!empty($section3)) {
    $items[] =             [
        'label' => 'Раздел 3',
        'content' => $this->render('_view-section-table', ['attributes' => $attributes, 'section' => $section3])
    ];
}

if (!empty($section4)) {
    $items[] =             [
        'label' => 'Раздел 4',
        'content' => $this->render('_view-section-table', ['attributes' => $attributes, 'section' => $section4])
    ];
}

if (!empty($section5)) {
    $items[] =             [
        'label' => 'Раздел 5',
        'content' => $this->render('_view-section-table', ['attributes' => $attributes, 'section' => $section5])
    ];
}


?>

<?= \yii\bootstrap\Tabs::widget(
    [
        'linkOptions' => ['class' => 'btn btn-primary'],
        'itemOptions' => ['style' => 'margin-top: 10px;'],
        'items' => $items
    ]
) ?>
