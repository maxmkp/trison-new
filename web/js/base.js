$().ready(function(){
    date = new Date();
    $('.pic a.link-del, .pdf a.link-del').click(function(e){
        e.preventDefault();

        $this=$(this);
        id=$this.attr('data-id');
        href=$this.attr('data-href');
        $.ajax({
            type:'POST',
            cache: false,
            url: href,
            data: 'id='+id,
            success  : function(response) {
                $this.parent().remove();
            }
        });

    });

    $('select.ajax').change(function(){
        $this=$(this);
        val=$this.val();
        name=$this.attr('id');
        href=$this.attr('data-href');
        $after_this=$('.after_'+name);
        $after_this.html('').prop('disabled', true);
        cycle_name=name;
        while(true) {
            if ($('.after_' + cycle_name).hasClass('ajax')) {
                cycle_name = $('.after_' + cycle_name).attr('id');
                $('.after_' + cycle_name).html('').prop('disabled', true);
            } else break;
        }

        $.ajax({
            type:'POST',
            cache: false,
            url: href,
            data: 'val='+val+'&name='+name,
            success  : function(response) {
                $after_this.prop('disabled', false).html(response);
            }
        });

    });

    $('.after_works-accident_id').change(function(){
        $this=$(this);
        href=$this.attr('data-href');
        work_id=$this.attr('data-work_id');

        id=$this.val();
        fio=$this.find('option[value='+id+']').text();

        $.ajax({
            type:'POST',
            cache: false,
            url: href,
            data: 'eng='+id+'&work='+work_id,
            success  : function(response) {
                $this.before('<div class="worker"><a href="/engineers/view?id='+id+'" target="_blank">'+fio+'</a><span class="del_worker" data-id="'+response+'"></span></div>');
            }
        });

    });

    if ($('span.STATUS').length) {
        $('span.STATUS').each(function(){
            status=$(this).html();
            $(this).closest('tr').addClass('STATUS_'+status);
        });
    }
    //doing setting of the showing columns
    if($('.grid-view').length) {
        $('.grid-view').each(function(){
            $this = $(this);
            grid_id = $this.attr('id');
            model_name = $this.find('table').attr('id');
            cols = [];
            iter = 0;
            $this.find('table.table>thead>tr>th, table.table>thead>tr>td, table.table>tbody>tr>td').hide();
            $this.find('thead th').each(function(){
                $th = $(this);
                if ($th.find('a').length) th_name = $th.find('a').html();
                else th_name = $th.html();
                cols[iter] = th_name;
                iter++;
            });

            if (cols.length) {
                filterBlock = '<div class="set_cols_wrapper"><ul class="set_cols">';
                $.each(cols, function(index, value){
                    if (value != '') {
                        if (value == '&nbsp;') value='Operations';
                        if ($('#db_cols').length) {
                            if ($('#db_cols').hasClass('show'+index)) checked = ' checked';
                            else checked = '';
                        } else checked = ' checked';
                        filterBlock += '<li><label><input type="checkbox" name="cols[]"'+checked+' value="'+index+'">'+value+'</label>';

                        if (checked==' checked') {
                            $this.find('table.table>thead>tr>th').eq(index).show();
                            $this.find('table.table>thead>tr>td').eq(index).show();
                            $this.find('table.table>tbody>tr').each(function(){$(this).children().eq(index).show();});
                        }
                    }
                });
                filterBlock += '</ul></div>';
                $this.prepend(filterBlock);
            }

            if ($this.find('.set_cols').length) $this.find('.set_cols input').change(function(){

                $a = $(this);
                eq = $a.val();
                // alert(eq);
                if ($a.prop('checked')==true) {
                    // $a.removeClass('nonactive');
                    $this.find('table.table>thead>tr>th').eq(eq).show();
                    $this.find('table.table>thead>tr>td').eq(eq).show();
                    $this.find('table.table>tbody>tr').each(function(){$(this).children().eq(eq).show();});

                    setChanges(model_name, $this);

                //    отправка аяксом в таблицу пользователей через actionChangeShowCols, проверить
                } else {
                    // $a.addClass('nonactive');
                    $this.find('table.table>thead>tr>th').eq(eq).hide();
                    $this.find('table.table>thead>tr>td').eq(eq).hide();
                    $this.find('table.table>tbody>tr').each(function(){$(this).children().eq(eq).hide();});

                    setChanges(model_name, $this);
                }
            });

        });


    }
    //doing filter sign
    if ($('table.table thead th').length) {
        $('table.table th').each(function(index, value){
            if ($('table.table thead td').eq(index).find('input, select').length) $(this).append('<a class="th_filter" data-eq="'+index+'"></a>');
        });
        $('table.table .filters td input, table.table .filters td select').each(function(index, value){
            if (($(this).val() != null)&&($(this).val() != '')) {
                $(this).parent().addClass('active');
                $(this).closest('table').addClass('active_filter');
            }
        });
        $('.th_filter').click(function(){
            eq = $(this).attr('data-eq');
            $thead_td = $('table.table thead td').eq(eq);
            if($thead_td.hasClass('active')) {
                $thead_td.removeClass('active');
                if (!$thead_td.closest('table').find('.filters td.active').length) $thead_td.closest('table').removeClass('active_filter');
            } else {
                $thead_td.addClass('active');
                $thead_td.closest('table').addClass('active_filter');
            }
        });
    }
//отправка формы с комментарием аяксом
    $('.js-form-success').on('click', function(event) {

        event.preventDefault();

        if ($(this).is(':disabled')) return false;
        else {

            $form = $(this).closest('form');
            var action = $form.attr('action');

            if ($form.find('input[type=file]').length > 0) {

                sendFileForm($form, action);

            }
        }
    });
//отправка причины с комментарием аяксом
    $('.js-cause-send').on('click', function(event) {

        event.preventDefault();
        $this = $(this);
        $form = $this.closest('form');

        if ($this.is(':disabled')) return false;
        else {
            if ($this.hasClass('to_send')) {

                if ($form.find('textarea').val() != '') {

                    var action = $form.attr('action');

                    if (sendFileForm($form, action)) location.reload();

                } else $form.find('textarea').addClass('is-error');

            } else {

                $form.find('.js-cause-send').removeClass('to_send');
                $form.find('.add_message_block').remove();
                $this.addClass('to_send').before('<div class="add_message_block"><div><textarea name="text"></textarea></div><div class="inputfile"><label><span></span><input type="file" name="attach[]"></label></div></div>');

            }
        }
    });
//кастомизация файлового инпута
    $('input[type=file]').change(function(){
        $this = $(this);
        val = $this.val().replace("fakepath", "...");
        $this.closest('label').addClass('active').find('span').html(val);
    });

    if ($('.logs_table').length) {
        //предзагрузка логов
        $this = $('.logs_table');
        $this.closest('tr').addClass('logs_tr');
        work_id = $this.attr('data-work');
        if (work_id > 0) params = 'LogsSearch[work_id]='+work_id;
        else {
            acc_id = $this.attr('data-acc');
            params = 'LogsSearch[accident_id]='+acc_id;
        }
        $.ajax({
            type:'POST',
            cache: false,
            url: '/logs/view-messages',
            data: params,
            success  : function(response) {
                $this.html(response);
            }
        });
        //аякс-пагинация логов
        $('.logs_table').on('click', '.pagination a', function(e){
            e.preventDefault();

            page = $(this).attr('data-page');
            params_w_page = params+'&page='+page;

            $.ajax({
                type:'POST',
                cache: false,
                url: '/logs/view-messages',
                data: params_w_page,
                success  : function(response) {
                    $this.html(response);
                }
            });
        });
        //выбор отправителей
        $('.logs_table').on('click', '.message_to_from_list', function(e){
           e.preventDefault();
           title = $(this).attr('title');
           if (title == undefined) title = $(this).attr('data-name');
           user_id = $(this).attr('data-id');
           if ($('.recipients_box input[value='+user_id+']').length) $('.recipients_box input[value='+user_id+']').prop('checked', true);
           else $('.recipients_box').append('<li><input type="checkbox" name="recipients[]" value="'+user_id+'" id="recipient_'+user_id+'" checked><label for="recipient_'+user_id+'">'+title+'</label>');

           scrollTo('.recipients_box');
        });
    }


    // $('.change_status').click(function(e){
    //     e.preventDefault();
    //     id=$('th').index($('th [data-sort=status]').parent());
    //     $this=$(this);
    //     $span=$this.find('span');
    //     $status=$this.closest('td').find('b');
    //     $.ajax({
    //         type:'GET',
    //         cache: false,
    //         url: $this.attr('href'),
    //         success  : function(response) {
    //             if ($span.hasClass('glyphicon-ok')) {
    //                 $span.removeClass('glyphicon-ok').addClass('glyphicon-remove').attr('title', 'Открыть инцидент');
    //                 $status.html('CLOSED');
    //             } else {
    //                 $span.removeClass('glyphicon-remove').addClass('glyphicon-ok').attr('title', 'Закрыть инцидент');
    //                 $status.html('OPEN');
    //             }
    //         }
    //     });
    // });

    $('.copy_block a').click(function(){
       $element = $(this).closest('.copy_block').addClass('copied').find('span');
       copyToClipboard($element);
    });

    // $('.error_status, .cancel_status').click(function(){
    //     $this = $(this);
    //     if ($this.closest('.grid-view').length) gridView = true;
    //     else gridView = false;
    //
    //     var type = $this.attr('data-type');
    //     var id = $this.attr('data-id');
    //     if ($this.hasClass('error_status')) {
    //         quest = 'Вы действительно хотите отменить заявку?';
    //         action = 'set-error-status';
    //     } else {
    //         quest = 'Вы действительно хотите отменить последний статус?';
    //         action = 'set-cancel-status';
    //     }
    //     var areYouReady = confirm(quest);
    //
    //     if (areYouReady) {
    //
    //         var yourCause = prompt("Укажите причину установки статуса", '');
    //
    //         if (yourCause != false) {
    //             var statusOfChange = sendButtonStatusChange(id, type, action, yourCause);
    //             if (!statusOfChange) alert('Неизвестная ошибка смены статуса');
    //             else {
    //                 if (gridView) $this.closest('tr').find('STATUS').html(statusOfChange);
    //                 else $this.closest('table').find('STATUS').html(statusOfChange);
    //             }
    //         } else alert('Без выяснения причины смена статуса невозможна');
    //
    //     }
    //
    // });

    $('a.printIframe').click(function(e){
        e.preventDefault();

        window.frames['iframePrint'].focus();
        window.frames['iframePrint'].print();
        // href=$(this).attr('href');
        //
        // $('body').append('<iframe src="'+href+'" onload="window.print();"></iframe>');
    });
});

