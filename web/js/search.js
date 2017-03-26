$(function () {
    $("#suggest").autocomplete({
        source: function (request, response) {
            $.getJSON("/videos.json", function (data) {
                var d = $.map(data, function (value, key) {
                    return {
                        label: value.title,
                        value: value.url
                    };
                });
                response(d);
            });
        },
        minLength: 2,
        delay: 100,
        select: function(event, ui) {
            window.location = ui.item.value;
        }
    });
});