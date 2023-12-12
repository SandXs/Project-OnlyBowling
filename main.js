$(document).on("click", ".readBtnInfo", function() {

    if ($(this).text() == "Read More") {

        $(this).text("Read Less");

        // Use a jquery selector using the `.attr()` of the link
        $("#toggle-text-" + $(this).attr("toggle-text")).slideDown();

    } else {

        $(this).text("Read More");

        // Use a jquery selector using the `.attr()` of the link
        $("#toggle-text-" + $(this).attr("toggle-text")).slideUp();

    }


});