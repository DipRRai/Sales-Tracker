// Function to add a new dish entry
function addDishEntry() {
    const dishEntry = `
        <div class="dish-entry">
            <label>Dish Name: <input type="text" name="dish_name[]" value="Tonkotsu Ramen" required></label><br>
            <label>Price: <input type="number" name="price[]" step="0.01" value="15.99" required></label><br>
            <label>Description: <input type="text" name="description[]" value="Extra Spicy" required></label><br>
            <button type="button" class="deleteDish">Delete</button><br>
        </div>
    `;
    $('#dishContainer').append(dishEntry);
}

$(document).on('click', '.deleteDish', function() {
const dishEntry = $(this).closest('.dish-entry');
const dishName = dishEntry.find('input[name="dish_name[]"]').val();

if (dishName) {
    $.ajax({
        url: 'delete_dish.php',
        type: 'POST',
        data: { dishName: dishName },
        success: function(response) {
            //alert("Dish Deleted: " + response);
        }
    });
}

dishEntry.remove(); // Remove the dish entry from the DOM
});