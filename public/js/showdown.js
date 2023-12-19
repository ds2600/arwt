var converter = new showdown.Converter();
text = document.getElementById('markdownContent').innerHTML;
html = converter.makeHtml(text);
document.getElementById('renderedContent').innerHTML = html;
