function validateY() {
    element = document.getElementById("y-value");
    y = element.value.replace(',', '.');
    if (!isNumeric(y) || (parseFloat(y) >= 5 && y.split('.')[0] !== "4") || (parseFloat(y) <= -3 && y.split('.')[0] !== "-2")) {
        console.log(y.split('.')[0])
        element.setCustomValidity("Please enter an integer between -3 and 5 (Not inclusive)");
        element.reportValidity();
        return false;
    } else {
        element.setCustomValidity("");
        element.reportValidity();
        return true;
    }

}

function isNumeric(n) {
    const regex = /^-?\d*\.?\d+$/;
    return !isNaN(parseFloat(n)) && isFinite(n) && regex.test(n);
}
