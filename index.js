const convertButton = document.getElementById('reduce');
const input = document.getElementById('uri');
convertButton.addEventListener('click', e => {
    let uri = input.value;
    let isValidUri = uriValidator(uri);
    if (isValidUri) {
        return this.createShortLink(uri);
    }
    alert('Ошибка валидации ссылки')
})

async function createShortLink(uri) {
    let response = await fetch('/links', {method: 'POST', body: new URLSearchParams({uri: uri})});
    let content = await response.json();
    this.renderShortLink(content.data[0]['short_url']);
}

function renderShortLink(shortUrl) {
    const shortLinkBox = document.getElementById('shortLink');
    const shortLinkFragment = document.createElement('a');
    shortLinkFragment.setAttribute('href', shortUrl);
    let location = window.location;
    shortLinkFragment.textContent = location.host + location.pathname + '/' + shortUrl;
    shortLinkBox.appendChild(shortLinkFragment);
}

const uriValidator = urlString => {
    let urlPattern = new RegExp('^(https?:\\/\\/)?' + // validate protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // validate domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))' + // validate OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // validate port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?' + // validate query string
        '(\\#[-a-z\\d_]*)?$', 'i'); // validate fragment locator
    return !!urlPattern.test(urlString);
}