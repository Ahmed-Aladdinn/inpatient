$(document).ready(function() {
    $("#search-patient").submit(function(event) {
        submitForm();
        submitForm2();
        // getTime();
        return false;
    });
    // $("#search-ipd").submit(function(event) {
    //     searchIPD();
    //     // getTime();
    //     return false;
    // });

    // $("#inpatientForm").submit(function(event) {
    //     //sendDate();
    // });
});

function sendDate() {
    var datepicker = $('#datepicker').val();
    $.ajax({
        type: "POST",
        url: location.href,
        date: ({ datepicker: datepicker }),
        // data: $('form#inpatientForm').serialize(),

    });
}


function getTime() {
    var d = new Date(),
        h = d.getHours(),
        m = d.getMinutes();
    if (h < 10) h = '0' + h;
    if (m < 10) m = '0' + m;
    var n = h + ':' + m;
    document.getElementById("appointment_time").defaultValue = '18:00';
}

// function searchIPD() {
//     $.ajax({
//         type: "GET",
//         url: "search_ipd.php",
//         cache: false,
//         data: $('form#search-ipd').serialize(),
//         success: function(response) {
//             $("#inpatient-table").html(response);
//         },
//         error: function() {
//             alert("Error");
//         }
//     });
// }



function submitForm() {
    $.ajax({
        type: "POST",
        url: "search_patient.php",
        cache: false,
        data: $('form#search-patient').serialize(),
        success: function(response) {
            $("#inpatient-details").html(response);
        },
        error: function() {
            alert("Error");
        }
    });
}

function submitForm2() {
    $.ajax({
        type: "POST",
        url: "inpatient_admission.php",
        cache: false,
        data: $('form#search-patient').serialize(),
        success: function(response) {
            $("#inpatient-admission").css("display", "block")
            $("#inpatient-admission").html(response);
        },
        error: function() {
            alert("Error");
        }
    });
}