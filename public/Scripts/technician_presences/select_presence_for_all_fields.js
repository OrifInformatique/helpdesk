/**
 * Changes all the form input values to a specified presence
 * 
 * @param {int} presence Presence selected
 * 
 * @returns {void}
 * 
 */
function ChangeAllFormValues(presence)
{
    // Get the form inputs
    const radios = document.querySelectorAll('input[type="radio"]');

    const radiosGroupedByName = {};

    radios.forEach(radio => 
    {
        const name = radio.getAttribute('name');

        if (!radiosGroupedByName[name])
            radiosGroupedByName[name] = [];

        radiosGroupedByName[name].push(radio)
    });

    const index = presence - 1;

    // Apply the selection in all inputs
    for (const name in radiosGroupedByName) 
    {
        const radioGroup = radiosGroupedByName[name];
        radioGroup[index].checked = true;
    }
}