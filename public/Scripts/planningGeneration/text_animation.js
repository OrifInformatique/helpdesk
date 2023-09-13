function textAnimation()
{
    // Get text
    const text = document.getElementById('waiting-text')
    let content = text.textContent

    if (content.endsWith('....'))
    {
        // Removes 4 chars from content
        content = content.slice(0, -4)
    }

    else
    {
        // Add a dot at the end of text
        content += '.'
    }

    text.textContent = content
}

// Calls the textAnimation function each 500ms
const updateAnimation = setInterval(textAnimation, 250)