function setChanges(model_name, $this) {
    var cols_list='';
    $this.find('.set_cols input:checked').each(function(){
        cols_list += $(this).val()+',';
    });
    if (cols_list != '') cols_list = cols_list.slice(0, -1);

    $.ajax({
        type:'POST',
        cache: false,
        url: '/user/change-show-cols',
        data: 'name='+model_name+'&fields='+cols_list,
        success  : function(response) {
            // $after_this.prop('disabled', false).html(response);
        }
    });
}

function sendFileForm($form, action, pass_through){
    $form.closest('.form-cover').addClass('preload');

    var formData = new FormData();

    $form.find('input[type=file]').each(function(){
        name=$(this).attr('name');

        $.each($(this)[0].files, function(i, file) {
            formData.append(name, file);
        });

    });

    $form.find('input[type=text], input[type=tel], select, input[type=checkbox], input[type=radio], input[type=hidden], textarea').each(function(){
        formData.append($(this).attr('name'), $(this).val());
    });

    $.ajax({
        url: action,
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: formData, //указываем что отправляем
        success: function(data){
            $form.closest('.form-cover').removeClass('preload');
            var data_arr = data.split(':');
            if (data_arr[0]=='error') {

                $('.is-error').removeClass('is-error');
                // $('.errorBlock').removeClass('errorBlock');

                errArr = data_arr[1].split(';');
                errArr.forEach(function(error, i, errArr) {
                    err = error.split('==');
                    if ($form.find('[name='+err[0]+']').length>0) {
                        if ($form.find('[name='+err[0]+']').attr('type')!='file') $form.find('[name='+err[0]+']').addClass('is-error');
                        else $form.find('[name='+err[0]+']').closest('label').addClass('is-error');
                    } else if ($form.find('[name^="'+err[0]+'"]').length>0) $form.find('[name^="'+err[0]+'"]').eq(0).addClass('is-error');
                });

                return false;
            } else {
                if (pass_through === undefined) {
                    $form.find('input[type=text], input[type=file], textarea').val('');
                    $form.find('input[type=file]').closest('label').find('span').html('');
                    $('.logs_table').html(data);
                    scrollTo('.logs_table');
                }
                return true;
            }
        }
    });
}

function scrollTo(selector) {
    $("html, body").animate({scrollTop: $(selector).offset().top});
}

function copyToClipboard($element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($element.text()).select();
    document.execCommand("copy");
    $temp.remove();
}

function sendButtonStatusChange(id, type, action, yourCause) {
    postAction = '/'+type+'/'+action;

    $.ajax({
        url: postAction,
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: {'id':id, 'cause':yourCause}, //указываем что отправляем
        success: function(data){
            return data;
        },
        error: function(){
            return false;
        }
    });
}