$(document).ready(function() {
    $(function() {
        var $animation_elements = $('.skillBarContainer > *');

        var $window = $(window);
        // $animation_elements.css({ '-webkit-opacity': '0', '-webkit-transform': 'scaleX(.1)' });
        $window.on('scroll', function() {
            var window_height = $window.height();
            var window_top_position = $window.scrollTop();
            var window_bottom_position = (window_top_position + window_height);

            console.log(window_height)

            $.each($animation_elements, function() {
                var element_height = $(this).outerHeight();
                var element_top_position = $(this).offset().top;
                var element_bottom_position = (element_top_position + element_height);

                if ((element_bottom_position >= window_top_position) && (element_top_position <= window_bottom_position)) {

                    $(this).addClass('skillBarValue');
                };
            });

        });
    });
});

//console.log("second hello")
//console.log("window_top_position: " + window_top_position)
//console.log("element_bottom_position: " + element_bottom_position)
// console.log("first condition: " + (element_bottom_position >= window_top_position))
// console.log("element_top_position: " + element_top_position)
// console.log("window_bottom_position: " + window_bottom_position)
