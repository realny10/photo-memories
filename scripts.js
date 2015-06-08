
(function($){

    var getLocalization = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position){
                $('.jQ_getLocalizationInput').val(position.coords.latitude + ',' + position.coords.longitude);
            });
        }
    };

    $('body').on('click', '.jQ_getLocalization', getLocalization);

    getLocalization();

})(jQuery);