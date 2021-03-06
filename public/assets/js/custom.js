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


    $.datepicker.setDefaults( $.datepicker.regional[ "ru" ].dateFormat["dd-mm-yy"] );

    $('.date-picker').datepicker({
        dateFormat: 'dd-mm-yy',
    });
});

function join(arr /*, separator */) {
    var separator = arguments.length > 1 ? arguments[1] : ", ";
    return arr.filter(function(n){return n}).join(separator);
}

function typeDescription(type) {
    var TYPES = {
        'INDIVIDUAL': 'Индивидуальный предприниматель',
        'LEGAL': 'Организация'
    }
    return TYPES[type];
}



function showSuggestion(suggestion) {
    console.log(suggestion);
    var data = suggestion.data;
    if (!data)
        return;

    $("#type").text(
        typeDescription(data.type) + " (" + data.type + ")"
    );

    if (data.name)
        $("#name_short").val(join([data.opf && data.opf.short || "", data.name.short || data.name.full], " "));

    if (data.name && data.name.full)
        $("#name_full").val(join([data.opf && data.opf.full || "", data.name.full], " "));

    $("#inn").val(data.inn);

    $("#kpp").val(data.kpp);

    if (data.address)
        $("#ur_address").val(data.address.value);
}

$("#name_clients").suggestions({
    token: "b61f436ea3957ef1aefa6b14124b59ed766ac81f",
    type: "PARTY",
    count: 5,
    /* Вызывается, когда пользователь выбирает одну из подсказок */
    onSelect: showSuggestion
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

$("body").on("click", ".set-pay-income", function(e){
    var incomeId = $(this).attr('income');
    console.log(incomeId);
    $("body").on("click", ".income-data", function(e){
        var incomeDate = $('.income-date-picker').val();
        var route = $('.income-date-picker').attr('route');
        var token = $('.income-date-picker').attr('token');
        var url  = "".concat(window.location.href);
        console.log(url);
        if (incomeDate != 0) {
            $.ajax({
                type: 'post',
                url: route,
                data: {'incomeId': incomeId, 'incomeDate': incomeDate, '_token': token },
            }).done(function (data) {
                document.location.href = url;

            }).fail(function () {
                console.log('fail');
                document.location.href = url;
            });
        }
    });
});

$("body").on("click", ".set-pay-act", function(e){
    var actId = $(this).attr('act');
    console.log(actId);
    $("body").on("click", ".income-data", function(e){
        var actDate = $('.act-date-picker').val();
        var route = $('.act-date-picker').attr('route');
        var token = $('.act-date-picker').attr('token');
        var url  = "".concat(window.location.href);
        console.log(url);
        if (actDate != 0) {
            $.ajax({
                type: 'post',
                url: route,
                data: {'actId': actId, 'actDate': actDate, '_token': token },
            }).done(function (data) {
                document.location.href = url;

            }).fail(function () {
                console.log('fail');
                document.location.href = url;
            });
        }
    });
});

Date.prototype.yyyymmdd = function() {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();

    return [this.getFullYear() + '-',
        (mm>9 ? '' : '0') + mm + '-',
        (dd>9 ? '' : '0') + dd
    ].join('');
};

$('.income_modal').on('click', function() {
    var actIncome = $(this).text();
    var route = $(this).attr('route');
    var token = $(this).attr('token');
    $('.row-product').each(function(i,el){
        $(el).remove();
    });

    $.ajax({
        type: 'post',
        url: route,
        data: {'actIncome': actIncome, '_token': token },
    }).done(function (data) {
        console.log(data.account_number);
        console.log(data.client.name);
        console.log(data.comment);
        console.log(data.income_date);
        console.log(data.plan_date);
        console.log(data.service);
        console.log(data.pay_service);


        $.each( data.product, function( key, value ) {
            var textInfo = document.getElementById("modal-product"),
                Paragraph = textInfo.firstElementChild;
            var pInfo = document.createElement("div");
            pInfo.className = 'row row-product';
            pInfo.innerHTML =
                "<div class=\"col-md-4\">"+value.product+"</div>\n" +
                "<div class=\"col-md-4\">"+value.count+"</div>\n" +
                "<div class=\"col-md-4\">"+value.price+"</div>";
            textInfo.insertBefore(pInfo, Paragraph);
        });


        $('.modal-account_number').text('Счет № ' + data.account_number);
        $('.modal-client').text(data.client.name);
        $('.modal-comment').text(data.comment);
        $('.modal-date').text(data.income_date);

        data.plan_date = data.plan_date ? data.plan_date : "Не заполнено";
        console.log(data.plan_date);

        $('.modal-plan-date').text(new Date(data.plan_date.date).yyyymmdd());
        $('.modal-service').text(data.service);
        $('.modal-pay_service').text(data.pay_service);
        $('.modal-sum').text(data.sum);


    }).fail(function () {
        console.log('fail');

    });


    $('#category').change(function(){
        $('.optionChek').each(function(i,el){
            $(el).remove();
        });
        $.ajax({
            url: "{{route('income.getClientCheckingAccount')}}",
            data: { "value": $("#category").val(), '_token': '{{csrf_token()}}' },
            type: "post",
            success: function(data){
                $.each(data, function( index, value ){
                    console.log( value.name );
                    var option = document.createElement('OPTION');
                    option.value = (value.id);
                    option.textContent = (value.name);
                    option.className = 'optionChek';
                    document.querySelector('#client_cheking_accounts').appendChild(option);
                });
            }
        });
    });
});


// Elegant code XD
document.addEventListener('DOMContentLoaded', () => {
    $('.select2').select2();
    let moreFiltersLink = document.getElementById('moreFiltersLink');
    let moreFiltersBlock = document.getElementById('moreFilters');

    if (moreFiltersLink != null && moreFiltersLink != undefined) {

        moreFiltersLink.addEventListener('click', () => {

            if(moreFiltersBlock.classList.contains('collapse') && moreFiltersBlock.classList.contains('show')) {
                moreFiltersLink.innerText = 'Показать фильтры';
            } else {
                moreFiltersLink.innerText = 'Скрыть фильтры';
            }

        });

    }
});
