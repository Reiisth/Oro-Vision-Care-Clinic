document.addEventListener("DOMContentLoaded", () => {

    const provinceSelect = document.getElementById("province");
    const citySelect = document.getElementById("city");
    const barangaySelect = document.getElementById("barangay");

    let addressData = {};

    // Load JSON
    fetch("../ph-address/ph-address.json")
        .then(res => res.json())
        .then(data => {
            addressData = data;
            loadProvinces(data);
        });


    // Load province dropdown
    function loadProvinces(data) {
        provinceSelect.innerHTML = `<option value="">Select Province</option>`;

        Object.values(data).forEach(region => {
            const provinces = region.province_list;

            Object.keys(provinces).forEach(provinceName => {
                const option = document.createElement("option");
                option.value = provinceName;
                option.textContent = provinceName;
                provinceSelect.appendChild(option);
            });

        });
    }


    // When province selected → load cities
    provinceSelect.addEventListener("change", () => {
        const selectedProvince = provinceSelect.value;

        citySelect.innerHTML = `<option value="">Select City / Municipality</option>`;
        barangaySelect.innerHTML = `<option value="">Select Barangay</option>`;

        Object.values(addressData).forEach(region => {
            const provinces = region.province_list;

            if (provinces[selectedProvince]) {
                const municipalities = provinces[selectedProvince].municipality_list;

                Object.keys(municipalities).forEach(muniName => {
                    const option = document.createElement("option");
                    option.value = muniName;
                    option.textContent = muniName;
                    citySelect.appendChild(option);
                });
            }
        });
    });


    // When city/municipality selected → load barangays
    citySelect.addEventListener("change", () => {
        const selectedProvince = provinceSelect.value;
        const selectedCity = citySelect.value;

        barangaySelect.innerHTML = `<option value="">Select Barangay</option>`;

        Object.values(addressData).forEach(region => {
            const province = region.province_list[selectedProvince];

            if (province) {
                const municipalities = province.municipality_list;

                if (municipalities[selectedCity]) {
                    const barangays = municipalities[selectedCity].barangay_list;

                    barangays.forEach(brgy => {
                        const option = document.createElement("option");
                        option.value = brgy;
                        option.textContent = brgy;
                        barangaySelect.appendChild(option);
                    });
                }
            }
        });

    });

});

function preSelectAddress() {
    const provinceSelect = document.getElementById("provinceSelect");
    const citySelect = document.getElementById("citySelect");
    const barangaySelect = document.getElementById("barangaySelect");

    if (!provinceSelect) return; // Not on this page

    const currentProvince = provinceSelect.dataset.current;
    const currentCity = citySelect.dataset.current;
    const currentBarangay = barangaySelect.dataset.current;

    // Step 1: Set the province
    if (currentProvince) {
        provinceSelect.value = currentProvince;
        populateCities(currentProvince); // load related cities
    }

    // Step 2: Set the city
    if (currentCity) {
        citySelect.value = currentCity;
        populateBarangays(currentProvince, currentCity);
    }

    // Step 3: Set the barangay
    if (currentBarangay) {
        barangaySelect.value = currentBarangay;
    }
}



function populateCities(selectedProvince) {
    const event = new Event("change");
    document.getElementById("provinceSelect")?.dispatchEvent(event);
}

function populateBarangays(selectedProvince, selectedCity) {
    const event = new Event("change");
    document.getElementById("citySelect")?.dispatchEvent(event);
}


// RUN AFTER LOADING ADDRESS DATA
setTimeout(() => {
    const province = document.getElementById("province");
    const city = document.getElementById("city");
    const barangay = document.getElementById("barangay");

    if (!province) return;

    const cp = province.dataset.current;
    const cc = city.dataset.current;
    const cb = barangay.dataset.current;

    if (cp) {
        province.value = cp;
        province.dispatchEvent(new Event("change")); // Load cities
    }

    setTimeout(() => {
        if (cc) {
            city.value = cc;
            city.dispatchEvent(new Event("change")); // Load barangays
        }

        setTimeout(() => {
            if (cb) barangay.value = cb;
        }, 150);
    }, 150);

}, 300);

