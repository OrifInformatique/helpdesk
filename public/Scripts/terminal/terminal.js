/*
 * Let the user know that a technician is available or not
 * by clicking the technician card
 * 
 * @return void
 * 
 */
// function changeAvailability()
// {   
//     /**
//      * First technician
//      */
//     try
//     {
//         // Get the technician card
//         let technician_1_card = document.querySelector(".technician-1-card")

//         // Get the "unavailable" text
//         let technician_1_unavailable_text = document.querySelector(".technician-1-unavailable-text")
        
//         // Change availability of technician 1 on card click
//         technician_1_card.addEventListener("click", () =>
//         {
//             // Change the card state
//             technician_1_card.classList.toggle("unavailable")

//             // Displays the text
//             technician_1_unavailable_text.classList.toggle("hidden")

//             // Register the state change in sessionStorage
//             let isUnavailable = technician_1_card.classList.contains("unavailable");
//             sessionStorage.setItem("unavailable-1", isUnavailable.toString());

//             noTechniciansCheck()
//         })

//         // After page refresh, if the card was set to unavailable, put the state on.
//         if (sessionStorage.getItem("unavailable-1") === "true") 
//         {
//             technician_1_card.classList.add("unavailable");
//             technician_1_unavailable_text.classList.remove("hidden")
//         }
//     }

//     // Means technician 1 is not assigned to planning
//     catch
//     {
//         sessionStorage.setItem("unavailable-1", "true")
//     }



//     /**
//      * Second technician
//      */
//     try
//     {
//         // Get the technician card
//         let technician_2_card = document.querySelector(".technician-2-card")

//         // Get the "unavailable" text
//         let technician_2_unavailable_text = document.querySelector(".technician-2-unavailable-text")
        
//         // Change availability of technician 2 on card click
//         technician_2_card.addEventListener("click", () =>
//         {
//             // Change the card state
//             technician_2_card.classList.toggle("unavailable")

//             // Displays the text
//             technician_2_unavailable_text.classList.toggle("hidden")

//             // Register the state change in sessionStorage
//             let isUnavailable = technician_2_card.classList.contains("unavailable");
//             sessionStorage.setItem("unavailable-2", isUnavailable.toString());

//             noTechniciansCheck()
//         })

//         // After page refresh, if the card was set to unavailable, put the state on.
//         if (sessionStorage.getItem("unavailable-2") === "true") 
//         {
//             technician_2_card.classList.add("unavailable");
//             technician_2_unavailable_text.classList.remove("hidden")
//         }
//     }

//     // Means technician 2 is not assigned to planning
//     catch
//     {
//         sessionStorage.setItem("unavailable-2", "true")
//     }



//     /**
//      * Third technician
//      */
//     try
//     {
//         // Get the technician card
//         let technician_3_card = document.querySelector(".technician-3-card")

//         // Get the "unavailable" text
//         let technician_3_unavailable_text = document.querySelector(".technician-3-unavailable-text")
        
//         // Change availability of technician 3 on card click
//         technician_3_card.addEventListener("click", () =>
//         {
//             // Change the card state
//             technician_3_card.classList.toggle("unavailable")

//             // Displays the text
//             technician_3_unavailable_text.classList.toggle("hidden")

//             // Register the state change in sessionStorage
//             let isUnavailable = technician_3_card.classList.contains("unavailable");
//             sessionStorage.setItem("unavailable-3", isUnavailable.toString());

//             noTechniciansCheck()
//         })

//         // After page refresh, if the card was set to unavailable, put the state on.
//         if (sessionStorage.getItem("unavailable-3") === "true") 
//         {
//             technician_3_card.classList.add("unavailable");
//             technician_3_unavailable_text.classList.remove("hidden")
//         }
//     }

//     // Means technician 3 is not assigned to planning
//     catch
//     {
//         sessionStorage.setItem("unavailable-3", "true")
//         noTechniciansCheck()
//     }
// }
// changeAvailability()



// /**
//  * Checks if there is no technician available at all.
//  * If it is the case, displays an error message.
//  * 
//  * @return void
//  * 
//  */
// function noTechniciansCheck()
// {
//     try
//     {
//         no_technician = document.getElementById("no-technician-available")

//         // If all 3 technicians are unavailable, displays an error message
//         if(sessionStorage.getItem("unavailable-1") === "true" &&
//             sessionStorage.getItem("unavailable-2") === "true" &&
//             sessionStorage.getItem("unavailable-3") === "true")
//         {
//             no_technician.classList.remove("hidden")
//         }

//         else
//         {
//             no_technician.classList.add("hidden")
//         }
//     }
    
//     catch
//     {
//         console.error("Error occured when testing if there aren't any available technician")
//     }
// }
// noTechniciansCheck()



/*
 * Attempt to do the preview functionality in JS.
 * Code do works but simplier to do it with PHP at the moment.
 * If getting a unique ID from the terminal is possible, 
 * feel free to explore this path.
 */
// let userAgent = navigator.userAgent;
// console.log(userAgent);

// if(userAgent.platform !== 'PlateformeDeLaBorne')
// {
//     let body = document.querySelector("body")

//     // Remove ability to click on the technician sheet
//     let technicianSheets = document.querySelector(".technician-sheet")
//     technicianSheets.style.pointerEvents = "none"
    
//     // Gray foreground to let the user know the page is readonly
//     let previewFilter = document.createElement("div")
//     previewFilter.style.position = "fixed"
//     previewFilter.style.top = "0"
//     previewFilter.style.left = "0"
//     previewFilter.style.width = "100vw"
//     previewFilter.style.height = "100vh"
//     previewFilter.style.backgroundColor = "rgba(185, 185, 185, 0.4)"
//     previewFilter.style.zIndex = "10";
//     previewFilter.style.pointerEvents = "none"
    
//     body.appendChild(previewFilter);
    
//     let previewText = document.createElement("p")
//     previewText.style.position = "fixed"
//     previewText.style.top = "1%"
//     previewText.style.zIndex = "15";
//     previewText.style.width = "fit-content"
//     previewText.style.padding = "10px"
//     previewText.style.borderRadius = "5px"
//     previewText.style.backgroundColor = "rgba(185, 185, 185, 1)"
//     previewText.style.fontSize = "20px"
//     previewText.innerText = "Aperçu"
//     previewText.style.pointerEvents = "none"
    
//     let previewTextDuplicate = previewText.cloneNode(true)
//     previewText.style.left = "1%"
//     previewTextDuplicate.style.right = "1%"

//     body.appendChild(previewText)
//     body.appendChild(previewTextDuplicate)
// }



// Set timer value (in seconds)
let timer = 60

// Select the html element to put the timer
let timerText = document.querySelector(".timer")

// Get the link to reload the page
let reloadPage = document.getElementById('reload-page-data').dataset.reloadPage;

/**
 * Auto-refreshes the page after 60 seconds.
 * Displays and updates a timer on the page.
 * 
 * @return void
 * 
 */
function autoRefresh()
{
    // Displays the timer on the page
    timerText.innerHTML = timer

    timer--

    // If time's up, refesh the page and resets the timer
    if(timer < 0)
    {
        window.location = reloadPage
        clearInterval(interval)
    }
}
autoRefresh()

// Repeats the function every second
let interval = setInterval(autoRefresh, 1000)
