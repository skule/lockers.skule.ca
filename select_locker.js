$(document).ready(function() {
    $("#sizes").change(function() {
        var size = $("#sizes").val();
        $.ajax({
            url: 'select_locker.php',
            method: 'post',
            data: 'size=' + size
        }).done(function(lockers) {
            console.log(lockers);
            // lockers = JSON.stringify(lockers);
            lockers = JSON.parse(lockers);
            $('#lockers').empty();
            $(lockers).each(function(index, locker) {
                $('#lockers').append('<option>' + locker.locker_id + '</option>');
            }) 
        })
    })
})