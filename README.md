<div align="center">

![Extension icon](Resources/Public/Icons/Extension.svg)

# TYPO3 extension `bw_static_template`

![Latest version](https://typo3-badges.dev/badge/bw_static_template/version/shields.svg)
[![Supported TYPO3 versions](https://typo3-badges.dev/badge/bw_static_template/typo3/shields.svg)](https://extensions.typo3.org/extension/bw_static_template)
![Total downloads](https://typo3-badges.dev/badge/bw_static_template/downloads/shields.svg)
[![Composer](https://typo3-badges.dev/badge/bw_static_template/composer/shields.svg)](https://packagist.org/packages/blueways/bw-static-template)

</div>

This TYPO3 extension ships a custom content element that renders every Fluid template. Inject JSON data or FAL files into the templates. Perfect for fast template development.

![Plugin in the TYPO3 Backend](Documentation/Images/Preview.jpg)

## Why?

* Sometimes content is very unlikely to change regularly. It's faster to immediately write a Fluid template than to create a custom content element or adjust an extension to your needs.
* No need to write TCA or TypoScript to get frontend output that can be adjusted through the backend. (E.g. quick image or phone number change)
* If a standalone solution is required, the templates can be reused.

## Install

1. Install via composer:

```bash
composer require blueways/bw-static-template
```

2. Add the site set `blueways/bw-static-template` to your site configuration:

```yaml
# config/sites/<your-site>/config.yaml
sets:
  - blueways/bw-static-template
```

## Usage

Add the content element **Static Template** to a page.

![Content Element Wizard](./Documentation/Images/NewContentElement.png)

### Select a template

Enter a Fluid template path in the **Frontend template** field:

* A template name (e.g. `MyTemplate`) — resolved against the configured `templateRootPaths`
* A full EXT: path (e.g. `EXT:your_ext/Resources/Private/Templates/MyTemplate.html`)

![Backend TCA](./Documentation/Images/TCA.png)

Save & done.

### Optional: Pass data into the template

Enter valid JSON in the **JSON** field:

```json
{
    "templateMarker1": "Example marker data",
    "persons": [
        {
            "name": "Markus Mustermann",
            "contactUid": 3
        },
        {
            "name": "Paul Werner",
            "contactUid": 4
        }
    ]
}
```

Now you can use the given data in your template, e.g.:

```html
Hello {templateMarker1}!

<f:for each="{persons}" as="person">
    Say hello to <f:link.page pageUid="{person.contactUid}">{person.name}</f:link.page>
</f:for>
```

### Optional: Load JSON from file

Toggle **Use database** off to load JSON from a file path instead of the inline editor. Enter a relative or `EXT:` path in the **JSON file path** field. Remote URLs are also supported.

### Optional: Select images

The selected images are accessible as `FileReference` objects via the `{files}` variable:

```html
<f:for each="{files}" as="file">
    <f:image image="{file}" />
</f:for>
```

### Optional: Backend preview template

Enter a template name or `EXT:` path in the **Backend preview** field to render a custom Fluid template in the backend page module instead of the default JSON table view. The same JSON data and file variables are available as in the frontend template.

## Configuration

### Constants

Configure the template root paths so that template names are resolved correctly:

```typoscript
plugin.tx_bwstatictemplate {
    view {
        templateRootPath =
        partialRootPath =
        layoutRootPath =
    }
}
```

### TypoScript

The content element is rendered like any other `lib.contentElement`-based element. Use standard TypoScript to inject additional data:

```typoscript
tt_content.bw_static_template {

    # insert variables
    variables {
        foo = TEXT
        foo.value = bar
    }

    # use DataProcessor (indexes 10 and 20 are reserved)
    dataProcessing {

        # Inject a menu
        30 = TYPO3\CMS\Frontend\DataProcessing\MenuProcessor
        30 {
            as = navigation
            entryLevel = 0
        }

        # Inject data about the current page
        40 = TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor
        40 {
            table = pages
            pidInList = this
            as = page
        }
    }
}
```

## License

This project is licensed under [GNU General Public License 2.0 (or later)](LICENSE.md).

## Contribute

This extension was made by Maik Schneider. Feel free to contribute!

Thanks to [blueways](https://www.blueways.de/) and [XIMA](https://www.xima.de/)!
