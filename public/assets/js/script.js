/**
 * This file is part of the DAMJ Documents Projects
 *
 * (c) ECHO, Software development
 * @author Skander SMAOUI <ssmaoui@echo.tn>
 *
 */

    moment.locale('fr');

    $.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    $.fn.ForceNumericOnly = function() {
        return this.each(function()
        {
            $(this).keydown(function(e)
            {
                var key = e.charCode || e.keyCode || 0;
                return (key == 8 || key == 9 || key == 13 || key == 46 || key == 110 || key == 190 || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
            });
        });
    };

    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        columnDefs: [{
            targets  : 'no-sort',
            orderable: false,
            width: 100
        }],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '_INPUT_',
            info: '_START_ - _END_ sur _TOTAL_ lignes',
            searchPlaceholder: 'Tapez votre recherche...',
            lengthMenu: '<span>Afficher</span> _MENU_',
            paginate: { 'first': 'Premier', 'last': 'Dernier', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
        }
    });

    var
    datatablePagination,
        blockUIConfig = {
            message: '<i class="icon-spinner4 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#1b2024',
                opacity: 0.8,
                zIndex: 1200,
                cursor: 'wait'
            },
            css: {
                border: 0,
                color: '#fff',
                padding: 0,
                zIndex: 1201,
                backgroundColor: 'transparent'
            }
        },
        initPlugins = function(){

            $(".numeric").ForceNumericOnly();

            if ($('a[data-toggle="tab"]').length > 0) {
                var activeTab = localStorage.getItem('activeTab');
                
                if (activeTab) {
                    $('a[href="' + activeTab + '"]').tab('show');
                }
                
                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    localStorage.setItem('activeTab', e.target.hash);
                });
            } else {
                localStorage.setItem('activeTab', null);
            }

            if ($('.select').length > 0) {
                $('.select').select2({
                    allowClear: true,
                    placeholder: 'Choisir une option'
                });
            }
    
            if ($('textarea.trumbowyg').length > 0) {
                $('textarea.trumbowyg').trumbowyg({
                    btns: [
                        ['viewHTML'],
                        ['formatting', 'strong', 'em'],
                        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                        ['unorderedList', 'orderedList'],
                        ['insertImage', 'link']
                    ]
                });
            }
            
            if ($('.datepicker').length > 0) {
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd', 
                    language: 'fr',
                });
            }

            if ($('.datetimepicker').length > 0) {
                $('.datetimepicker').datetimepicker({
                    autoclose: true,
                    minView: 0,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd hh:ii', 
                    minuteStep: 15,
                    language: 'fr',
                })
                .on('changeDay', function(e){
                    $(this).datetimepicker('update', e.date);
                });
            }

            if ($('.datatable').length > 0) {
                datatablePagination = $('.datatable').DataTable({
                    pagingType: "simple",
                    responsive: true,
                    language: {
                        paginate: {'next': $('html').attr('dir') == 'rtl' ? 'Suiv. &larr;' : 'Suiv. &rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr; Préc.' : '&larr; Préc.'}
                    }
                });

                $('.dataTables_length select').select2({
                    minimumResultsForSearch: Infinity,
                    dropdownAutoWidth: true,
                    width: 'auto'
                });
            }

            $(".move-preview-after-label").each(function(index){
                var $this = $(this),
                    data = $this.data(),
                    $target = $('label:first', $(data.target).parent().parent());
                $('div', $this).insertAfter($target);
                $this.remove();
            });
        },
        cb_empty = function($element) {
            var data = $element.data();
            
            $(data.target).val('');
        },
        cb_form_modal = function($elt){
            $modal = $elt.closest('.modal');
            $modal.modal('hide');
    
            location.reload();
            return false;
        },
        cb_reload = function() {
    
            location.reload();
            return false;
        }
    ;

