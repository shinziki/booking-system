function editFare(id, from, destination, fare, date) {
    console.log("Edit Fare called with ID:", id);  // Add this for debugging
    document.getElementById('editId').value = id;
    document.getElementById('editFrom').value = from;
    document.getElementById('editDestination').value = destination;
    document.getElementById('editFareAmount').value = fare;
    document.getElementById('editFareDate').value = date;

    document.getElementById('editPopup').style.top = '0';
}

function closeEditPopup() {
    document.getElementById('editPopUp').style.top = '-100%';
}