function validateReservationForm() {
    const numberOfGuests = document.getElementById("numberOfGuests");
    const specialRequests = document.getElementById("specialRequests");

    // Check if the number of guests is valid
    if (numberOfGuests.value <= 0) {
        alert("Please enter a valid number of guests.");
        return false;
    }

    // Check if special requests are too long
    if (specialRequests.value.length > 200) {
        alert("Special requests must be less than 200 characters.");
        return false;
    }

    return true;
}

function handleSearchFormSubmit(event) {
    const searchTerm = document.getElementById("searchTerm").value.trim();
    if (searchTerm === "") {
        alert("Please enter a search term.");
        event.preventDefault(); // Prevent form submission
    }
}

function clearSearchInput() {
    document.getElementById("searchTerm").value = "";
}

document.addEventListener("DOMContentLoaded", function() {
    const searchForm = document.getElementById("searchForm");
    if (searchForm) {
        searchForm.addEventListener("submit", handleSearchFormSubmit);
    }

    const clearButton = document.getElementById("clearSearchButton");
    if (clearButton) {
        clearButton.addEventListener("click", clearSearchInput);
    }
});
