/*=============================================
EDIT ATTRIBUTE
=============================================*/

$(".tables").on("click", ".btnEditAttribute", function() {
    var idAttribute = $(this).attr("idAttribute");

    var datum = new FormData();
    datum.append("idAttribute", idAttribute);

    $.ajax({
        url: "ajax/attributes.ajax.php", // Update URL for attributes
        method: "POST",
        data: datum,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            // console.log("answer", answer);
            $("#editAttribute").val(answer["Attribute"]); // Update to match attribute field
            $("#editSymbol").val(answer["Symbol"]); // Add a field for symbol
            $("#idAttribute").val(answer["id"]); // Update to match id field
        }
    });
});

/*=============================================
DELETE ATTRIBUTE
=============================================*/
$(".tables").on("click", ".btnDeleteAttribute", function() {
    var idAttribute = $(this).attr("idAttribute");

    swal({
        title: 'Are you sure you want to delete the attribute?',
        text: "If you're not sure you can cancel!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Yes, delete attribute!'
    }).then(function(result) {
        if (result.value) {
            window.location = "index.php?route=attributes&idAttribute=" + idAttribute; // Update the route to attributes
        }
    });
});
