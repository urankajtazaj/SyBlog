$(document).ready(function() {

    $(".btn-delete").each(function(i, el) {
        var el = $(el);
    
        el.click(function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            if (confirm("Are you sure you want to execute this action?")) {
                location.href = url
            }
        })
    
    });

    $(".pop-up #close").click(function(e) {
        e.stopPropagation();
        $(".pop-up").hide();
    });

    $(".newWindow").each(function(i, e) {
        var el = $(e);

        el.click(function(event) {
            event.preventDefault();
            window.open($(this).attr("href"), 'Share Post', 'width=700,height=600');
        });
    });

});

// Show popup
function showPopup() {
    $(".pop-up").show();
}