$(document).ready(async function () {
    $("#loading_page").fadeOut(1000);
});

function formatRupiah(value) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value);
}

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
        return true;
    }

    alert("Please enable the location permission!");
    return false;
}

function showPosition(position) {
    let address = {
        latitude: position.coords.latitude,
        longitude: position.coords.longitude,
    };

    return address;
}

function openInputImage(inputId) {
    var input = document.querySelector("#" + inputId);
    input.click();
}

function validateInputImage(input, imgId) {
    if (input.files.length === 0) {
        return false;
    }

    if (input.files[0].size > 10000000) {
        alert("File must not be more than 10 Megabytes.");
        return false;
    }

    var img = document.querySelector("#" + imgId);
    img.src = URL.createObjectURL(input.files[0]);
    return true;
}

function validateBoxImage(input, imgId) {
    if (input.files.length === 0) {
        return false;
    }

    if (input.files[0].size > 10000000) {
        alert("File must not be more than 10 Megabytes.");
        return false;
    }

    let img = document.createElement("img");
    img.className = "w-full h-full object-cover";
    img.src = URL.createObjectURL(input.files[0]);
    $("#" + imgId).html(img);
    return true;
}

function validateBoxVideo(input, videoId) {
    if (input.files.length === 0) {
        return false;
    }

    if (input.files[0].size > 10000000) {
        alert("File must not be more than 10 Megabytes.");
        return false;
    }

    let video = document.createElement("video");
    video.className = "w-full h-full object-cover";
    video.controls = true;
    video.src = URL.createObjectURL(input.files[0]);
    $("#" + videoId).html(video);
    return true;
}
