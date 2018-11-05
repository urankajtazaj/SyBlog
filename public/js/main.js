$(document).ready(function() {


    $(".btn-delete").each(function(i, el) {
        var el = $(el);
    
        el.click(function(e) {
            e.preventDefault();
            var id = $(this).data("id");

            if (confirm("Are you sure you want to delete this post?")) {
                location.href = "/post/delete/" + id
            }
        })
    
    });


});