$( document ).ready(function () {
    // Table depth

    $('.depth-table').on('click', '.depth-table-toggle[toggled!=1]', function () {
        let tr = $(this).closest('tr');
        $('.depth-table-td[data-table-parent="' + $(tr).attr('data-table-id') + '"]').css('display', 'table-row');
        $(this).attr('toggled', '1');
        $(this).html('<i class="fa fa-minus" />');
    });

    $('.depth-table').on('click', '.depth-table-toggle[toggled=1]', function () {
        let tr = $(this).closest('tr');
        closeDepth($(tr).attr('data-table-id'));
        $(this).attr('toggled', '0');
        $(this).html('<i class="fa fa-plus" />');
    });


    $.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );

    $('.date-picker').datepicker({
        dateFormat: 'dd-mm-yy',
    });

    $('.select2').select2();

    $('input[name="is_payed"]').on('change', function () {
        if ($(this).is(':checked') && $('#income_date').val().length == 0) {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();

            if(dd<10) {
                dd = '0'+dd
            }

            if(mm<10) {
                mm = '0'+mm
            }

            $('#income_date').val(dd + '-' + mm + '-' + yyyy);
        }
    });
});

function closeDepth(i)
{
    $.each($('.depth-table-td[data-table-parent="' + i + '"]'), function () {
        console.log($(this).attr('data-table-id'));
        $(this).css('display', 'none');
        $(this).find('.depth-table-toggle').attr('toggled', '0');
        $(this).find('.depth-table-toggle').html('<i class="fa fa-plus" />');
        if ($('.depth-table-td[data-table-parent="' + $(this).attr('data-table-id') + '"]').length) {
            closeDepth($(this).attr('data-table-id'));
        }
    });
}
