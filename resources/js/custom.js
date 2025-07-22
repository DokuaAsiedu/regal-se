window.initIntlTelInput = function (elem_id) {
    const input = document.getElementById(elem_id);

    if (input) {
        const iti = window.intlTelInput(input, {
            initialCountry: "auto",
            geoIpLookup: (callback) => {
                fetch("https://ipinfo.io")
                    .then((res) => res.json())
                    .then((data) => callback(data.country))
                    .catch(() => callback("gh"));
            },
            loadUtils: () => import("intl-tel-input/utils"),
            autoPlaceholder: "aggressive",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
            customPlaceholder: (selectedCountryPlaceholder) => {
                return selectedCountryPlaceholder;
            },
        });

        return iti;
    }
};

window.addEventListener('delay-reload', function (event) {
    const delay = event.detail.delay || 3000;
    window.setTimeout(function () {
        console.log('delaying', delay)
        window.location.reload()
    }, delay)
})
