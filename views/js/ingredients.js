/*=============================================
EDIT INGREDIENT
=============================================*/



/*=============================================
DELETE INGREDIENT
=============================================*/

$(".tables").on("click", ".btnDeleteIngredient", function() {
   var idIngredient = $(this).attr("idIngredient");
    console.log(idIngredient);
    var datum = new FormData();
    datum.append("idIngredient", idIngredient);


    swal({
        title: 'Are you sure you want to delete this ingredient?',
        text: "If you're not sure, you can cancel!",
        icon: 'warning', // Update 'type' to 'icon'
        buttons: {
            cancel: {
                text: 'Cancel',
                value: null,
                visible: true,
                className: '',
                closeModal: true,
            },
            confirm: {
                text: 'Yes, delete ingredient!',
                value: true,
                visible: true,
                className: 'btn-danger',
                closeModal: true // Will close on click
            }
        }
    }).then((willDelete) => {
        if (willDelete) {
            window.location = "index.php?route=ingredients&idIngredient=" + idIngredient; // Ensure this is the correct route
        }
    });
});
