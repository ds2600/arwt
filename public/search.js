function performSearch() {
    var resultsDiv = document.getElementById('search-results');
    resultsDiv.innerHTML = ''; // Clear previous results
    var callSignInput = document.getElementById('call-sign');
    var searchButton = document.querySelector('#search-form button');
    var loadingIndicator = document.getElementById('loading-indicator');
    var callSign = callSignInput.value.trim();

    loadingIndicator.classList.remove('hidden');
    callSignInput.disabled = true;
    searchButton.disabled = true;

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'api/search.php?call-sign=' + encodeURIComponent(callSign), true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var results = JSON.parse(xhr.responseText);
            console.log(results);
            if (results.error) {
                alert(results.error);
            } else {
                displayResults(results);    
            }
        } else {
            console.error('Error in search request');
        }

        loadingIndicator.classList.add('hidden');
        callSignInput.disabled = false;
        searchButton.disabled = false;

    };
    xhr.send();
}

function displayResults(results) {
    var resultsDiv = document.getElementById('search-results');
    var loadingIndicator = document.getElementById('loading-indicator');
    var callSignInput = document.getElementById('call-sign');
    var searchButton = document.querySelector('#search-form button');

    resultsDiv.innerHTML = ''; // Clear previous results
    loadingIndicator.classList.add('hidden');
    callSignInput.disabled = false;
    searchButton.disabled = false;

    if (results.length === 0) {
        var p = document.createElement('p');
        p.textContent = 'No results found';
        resultsDiv.appendChild(p);
        return;
    }

    var ul = document.createElement('ul');

    results.forEach(function(result) {
        var box = document.createElement('div');
        box.className = 'result-box';

        box.innerHTML = `
            <div class="callsign">${result.call_sign}</div>
            <strong>Name:</strong> ${result.entity_name}<br>
            <strong>Address:</strong> ${result.street_address}, ${result.city}, ${result.state} ${result.zip_code}<br> 
            <strong>Class:</strong> ${result.operator_class}<br>
        `;

        resultsDiv.appendChild(box);
    });
}
