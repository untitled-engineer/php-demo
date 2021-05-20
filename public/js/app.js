const app = {
    controllers: {},
    data: {
        place: "",
        range: 42
    }
}

app.controllers.getPlaceInfo = function () {

    console.log(history)
}

// https://www.freecodecamp.org/news/javascript-debounce-example/
function debounce(func, timeout = 300){
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
}

const anyUiChangeHandler = debounce(() => app.controllers.getPlaceInfo());

jQuery(document).ready(function($){
    $( function() {

        $("#slider")
            .slider({
                change: function( event, ui ) {
                    app.data.range = ui.value;
                    $('#range').val(ui.value);
                    anyUiChangeHandler();
                }
            }).slider("value", app.data.range);

        $('#range').on('input', function (){
            app.data.range = ($(this).val());
            $("#slider").slider( "value", app.data.range);
        });

        $('#place-selector').change(function (event) {
            app.data.place = ($(this).val());
            anyUiChangeHandler();
        });

    } );

});
