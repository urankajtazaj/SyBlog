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


    function changeVote(btn) {

        var comment = $(btn).data("cid");
        var type = $(btn).data("type");
        var uid = $(btn).data("vid");
        var pid = $(btn).data("pid");

        var newUrl = "/upvotes/" + comment + "/" + type + "/" + uid + "/" + pid;

        $.ajax({
            method: "GET",
            url: newUrl,
            success: function(data) {
                var count = $("#count-" + comment);
                $(count).text(data['count']);
                $("#votes-" + comment + " a").removeClass('active');

                if (data['type'] != 0)
                    $(btn).addClass('active');
                
                if (data['type'] == 1) {
                    $(".votes#votes-" + comment + " a:first-of-type").data("type", 1);
                } else if (data['type'] == -1) {
                    $(".votes#votes-" + comment + " a:last-of-type").data("type", -1);
                }
            }
        });
    }


    $(".votes").each(function(i, el) {
        var el = $(el);

        var btnUp = $("a:first-of-type", this);
        var btnDown = $("a:last-of-type", this);

        btnUp.click(function(e) {
            e.preventDefault();
            changeVote(this);
        });

        btnDown.click(function(e) {
            e.preventDefault();
            changeVote(this);
        });

    });

});


// Show popup
function showPopup() {
    $(".pop-up").show();
}