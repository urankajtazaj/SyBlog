$(document).ready(function() {

    $(".btn-delete").each(function(i, el) {
        var el = $(el);
    
        el.click(function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            var url = $(this).data("url") + id;

            if (confirm("Are you sure you want to execute this action?")) {
                location.href = url
            }
        })
    
    });

    $(".pop-up #close").click(function(e) {
        e.stopPropagation();
        $(".pop-up").hide();
    });

});


// Show popup
function showPopup() {
    $(".pop-up").show();
}