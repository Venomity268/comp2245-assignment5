document.addEventListener("DOMContentLoaded", function () {
    const resultDiv = document.getElementById("result");

    function fetchData(lookupType) {
        const country = document.getElementById("country").value;
        const xhr = new XMLHttpRequest();

        const url = `world.php?country=${encodeURIComponent(country)}&lookup=${lookupType}`;
        xhr.open("GET", url, true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                resultDiv.innerHTML = xhr.responseText;
            } else {
                resultDiv.innerHTML = `<p>Error fetching data: ${xhr.statusText}</p>`;
            }
        };

        xhr.onerror = function () {
            resultDiv.innerHTML = `<p>An error occurred while trying to fetch the data.</p>`;
        };

        xhr.send();
    }

    document.getElementById("lookup").addEventListener("click", function () {
        fetchData("countries");
    });

    document.getElementById("lookup-cities").addEventListener("click", function () {
        fetchData("cities");
    });
});
