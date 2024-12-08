let request = new XMLHttpRequest();
function processData(){
    "use strict";
    if(request.readyState === XMLHttpRequest.DONE && request.status === 200){
        if(request.responseText != null){
            const data = JSON.parse(request.responseText);
            process(data);
        }
        else {
            console.error('Dokument ist leer');
        }
    }
    else {
        console.error('Uebertragung fehlgeschlagen');
    }
}


function requestData(){
    "use strict";
    request.open("GET", "KundenStatus.php")
    request.onreadystatechange = processData;
    request.send(null);
}

function process(jsonData) {
    "use strict";
    let statusSection = document.getElementById('status_section');

    // Clear any existing content in the status container
    while (statusSection.firstChild) {
        statusSection.removeChild(statusSection.firstChild);
    }

    if (jsonData.length === 0) {
        const noPizzaMessage = document.createElement("p");
        noPizzaMessage.textContent = "No pizzas available.";
        statusSection.appendChild(noPizzaMessage);
        return;
    }

    //Make paragraph for order id
    const orderId = document.createElement("h2");
    orderId.textContent = "Bestellung: "+jsonData[0].ordering_id;
    statusSection.appendChild(orderId);

    jsonData.forEach(statusObj => {
        // const statusElement = document.createElement("div");
        // statusElement.classList.add("status");

        const articleName = document.createElement("p");
        articleName.textContent = "Pizza: " + statusObj.name;
        statusSection.appendChild(articleName);

        for (let i = 0; i < 5; i++) {
            const radio = document.createElement("input");
            radio.type = "radio";
            radio.name = "status_"+statusObj.ordered_article_id;
            radio.value = i;
            statusSection.appendChild(radio);

            const label = document.createElement("label");
            if(i === 0) {
                label.textContent = "bestellt";
            } else if(i === 1) {
                label.textContent = "im Ofen";
            } else if(i === 2) {
                label.textContent = "fertig";
            } else if(i === 3) {
                label.textContent = "unterwegs";
            } else if(i === 4) {
                label.textContent = "geliefert";
            }
            statusSection.appendChild(label);
            // Add spacing between radio buttons
            statusSection.appendChild(document.createTextNode(" "));

            // Check the radio button that matches the current status
            if (statusObj.status == i) {
                radio.checked = true;
            }
            radio.disabled = true;
        }
        // statusSection.appendChild(statusElement);
    });
}
window.onload = function() {
    requestData();
    setInterval(requestData, 2000);
};