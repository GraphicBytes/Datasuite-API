function decodeHTMLEntities(str) {
  if (str && typeof str === 'string') {
    var element = document.createElement('div');
    str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
    str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
    element.innerHTML = str;
    str = element.textContent;
    element.textContent = '';
  }
  return str;
}
