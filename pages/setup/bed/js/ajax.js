$(document).ready(function() {
    $("#search-bed-status").submit(function(event) {
        searchBedStatus();
        return false;
    });
});

function searchBedStatus() {
    $.ajax({
        type: "GET",
        url: "search_bed_status.php",
        cache: false,
        data: $('form#search-bed-status').serialize(),
        success: function(response) {
            $("#bed-status-table").html(response);
        },
        error: function() {
            alert("Error");
        }
    });
}