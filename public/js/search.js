const apiUrl = window.AppConfig.apiUrl;

function performCallSignSearch() {
    var callSignInput = document.getElementById('call-sign');
    var searchButton = document.querySelector('#search-form button');
    var loadingIndicator = document.getElementById('loading-indicator');
    var callSign = callSignInput.value.trim();

    loadingIndicator.classList.remove('hidden');
    callSignInput.disabled = true;
    searchButton.disabled = true;

    var xhr = new XMLHttpRequest();
    xhr.open('GET', `${apiUrl}api/search.php?call-sign=` + encodeURIComponent(callSign), true);
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

function performNameSearch() {
    var nameInput = document.getElementById('call-sign');
    var nameSearchButton = document.querySelector('#search-form button');
    var loadingIndicator = document.getElementById('loading-indicator');
    var searchName = nameInput.value.trim();

    loadingIndicator.classList.remove('hidden');
    nameInput.disabled = true;
    nameSearchButton.disabled = true;

    var xhr = new XMLHttpRequest();
    xhr.open('GET', `${apiUrl}api/search.php?name=` + encodeURIComponent(searchName), true);
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
        nameInput.disabled = false;
        nameSearchButton.disabled = false;

    };
    xhr.send();
}

function displayResults(results) {
    var resultsDiv = document.getElementById('search-results');
    var loadingIndicator = document.getElementById('loading-indicator');
    var callSignInput = document.getElementById('call-sign');
    var searchButton = document.querySelector('#search-form button');

    loadingIndicator.classList.add('hidden');
    callSignInput.disabled = false;
    searchButton.disabled = false;

    let history = JSON.parse(localStorage.getItem('searchHistory') || '[]');
    let matchedEntry = null;
    let matchIndex = -1;

    for (let i = 0; i < history.length; i++) {
        const histResults = history[i].results;
        if (histResults.length === results.length) {
            const isFullMatch = histResults.every((histItem, idx) => {
                const newItem = results[idx];
                return Object.keys(histItem).every(key => 
                    key === 'cached' || histItem[key] === newItem[key]
                );
            });
            if (isFullMatch) {
                matchedEntry = history[i];
                matchIndex = i;
                break;
            }
        }
    }

    if (matchedEntry) {
        history.splice(matchIndex, 1);
        localStorage.setItem('searchHistory', JSON.stringify(history));
    } else if (results.length === 0) {
        var separator = document.createElement('div');
        separator.className = 'search-separator';
        separator.textContent = `Search for "${callSignInput.value}" - ${new Date().toLocaleString()}`;
        resultsDiv.insertBefore(separator, resultsDiv.firstChild); 

        var p = document.createElement('p');
        p.textContent = 'No results found';
        resultsDiv.insertBefore(p, resultsDiv.firstChild); 

        saveToHistory({ separator: separator.textContent, results: [] });
        return;
    }

    results.slice().reverse().forEach(function(result) {
        var box = document.createElement('div');
        box.className = 'result-box';
        var cacheIndicator = result.cached ? '<span class="cached">💾</span>' : '';
        box.innerHTML = `
            <div class="callsign">${result.call_sign} ${cacheIndicator}</div>
            <strong>Name:</strong> ${result.entity_name}<br>
            <strong>Address:</strong> ${result.street_address}, ${result.city}, ${result.state} ${result.zip_code}<br> 
            <strong>Class:</strong> ${result.operator_class}<br>
            <strong>Granted:</strong> ${result.grant_date} / <strong>Expires:</strong> ${result.expired_date}<br>
        `;
        resultsDiv.insertBefore(box, resultsDiv.firstChild);
    });

    var separator = document.createElement('div');
    separator.className = 'search-separator';
    separator.textContent = `Search for "${callSignInput.value}" - ${new Date().toLocaleString()}`;
    resultsDiv.insertBefore(separator, resultsDiv.firstChild);

    saveToHistory({ results: results, separator: separator.textContent});
}

function saveToHistory(searchData) {
    let history = JSON.parse(localStorage.getItem('searchHistory') || '[]');
    history.unshift(searchData); 
    localStorage.setItem('searchHistory', JSON.stringify(history));
}

function clearHistory() {
    var resultsDiv = document.getElementById('search-results');
    resultsDiv.innerHTML = ''; 
    localStorage.removeItem('searchHistory'); 
    var p = document.createElement('p');
    p.textContent = 'Search history cleared.';
    resultsDiv.insertBefore(p, resultsDiv.firstChild);
}

function loadSearchHistory() {
    var resultsDiv = document.getElementById('search-results');
    let history = JSON.parse(localStorage.getItem('searchHistory') || '[]').reverse(); 

    history.forEach(function(searchData) {
        // Add separator first
        
        // Then add results (if any)
        if (searchData.results.length === 0) {
            var p = document.createElement('p');
            p.textContent = 'No results found';
            resultsDiv.insertBefore(p, resultsDiv.firstChild); 
        } else {
            searchData.results.slice().reverse().forEach(function(result) {
                var box = document.createElement('div');
                box.className = 'result-box';
                box.innerHTML = `
                    <div class="callsign">${result.call_sign}</div>
                    <strong>Name:</strong> ${result.entity_name}<br>
                    <strong>Address:</strong> ${result.street_address}, ${result.city}, ${result.state} ${result.zip_code}<br> 
                    <strong>Class:</strong> ${result.operator_class}<br>
                    <strong>Granted:</strong> ${result.grant_date} / <strong>Expires:</strong> ${result.expired_date}<br>
               `;
                resultsDiv.insertBefore(box, resultsDiv.firstChild); 
            });
        }
        var separator = document.createElement('div');
        separator.className = 'search-separator';
        separator.textContent = searchData.separator;
        resultsDiv.insertBefore(separator, resultsDiv.firstChild); 

    });
}

window.onload = function() {
    loadSearchHistory();
};

