# post-template
Template for post in [frontendlabs.io](http://frontendlabs.io/)

## Clone

```
git clone https://github.com/frontend-labs/post-template.git
```

## Dependencies

```
node version >= 4.2.6
```

## Install

```
sudo npm install -d
```

## Does it work?

### Watch

```bash
npm run watch
```

### BrowserSync

```bash
npm run browser
```

## Ready!

Open *pug/_config.pug* and update it with your post data

```pug
- var post = {}
- post.title           = 'titulo'
- post.date            = '17 enero, 2016'
- post.author          = 'Jan Sanchez'
```

Then open *pug/index.pug* and start making your new post!

```pug
extends ./pug/_layout.pug
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


![it works](source/themes/general/img/post.png)

# Docker!

In this file: *docker/compose/supply.yml* change *jan* for your username (whoami)

## Setup
```
make setup
```

## Watch
```
make watch
```
And in another terminal:

## Browser
```
make browser
```

## Enjoy it!
