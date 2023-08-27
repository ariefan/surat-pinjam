// zmauto.js
// version 1.0.4
// created by zmachmobile
(function ( $ ) {
    $.fn.zmauto = function(options) {
        // This is the easiest way to have default options.
        var settings = $.extend({
            // These are the defaults.
            // color: "#556b2f",
            //backgroundColor: "#00AAD4",
        }, options );

        // Greenify the collection based on the settings variable.
        // return this.css({
            // color: settings.color,
            // backgroundColor: settings.backgroundColor
        // });
        this.on('keyup', debounce(function () {
            zmauto(this)
        }, 500));
        this.ready(function(){
            zmautoready();
        });
        var id=$(this).attr('id');
        var value=$(this).val();
        var name=$(this).attr('name');
        var defaultvalue=$(this).attr('default-value');

        $(this).attr('autocomplete','off');
        $(this).after('<div id="zmauto-div-'+id+'" class="zmauto-div"></div><div id="zmauto-hidden-'+id+'"></div>');
        $('#zmauto-div-'+id).css('width',$(this).width()+25);

        function debounce(func, wait, immediate) {
            var timeout;
            return function () {
                var context = this, args = arguments;
                var later = function () {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        };

        function zmauto(elemen) {
            var noSpace=elemen.value;
            noSpace=noSpace.replace(' ','_');
            $('#zmauto-div-' + id).html('');

            if (elemen.value=='') {
                $('#zmauto-div-'+id).css('display','none');
            } else {
                $('#zmauto-div-'+id).css('display','block');
            };

            $.ajax({
                url : settings.url+'/?q='+noSpace,
                success: function (data) {
                    $.each(data, function(index, parsed) {
                        $('#zmauto-div-'+id).append('<li onclick="zmauto_select('+parsed.key+',\''+parsed.value+'\','+elemen.id+')">'+parsed.value+'</li>');
                    });
                }
            });
        };

        function zmautoready(){
            $('#zmauto-hidden-'+id).html('<input type="hidden" name="'+name+'" value="'+value+'">');
            $('#'+id).val(defaultvalue);
        };

        // return console.log(settings.backgroundColor);
    };
}( jQuery ));

function zmauto_select(key, value, elemen) {
    var id=$(elemen).attr('id');
    $('#zmauto-div-'+id).html('');
    $(elemen).val(value);
    $('#zmauto-hidden-'+id).html('<input type="hidden" name="'+elemen.id+'" value="'+key+'">');
    $('#zmauto-div-'+id).css('display','none');
};
