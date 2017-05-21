jQuery(document).ready(function($) {
    BSFMouseSmoothScroll();
});
function BSFMouseSmoothScroll() {
    jQuery.srSmoothscroll({
        step: 45,
        speed: 400,
        ease: 'linear',
        target: jQuery('body'),
        container: jQuery(window)
    });
}