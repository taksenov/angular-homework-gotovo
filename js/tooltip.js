/**
 * Created by admin on 02.04.2015.
 */
//$(function () {
//  $('[data-toggle="tooltip"]').tooltip()
//});
$(function() {
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]', // можете использовать любой селектор
        placement: "bottom"
    });
});