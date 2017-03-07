document.oncontextmenu = function() {return false;};
$(document).ready(function() {
    $("#fold:first").addClass('selected-html-element');

    $id = $(".selected-html-element").attr("data-folid");

    $.post("/main/showimage", {folder:$id}, function (data) {
        $("#image").html(data);
    });

    $("#hidden").attr ( "value", $id );

    // Вешаем слушатель события нажатие кнопок мыши для всего документа:
    $(document).mousedown(function(event) {
        // Убираем css класс selected-html-element у абсолютно всех элементов на странице с помощью селектора "*":
        $('*').removeClass('selected-html-element');
        // Удаляем предыдущие вызванное контекстное меню:
        $('.context-menu').remove();
        if (event.which === 1 && ($(event.target).attr("id")== 'mainfold' || $(event.target).attr("id")== 'fold' )) {
            var target = $(event.target);
            var dataid = target.attr("data-folid");
            $("#hidden").attr ( "value", dataid );
            target.addClass('selected-html-element');
        }
        // Проверяем нажата ли именно правая кнопка мыши:
        else if (event.which === 3 && ($(event.target).attr("id")== 'mainfold' || $(event.target).attr("id")== 'fold' )) {
            // Получаем элемент на котором был совершен клик:
            var target = $(event.target);

            // Добавляем класс selected-html-element что бы наглядно показать на чем именно мы кликнули (исключительно для тестирования):
            target.addClass('selected-html-element');
            var cl = target.attr("id");
            var dataid = target.attr("data-folid");
            $("#hidden").attr ( "value", dataid );
            if (cl != 'mainfold') {
                // Создаем меню:
                $('<div/>', {
                    class: 'context-menu' // Присваиваем блоку наш css класс контекстного меню:
                })
                    .css({
                        left: event.pageX + 'px', // Задаем позицию меню на X
                        top: event.pageY + 'px' // Задаем позицию меню по Y
                    })
                    .appendTo('body') // Присоединяем наше меню к body документа:
                    .append( // Добавляем пункты меню:
                        $('<ul/>')
                            .append('<li ><a id="Create" href="#">Create</a></li>')
                            .append('<li id="Delete"><a href="#">Delete</a></li>')
                            .append('<li id="Rename"><a href="#">Rename</a></li>')
                    )
                    .show('fast'); // Показываем меню с небольшим стандартным эффектом jQuery. Как раз очень хорошо подходит для меню
                $('#Create').mousedown(function (event) {
                    if (event.which === 1) {
                        $.post("/main/create/", {id:dataid}, function (data) {
                            $("#folder").html(data);
                            });
                    }
                    });
                $('#Delete').mousedown(function (event) {
                    if (event.which === 1) {
                        $.post("/main/delete/", {id:dataid}, function (data) {
                            $("#folder").html(data);
                        });
                    }
                });
                $('#Rename').mousedown(function (event) {
                    if (event.which === 1) {
                        var old_name = $('div[data-folid=' + dataid + ']').html();
                        $('div[data-folid=' + dataid + ']').html("<input class ='rename' maxlength='10' type='text' value='" + old_name + "' required/>");
                        $(".rename").focusout(function () { // событие клика по веб-документу
                        var div = $(".rename"); // тут указываем ID элемента

                         // и не по его дочерним элементам
                            var new_name = div.val();
                            $.post("/main/rename/", {id: dataid, new_name: new_name}, function (data) {
                                $("#folder").html(data);
                            });
                            return false;
                    });
                }
                });

    }
            else {
                $('<div/>', {
                    class: 'context-menu' // Присваиваем блоку наш css класс контекстного меню:
                })
                    .css({
                        left: event.pageX + 'px', // Задаем позицию меню на X
                        top: event.pageY + 'px' // Задаем позицию меню по Y
                    })
                    .appendTo('body') // Присоединяем наше меню к body документа:
                    .append( // Добавляем пункты меню:
                        $('<ul/>')
                            .append('<li ><a id="Create" >Create</a></li>')
                    )
                    .show('fast'); // Показываем меню с небольшим стандартным эффектом jQuery. Как раз очень хорошо подходит для меню
                $('#Create').mousedown(function (event) {
                    if (event.which === 1) {
                        $.post("/main/create/", {id:dataid}, function (data) {
                            $("#folder").html(data);
                        });
                    }
                });
            }

        }
    });
    $( "form" ).submit(function() {
        var input = $("#hidden");
        var folder = input.val();
        $.post("/main/showimage", {folder:folder}, function (data) {
            $("#image").html(data);
        });
        });

});


