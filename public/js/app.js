const app = {
    controllers: {},
    data: {
        place: "",
        range: 42
    },
    handlers: {
        getDataFromDom() {
            app.data.range = $("#slider").slider('value');
            app.data.place = $('#place-selector').val();
        },
    }
}

const anyUiChangeHandler = debounce(() => app.controllers.getPlaceInfo());

app.controllers.getPlaceInfo = function () {
    const html = '';

    $.ajax({
        type: 'get',
        url: '/get-place-info',
        data: app.data,
        dataType: 'json',
        success: function (data) {
            console.log(data);
            //$('#nearest-places').html(html);
        },
        error: function (data) {
            console.log(data);
        }
    });
}

// https://www.freecodecamp.org/news/javascript-debounce-example/
function debounce(func, timeout = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            func.apply(this, args);
        }, timeout);
    };
}


jQuery(document).ready(function ($) {
    $(function () {

        $("#slider")
            .slider({
                change: function (event, ui) {
                    $('#range').val(ui.value);
                    app.handlers.getDataFromDom();
                    anyUiChangeHandler();
                }
            }).slider("value", app.data.range);

        $('#range').on('input', function () {
            app.data.range = ($(this).val());
            $("#slider").slider("value", app.data.range);
        });

        $('#place-selector').change(function (event) {
            app.handlers.getDataFromDom();
            anyUiChangeHandler();
        });

    });

    // CREATE
    $("#btn-save").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
    });
});
