<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?
if (!empty($models)) {?>
<div class="im-page js-im-page im-page_classic">
<?foreach ($models as $log) {?>
    <div class="im-mess-stack _im_mess_stack " data-peer="<?=$log->user->id?>" data-admin="">
        <div class="im-mess-stack--content">
            <div class="im-mess-stack--info">
                <div class="im-mess-stack--pname">
                    <?=Html::a($log->user->username,
                        '#',
                        [
                            'title' => $log->user->username,
                            'class' => 'message_to_from_list im-mess-stack--lnk',
                            'data' => ['id' => $log->user->id]
                        ]
                    )?> (<?if (!empty($log->accident)) {
                        $acc = $log->accident;

                        echo Html::a($acc->acc_id,
                            Url::to(['accident/view', 'id' => $acc->id]),
                            [
                                'title' => $acc->acc_id,
                                'class' => 'link_to_acc_work',
                                'target' => '_blank'
                            ]
                        );
                    }?><?if (!empty($log->works)) {
                        $work = $log->works;

                        echo ' -> '.Html::a(((!empty($work->full_work_performed)) ? $work->full_work_performed : ((!empty($work->reason)) ? $work->reason : "Работа с №" . $work->id)),
                            Url::to(['works/view', 'id' => $work->id]),
                            [
                                'title' => $work->full_work_performed,
                                'class' => 'link_to_acc_work',
                                'target' => '_blank'
                            ]
                        );
                    } elseif (!empty($log->work_id)) {
                        echo '-> '."Работа с №" . $log->work_id;
                    }?>) <span class="im-mess-stack--tools"> <a class="_im_mess_link"><?=date('j.m.Y H:i', strtotime($log->created))?> </a></span>
                </div>
            </div>
            <ul class="ui_clean_list im-mess-stack--mess _im_stack_messages">
                <li class="im-mess im_in _im_mess" aria-hidden="false" data-ts="<?=strtotime($log->created)?>" data-msgid="<?=$log->id?>" data-peer="<?=$log->user->id?>">
                    <div class="im-mess--actions">
                        <?if ($user_id != $log->user->id) {?>
                            <span role="link" aria-label="Ответить" class="im-mess--reply _im_mess_reply message_to_from_list" data-id="<?=$log->user->id?>" data-name="<?=$log->user->username?>"></span>
                        <?} else {?>
<!--                            <span role="link" aria-label="Редактировать" class="im-mess--edit _im_mess_edit"></span>-->
                        <?}?>
<!--                        <span role="link" aria-label="Важное сообщение" class="im-mess--fav _im_mess_fav"></span>-->
                    </div>
                    <div class="im-mess--check fl_l"></div>
                    <div class="im-mess--text wall_module _im_log_body">
                        <?if ($log->act_type == 'MESSAGE') {
                            echo $log->text;
                    //                        вставка картинки, если есть
                        } else {
                            $log->fields_json;
                            if(!empty($log->fields_json)) {
                                $changes_obj = json_decode($log->fields_json);

                                if (!empty($changes_obj)) {

                                    echo '<table class="works_table"><thead style="font-weight:bold"><tr><td>Field</td><td>From</td><td>To</td></tr></thead><tbody>';
                                    foreach ($changes_obj as $field_name => $field_values) {
                                        echo '<tr><td>' . $field_name . '</td><td>' . $field_values->from . '</td><td>' . $field_values->to . '</td>';
                                    }

                                    echo '</tbody></table>';
                                }
                            }
                        }?>
                    </div>
                    <span tabindex="0" role="link" aria-label="Выделить сообщение" class="blind_label im-mess--blind-select _im_mess_blind_label_select"></span>
                    <span class="blind_label im-mess--blind-read _im_mess_blind_unread_marker"></span>
                    <span class="im-mess--marker _im_mess_marker"></span>
                </li>
                <?if (!empty($log->files)) {?>
                    <li class="im-mess im_in _im_mess" aria-hidden="false" data-ts="<?=strtotime($log->created)?>" data-msgid="<?=$log->id?>" data-peer="<?=$log->user->id?>">
                        <div class="im-mess--actions">
                            <?/*if ($user_id == $log->user->id) {?>
                                <span role="link" aria-label="Ответить" class="im-mess--reply _im_mess_reply"></span>
                            <?} else {?>
                                <span role="link" aria-label="Редактировать" class="im-mess--edit _im_mess_edit"></span>
                            <?}*/?>
<!--                            <span role="link" aria-label="Важное сообщение" class="im-mess--fav _im_mess_fav"></span>-->
                        </div>
                        <div class="im-mess--check fl_l"></div>
                        <div class="im-mess--text wall_module _im_log_body">
                            <div class="">
                                <div class="page_post_sized_thumbs clear_fix">
                                     <?foreach ($log->files as $file) {
                                         if (exif_imagetype($_SERVER['DOCUMENT_ROOT'].$file->url)) echo Html::a(Html::img($file->url, ['alt' => $file->name]),
                                             $file->url,
                                             [
                                                 'title' => $file->name,
                                                 'target' => '_blank'
                                             ]
                                         );
                                         else echo Html::a($file->name,
                                             $file->url,
                                             [
                                                 'title' => $file->name,
                                                 'target' => '_blank'
                                             ]
                                         );
                                     }?>
                                </div>
                            </div>
                        </div>
                        <span tabindex="0" role="link" aria-label="Выделить сообщение" class="blind_label im-mess--blind-select _im_mess_blind_label_select"></span>
                        <span class="blind_label im-mess--blind-read _im_mess_blind_unread_marker"></span>
                        <span class="im-mess--marker _im_mess_marker"></span>
                    </li>
                <?}?>
            </ul>
        </div>
    </div>
<?}?>
</div>
<?= LinkPager::widget([
    'pagination' => $pagination,
]); ?>
<?} else {?><i>There is no messages or logs here. You can add message using a form below this table</i><?}?>