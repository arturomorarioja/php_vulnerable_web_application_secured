# Not so vulnerable web app
Security enhancements to the [Vulnerable Web App]().

## Methods used to prevent security issues

__SQL Injection__
mysqli has been replaced by PDO, and the queries are now prepared.

__XSS__
The JQuery code is now assigning the user input to the `text()` property of the corresponding element instead of the `html()` property. Vanilla JavaScript equivalent: use `innerText` or `textContent` instead of `innerHTML`.

When the PHP API receives the name of the new movie to insert, it trims its and applies `htmlspecialchars()`.

__CSRF__
The JQuery/JavaScript method applied for XSS also works here.

## Tools
MariaDB / PHP8 / JQuery / JavaScript / CSS3 / HTML5

## Author
Arturo Mora-Rioja