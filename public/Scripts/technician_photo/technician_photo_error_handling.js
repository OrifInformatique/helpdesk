/**
 * Adds a default technician photo if there is an error when displaying it.
 *
 * @param {Object} img HTML <img> tag.
 *
 * @returns {void}
 *
 */
function DisplayDefaultTechnicianPhoto(img)
{
    // Get the data to insert the default value
    const img_path = document.getElementById('img-link-data').dataset.imgLink;
    const alt_text = document.getElementById('alt-text-data').dataset.altText;

    // Insert default values
    img.src = img_path;
    img.alt = alt_text;
}