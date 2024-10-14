/*=============================================
LOAD DYNAMIC PRODUCTS TABLE
=============================================*/

var hiddenProfile = $('#hiddenProfile').val();

$('.productsTable').DataTable({
    "ajax": "ajax/datatable-products.ajax.php?hiddenProfile=" + hiddenProfile,
    "deferRender": true,
    "retrieve": true,
    "processing": true
});

/*=============================================
ADDING SELLING PRICE
=============================================*/
$("#newBuyingPrice, #editBuyingPrice").change(function () {

    if ($(".percentage").prop("checked")) {

        var valuePercentage = $(".newPercentage").val();

        var percentage = Number(($("#newBuyingPrice").val() * valuePercentage / 100)) + Number($("#newBuyingPrice").val());

        var editPercentage = Number(($("#editBuyingPrice").val() * valuePercentage / 100)) + Number($("#editBuyingPrice").val());

        $("#newSellingPrice").val(percentage);
        $("#newSellingPrice").prop("readonly", true);

        $("#editSellingPrice").val(editPercentage);
        $("#editSellingPrice").prop("readonly", true);
    }

});

/*=============================================
PERCENTAGE CHANGE
=============================================*/
$(".newPercentage").change(function () {

    if ($(".percentage").prop("checked")) {

        var valuePercentage = $(this).val();

        var percentage = Number(($("#newBuyingPrice").val() * valuePercentage / 100)) + Number($("#newBuyingPrice").val());

        var editPercentage = Number(($("#editBuyingPrice").val() * valuePercentage / 100)) + Number($("#editBuyingPrice").val());

        $("#newSellingPrice").val(percentage);
        $("#newSellingPrice").prop("readonly", true);

        $("#editSellingPrice").val(editPercentage);
        $("#editSellingPrice").prop("readonly", true);
    }
});

$(".percentage").on("ifUnchecked", function () {

    $("#newSellingPrice").prop("readonly", false);
    $("#editSellingPrice").prop("readonly", false);

});

$(".percentage").on("ifChecked", function () {

    $("#newSellingPrice").prop("readonly", true);
    $("#editSellingPrice").prop("readonly", true);

});

/*=============================================
UPLOADING PRODUCT IMAGE
=============================================*/
$(".newImage").change(function () {

    var image = this.files[0];

    /*=============================================
    WE VALIDATE THAT THE FORMAT IS JPG OR PNG
    =============================================*/
    if (image["type"] != "image/jpeg" && image["type"] != "image/png") {

        $(".newImage").val("");

        swal({
            title: "Error uploading image",
            text: "¡The image should be in JPG or PNG format!",
            type: "error",
            confirmButtonText: "¡Close!"
        });

    } else if (image["size"] > 2000000) {

        $(".newImage").val("");

        swal({
            title: "Error uploading image",
            text: "¡The image shouldn't be more than 2MB!",
            type: "error",
            confirmButtonText: "¡Close!"
        });

    } else {

        var imageData = new FileReader;
        imageData.readAsDataURL(image);

        $(imageData).on("load", function (event) {

            var imagePath = event.target.result;

            $(".preview").attr("src", imagePath);

        });
    }
});

/*=============================================
EDIT PRODUCT
=============================================*/
$(".productsTable tbody").on("click", "button.btnEditProduct", function () {

    var idProduct = $(this).attr("idProduct");

    var datum = new FormData();
    datum.append("idProduct", idProduct);

    $.ajax({
        url: "ajax/products.ajax.php",
        method: "POST",
        data: datum,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (answer) {
            var categoryData = new FormData();
            categoryData.append("idCategory", answer["idCategory"]);

            $.ajax({
                url: "ajax/categories.ajax.php",
                method: "POST",
                data: categoryData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (answer) {
                    $("#editCategory").val(answer["id"]);
                    $("#editCategory").html(answer["Category"]);
                }
            });

            $("#editCode").val(answer["code"]);
            $("#editDescription").val(answer["description"]);
            $("#editStock").val(answer["stock"]);
            $("#editSellingPrice").val(answer["sellingPrice"]);

            if (answer["image"] != "") {
                $("#currentImage").val(answer["image"]);
                $(".preview").attr("src", answer["image"]);
            }
        }
    });
});

/*=============================================
DELETE PRODUCT
=============================================*/
$(".productsTable tbody").on("click", "button.btnDeleteProduct", function () {

    var idProduct = $(this).attr("idProduct");
    var code = $(this).attr("code");
    var image = $(this).attr("image");

    swal({
        title: '¿Are you sure you want to delete the product?',
        text: "¡If you're not sure you can cancel this action!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Yes, delete product!'
    }).then(function (result) {
        if (result.value) {
            window.location = "index.php?route=products&idProduct=" + idProduct + "&image=" + image + "&Code=" + code;
        }
    });
});


