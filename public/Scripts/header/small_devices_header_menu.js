/**
 * When on small devices, a menu button appears on the top left.
 * Code below lets a dropdown menu display below it on click.
 * 
 */
// Menu icon
document.querySelector('.col-sm-12.col-md-3').addEventListener('click', () => 
{
    // Toggles nav menu display
    document.querySelector('.nav').classList.toggle('active');
});