$(document).ready(function(){
    initPlugins();
    
    if ($('.select-all-cta').length > 0) {
        $('.select-all-cta').each(function(index){
            let selectID = $(this).attr('id');
            $(this).parent().prepend('<div class="form-custom-actions d-flex align-items-center float-right"><input id="'+selectID+'_action" type="checkbox" class="action" data-action="select-all" data-target="#'+selectID+'" data-noprevent="true"> <label for="'+selectID+'_action">Sélectionner tout</label></div>');
        });
    }


    $(document).on('change', '.ajx-filter', function(){
        var $this = $(this),
            data = $this.data(),
            inputs = '';

        $( ".ajx-filter" ).each(function() {
            inputs += '<input type="hidden" name="'+ $(this).data('var') +'" value="'+ $(this).val() +'" />';
        });

        $('<form action="'+ data.url +'" method="GET">'+ inputs + '</form>')
            .appendTo('body')
            .submit();
    });
    
    $(document).on("click", "a[data-action='upload']", function(e){
        e.preventDefault();

        let $this = $(this),
            data = $this.data(),
            fileInputOptions = data.options;

        $(fileInputOptions.elErrorContainer)
            .attr('class', 'alert alert-danger hidden alert-styled-left alert-arrow-left bg-white')
            .attr('style', '');

        $(data.target)
            .fileinput($.extend(fileInputOptions, {
                msgInvalidFileExtension: 'Type de fichier invalide "{name}". Seules les extensions "{extensions}" sont autorisées.',
                msgUploadEmpty: 'Format de fichier non valide pour le téléchargement.',
                msgErrorClass: 'alert alert-danger'
            }))
            .on('filepreupload', function(event, data) {
                $.blockUI(blockUIConfig);
            })
            .on('fileuploaded', function(event, data) {
                var _button = '<button type="button" class="close" data-dismiss="alert"><span>×</span></button>';
                $.unblockUI();
                $(fileInputOptions.elErrorContainer)
                    .attr("class", 'alert-styled-left alert-arrow-left bg-white ' + (data.response.status ? "alert alert-success" : "alert alert-danger"))
                    .html(_button + data.response.message)
                    .fadeIn(500)
                ;
            })
            .trigger('click')
            .on('change', function(event) {
                $(this).fileinput('upload');
            })
        ;

        $(fileInputOptions.elErrorContainer).on('close.bs.alert', function (e) {
            e.preventDefault();
            $(this)
                .attr('class', 'alert alert-danger hidden alert-styled-left alert-arrow-left bg-white')
                .attr('style', '');
        });
    });

    $(document).on('focusout', 'form.form-inline-ajx input', function(){
        var $this = $(this),
            data = $this.data(),
            $form = $(this).closest('form');

        if ($this.val() !== '') {
            $.ajax({
                url: data.route,
                type: "POST",
                dataType: "json",
                data: {
                    value: $this.val(),
                    _action: data.action,
                    _token: $('[name="_token"]', $form).val()
                },
                async: true,
                beforeSend: function()
                {
                    $.blockUI(blockUIConfig);
                },
                success: function (response)
                {
                    if (response.status) {
                        $this.fadeOut(300).fadeIn(200);
                        
                    }
                },
                complete : function (responses)
                {
                    $.unblockUI();
                }
            });
        }
    });
        
    $(document).on('click', '.action', function(e){
        var $this = $(this),
            data = $this.data(),
            $form = $this.closest('form');
            
        if (!data.noprevent){
            e.preventDefault();
        }

        switch (data.action) {
            case 'ajx':
                var action = function(){
                    $.ajax({
                        url: data.route,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            jquery_callback: data.callback,
                            options: data.options,
                            _token: $('[name="_token"]', $form).val()
                        },
                        async: false,
                        beforeSend: function()
                        {
                            $.blockUI(blockUIConfig);
                        },
                        success: function (responses)
                        {
                            if (responses.status) {
                                window[responses.jquery_callback]($this);
                            }
                        },
                        complete : function (responses)
                        {
                            $.unblockUI();
                        }
                    });
                    
                }
                
                if (JSON.parse(data.confirm)) {
                    if (confirm(data.confirm_text ? data.confirm_text : "Êtes vous sure ?")) {
                        action();
                    }
                } else {
                    action();
                }
                break;
            case 'modal_form_ajx':
                $.ajax({
                    url: data.route,
                    type: "POST",
                    dataType: "html",
                    data: {
                        id: data.id,
                        _token: $('[name="_token"]', $form).val()
                    },
                    async: true,
                    beforeSend: function()
                    {
                        $.blockUI(blockUIConfig);
                    },
                    success: function (response) {
                        var $modal = $(data.target);
                        
                        $modal
                            .html(response)
                            .modal();

                        initPlugins();
                        $('select', $modal).select2({
                            dropdownParent: $(data.target),
                            allowClear: true,
                            placeholder: 'Choisir une option'
                        });
                    },
                    complete : function (response)
                    {
                        $.unblockUI();
                    }
                });
                break;
            case 'submit_form_ajx':
                if (data.contraints) {
                    $.each(data.contraints, function(key, value){
                        if (value.value) {
                            $(value.field, $form).attr('required', 'required');
                        }
                        else {
                            $(value.field, $form)
                                    .removeClass('error')
                                    .removeAttr('required');
                        }
                    });
                } 
                $form.validate();
                if ($form.valid()) {
                    $.ajax({
                        url: $form.attr('action') ? $form.attr('action') : data.url,
                        type: $form.attr('method'),
                        dataType: "json",
                        data:  $form.serializeObject(),
                        async: false,
                        beforeSend: function()
                        {
                            $.blockUI(blockUIConfig);
                        },
                        success: function (responses)
                        {
                            if (responses.status) {
                                $form[0].reset();
                                window[responses.jquery_callback]($this);
                            } else {
                                if (responses.message) {
                                    $(".alert", $form).show().html(responses.message);
                                }
                            }
                        },
                        complete : function (responses)
                        {
                            $.unblockUI();
                        }
                    });
                }
                break;
            case 'select-all':
                var $select = $(data.target);
                
                if ($this.is(':checked') ){
                    $select.select2('destroy').find('option').prop('selected', 'selected').end().select2();
                } else {
                    $select.select2('destroy').find('option').prop('selected', false).end().select2();
                }
                break;
        }
    });

});