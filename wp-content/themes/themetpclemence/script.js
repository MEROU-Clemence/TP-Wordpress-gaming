function redirectOnChange(select) {
    var selectedOption = select.options[select.selectedIndex];
    var link = selectedOption.getAttribute("data-link");

    if (link && link !== "#") {
        window.location.href = link;
    }
}