document.addEventListener('DOMContentLoaded', function () {
    var url = new URL(document.URL);
    var wrong_password = url.searchParams.get("wrong_password");
    if (wrong_password === "true") {
        document.getElementById('ppw_entire_site_wrong_password').style.display = "block";
    }
});
