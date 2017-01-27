$( document ).ready(function() {
    // When the user presses the edit button
    $( "#editButton" ).click(function() {
        $("#existingProductForm :input").prop("disabled", false); // enable all of the form fields (so they can be editted)
        $( "#saveButton" ).show(); // Show the save button
        $('#existingProduct').modal(); // Display the data form modal
    });
    // When the user presses the edit button
    $( "#viewButton" ).click(function() {
        $("#existingProductForm :input").prop("disabled", true);// disable all of the form fields (so they can't be editted)
        $( "#saveButton" ).hide(); // Show the save button
        $('#existingProduct').modal(); // Display the data form modal
    });
    // When the user presses the new product button
    $( "#newProductButton" ).click(function() {
        $('#newProduct').modal(); // Display the blank data form modal
    });
    // When the user presses the delete product button
    $( "#deleteButton" ).click(function() {
        $('#deleteProduct').modal(); // Display the delete confimation modal
    });
});