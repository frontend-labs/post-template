# post-template
Template for post in [frontendlabs.io](http://frontendlabs.io/)

## Clone

```
git clone https://github.com/frontend-labs/post-template.git
```

## Install

```
sudo npm install -d
```


## Ready!

Open *jade/_config.jade* and update it with your post data 

```jade
- var post = {}
- post.title           = 'titulo'
- post.date            = '17 enero, 2016'
- post.author          = 'Jan Sanchez'
```

Then open *jade/post.jade* and start making your new post!

```jade
extends ./jade/_layout.jade
block post
	//- aquí empieza el contenido del post
	h2 titulo de sección
	p contenido de un parrafo
	p texto y un enlace 
		a(href="http://frontendlabs.io/") Frontendlabs.io
	p texto que contiene el nombre de una 
		span(class="inline_folder") carpeta de un 
		span(class="inline_file") archivo y de un 
		span(class="inline_code") codigo
	pre(class="prettyprint lang-js")
		code.
			var hola = 'ejemplo de string';
```

## Watch

```bash
watch -n 1 node index.js
```

## Does it work?

```bash
google-chrome output/post.html
```

![it works](themes/general/img/post.png)


## Enjoy it!
