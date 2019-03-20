# blueways Static Template

## Install

* via composer:

```
composer require blueways/bw-static-template
```

* include static typoscript template
* include static pageTS template

## Usage

* Add plugin to page
* Select fluid template to render (e.g. EXT:your_ext/Resources/Private/Partials/Header.html)
* Optional: Use json to pass data into template

```json
{
"templateMarker1": "Example text data",
"templateMarker2:": ["lorem", "ipsum"]
}
```
* Optional: Select images

Now you can use the given data in your template, e.g. 

```
Hello {templateMarker1}!
```

or

```
<f:for each="{templateMarker2}" as="text">{text}</f:for> 
```

The selected images are accessible as **FileReference** via ```{files}``` marker.
