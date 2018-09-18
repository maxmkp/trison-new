<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?
if (!empty($models)) {
$res = '<table class="works_table"><tr><th>Created</th><th>User</th><th>Type</th><th>Text/Changes</th><th>Attached Files</th><th>Accident</th><th>Work</th></tr>';
foreach ($models as $log) {
    $res .= '<tr><td>' . date('j.m.Y H:i:s', strtotime($log->created)) . '</td><td>'.
Html::a($log->user->username,
    '#',
    [
        'title' => $log->user->username,
        'class' => 'message_to_from_list',
        'data' => ['id' => $log->user->id]
    ]
)
.'</td><td>' . $log->act_type . '</td><td>';
    if ($log->act_type == 'MESSAGE') {
        $res .= $log->text;
//                        вставка картинки, если есть
    } else {
        $log->fields_json;
        if(!empty($log->fields_json)) {
            $changes_obj = json_decode($log->fields_json);

            if (!empty($changes_obj)) {

                $res .= '<table class="works_table"><thead style="font-weight:bold"><tr><td>Field</td><td>From</td><td>To</td></tr></thead><tbody>';
                foreach ($changes_obj as $field_name => $field_values) {
                    $res .= '<tr><td>' . $field_name . '</td><td>' . $field_values->from . '</td><td>' . $field_values->to . '</td>';
                }

                $res .= '</tbody></table>';
            } else $res = '';
        }
    }

    $res .= '</td><td>';

    if (!empty($log->files)) {
        foreach ($log->files as $file) $res .= Html::a($file->name,
                $file->url,
                [
                    'title' => $file->name,
                    'target' => '_blank'
                ]
            );
    }

    $res .= '</td><td>';

    if (!empty($log->accident)) {
        $acc = $log->accident;

        $res .= Html::a($acc->acc_id,
            Url::to(['accident/view', 'id' => $acc->id]),
            [
                'title' => $acc->acc_id,
                'target' => '_blank'
            ]
        );
    }

    $res .= '</td><td>';

    if (!empty($log->works)) {
        $work = $log->works;

        $res .= Html::a(((!empty($work->full_work_performed)) ? $work->full_work_performed : ((!empty($work->reason)) ? $work->reason : "Работа с №" . $work->id)),
            Url::to(['works/view', 'id' => $work->id]),
            [
                'title' => $work->full_work_performed,
                'target' => '_blank'
            ]
        );
    } elseif (!empty($log->work_id)) {
        $res .= "Работа с №" . $log->work_id;
    } else $res .= '—';


    $res .= '</td></tr>';

//                $res.=print_r($log, true);
}
$res .= '</table>';
echo $res;
?>
<?= LinkPager::widget([
    'pagination' => $pagination,
]); ?>
<?} else {?><i>There is no messages or logs here. You can add message using a form below this table</i><?}?>