//
// Adds a default picture if the image cannot be displayed (for each technician)
//
// @param object The <img> object
//
// @return void
//

let img_path = document.getElementById('img-link-data').dataset.imgLink;
let alt_text = 'Photo de technicien par défaut';

function HideImage1(img1)
{
    img1.src = img_path;
    img1.alt = alt_text;
}

function HideImage2(img2)
{
    img2.src = img_path;
    img2.alt = alt_text;
}

function HideImage3(img3)
{
    img3.src = img_path;
    img3.alt = alt_text;
}