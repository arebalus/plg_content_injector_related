# Injector Related

Injector Related (_plg_content_injector_related_) is a **plugin for Joomla!™** that injects the content articles with a list of **_related articles in an advanced way_**.

## Index
- [Description](#description)
- [Download](#download)
- [Installation](#installation)
- [Documentation](#documentation)
  - [General Params](#general-params)
  - [Placement Params](#placement-params)
  - [Advanced Params](#advanced-params)
  - [Override Configuration](#override-configuration)
    - [Syntax](#syntax)
    - [Remarks](#remarks)
    - [Attributes Table](#attributes-table)
  - [Override Design](#override-design)
  - [Translations](#translations)
    - [Sharing Your Translations](#sharing-your-translations)
- [Support](#support)
- [Contributions](#contributions)
- [Credits](#credits)

## Description 

You might want to add the related articles **at top or bottom** of the article text and this is really simple and can be done with Injector Related. But, what about a decent placement in the middle of the article?

Well, the **Injector Related** plugin, counts the **HTML tags** for paragraphs _(`<p>...</p>`)_ OR divisions _(`<div>...</div>`)_ within the article text and can automatically place the list at the center of such tags.

Do you want to place the related articles one (_or up to 10_) tags above the end or below the start of the article? No problem! Injector Related will do the job.

You can too set which categories will the joomla's related articles will be placed automatically.

**No keywords?** Usually related content articles are picked by keywords, so if you have not set this for a large amount of articles, then you should have an alternate way to get more articles. So, Injector Related, can be configured to get articles with the same tags or from the same category or written by the same author.

Since maybe you want to hide or show manually the related content articles, Injector Related offers too an inline plugin syntax that can be included in each content article and will be parsed by this Joomla plugin to override the global configuration of the plugin.


<br /> [:mag: Back to Index](#index)

## Download

Please feel free to browse and download any of our releases or the master pack. Please notice that master pack may not be ready for production.

[:arrow_right: **View Releases**](../../releases)


<br /> [:mag: Back to Index](#index)

## Installation

**Requirements**
- Joomla! 3.4 or better
- PHP 5.4 or better

**Instructions**

Read the Joomla! documentation on how to [install an extension](https://docs.joomla.org/Installing_an_extension). Since this is a plugin, pay special attention to publish it once it's successfully installed.

**Installation Support**

I have successfully tested the installation of this plugin in a fresh Joomla installation. So, if you are experiencing troubles to install, ask for support in the [Joomla official forums](http://forum.joomla.org/).


<br /> [:mag: Back to Index](#index)

## Documentation

### General Params

- **By Default**: Enable or Disable the plugin. This is different from publish/unpublish because this can be changed by the override syntax. 
- **Articles**: The number of articles the plugin must place at the related content list.
- **1st Relation**: The first relation that an article must have with the shown one in order to be included in the related content list.
- **2nd Relation**: The second relation that an article must have with the shown one in order to be included in the related content list if not enough articles are found by the previous relation. If this parameter is set to None the no further relations will be included.
- **3rd Relation**: The third relation that an article must have with the shown one in order to be included in the related content list if not enough articles are found by the previous relation. If this parameter is set to None the no further relations will be included.
- **4th Relation**: The fourth relation that an article must have with the shown one in order to be included in the related content list if not enough articles are found by the previous relation.


<br /> [:mag: Back to Index](#index)

### Placement Params

- **Categories Filter**: The type of filter to be used to automatically select the content articles of such categories. 
  - If set to _"Show at Selected"_, then the plugin result will automatically be shown in all the content articles belonging to the categories **explicitly** listed in the _Categories List_ parameter.
  - If set to _"Hide from Selected"_ then the plugin result will automatically be shown in all the content articles which DO NOT belong to the categories **explicitly** listed in the in the _Categories List_ parameter
  - If you want to activate the plugin result for all the categories, set this parameter to _"Hide from Selected"_ and left **empty** the _Categories List_ parameter.
- **Categories List**: The explicit list of categories on which the plugin will be shown or hidden (as set in the _Categories Filter_ parameter)
- **Element**: The HTML tag to count in order to find out the right placement for the plugin result, regardless of their content length.
  - Use _"Paragraph"_ if most of your content articles use `<p>...</p>` to organize their content blocks. In this case Class paramenter is irrelevant and will be hidden from the configuration screen.
  - Use _"Division"_ if most of your content articles use `<div>...</div>` to organize their content blocks. If you use **nested divisions**, like in the case of bootstrap, which uses them to generate a grid, then you should want to set the _Class_ parameter.
- **Class**: Since the divisions `<div>...</div>` can be nested within the HTML code, then it could be helpful to count only certain ones. So you can set the class of the divisions that will be counted to determine the right placement of the plugin result. 
- **Location**: Set where in the content article text will be placed the plugin result. You can select _"Top of Article"_, _"Bottom of Article"_ or _"Middle of Article"_. Regarding _"Middle of Article"_, you may use the _"Position"_ paramenter in order to set more specifically the point of the article text to place the plugin result.
- **Position**: If the Location parameter is set to Middle, then you may want to fine-tune where in the middle of the content is placed the related articles list. Notice that Injector Related counts the number of HTML tags regardless of the ammount of text they may contain.
  - Center: the plugin will calculate the half of the HTML tags occurrence and inject the related articles list at such point.
  - After Xth element from top: the plugin will count the HTML tags starting from the top of the article and will inject the related articles list after such element. In case there are not enough elements, then the output will be placed after the last element.
  - Before Xth element from bottom: the plugin will count the HTML tags starting from the bottom of the article and will inject the related articles list before such element. In case there are not enough elements, then the output will be placed before the first element.


<br /> [:mag: Back to Index](#index)

### Advanced Params

- **Layout**: Change the appearance of the plugin output choosing one of the available layouts.


<br /> [:mag: Back to Index](#index)

### Override Configuration

The global configuration set in the plugin configuration screen, may be overridden in a "per-article basis". 

#### Syntax

To do so, you must insert the plugin syntax at any point within the article content, preferably after the "read more" mark, as follows:

`{injector_related attribute=value[ attribute=value[ attribute=value[...]]]}`

#### Remarks

- the curly brackets {} must be present at the start and at the end of the instruction,
- there must **not** be any space between the opening curly bracket and the _injector_related_ keyword,
- there must be a space after _injector_related_,
- there must be at least one pair of _attribute_ and _value_ settings,
- there must **not** be any space between the _attribute_ name and the _equal sign_, same for the _equal sign_ and the _value_,
- there must **not** be any spaces in the _value_
- if more that one pair of _attribute_ and _value_ are set, then a space must separate them from the previous pair.

#### Attributes Table
Use the table below to identify the _attribute_ name for each parameter and its valid _values_:

<table style="width:100%;">
  <tr>
    <th colspan="3">General Params</th>  
  </tr>
  <tr>
    <th>Param</th>  
    <th>Attribute</th>  
    <th>Valid values</th>  
  </tr>
  <tr>
    <td>By Default</td>
    <td><em>enable</em></td>
    <td><em>0</em> (zero) for disabled<br />
        <em>1</em> (one) for enabled</td>
  </tr>
  <tr>
    <td>Articles</td>
    <td><em>number</em></td>
    <td>Any integer between 1 and 5</td>
  </tr>
  <tr>
    <td>1st Relation</td>
    <td><em>relation1</em></td>
    <td>
        <em>key</em> - By Keywords <br />
        <em>tag</em> - By Tags <br />
        <em>cat</em> - By Category <br />
        <em>aut</em> - By Author <br />
    </td>
  </tr>
  <tr>
    <td>2nd Relation</td>
    <td><em>relation2</em></td>
    <td>
        <em>non</em> - None (disabled) <br />
        <em>key</em> - By Keywords <br />
        <em>tag</em> - By Tags <br />
        <em>cat</em> - By Category <br />
        <em>aut</em> - By Author <br />
    </td>
  </tr>
  <tr>
    <td>3rd Relation</td>
    <td><em>relation3</em></td>
    <td>
        <em>non</em> - None (disabled) <br />
        <em>key</em> - By Keywords <br />
        <em>tag</em> - By Tags <br />
        <em>cat</em> - By Category <br />
        <em>aut</em> - By Author <br />
    </td>
  </tr>
  <tr>
    <td>4th Relation</td>
    <td><em>relation4</em></td>
    <td>
        <em>non</em> - None (disabled) <br />
        <em>key</em> - By Keywords <br />
        <em>tag</em> - By Tags <br />
        <em>cat</em> - By Category <br />
        <em>aut</em> - By Author <br />
    </td>
  </tr>
  <tr>
    <th colspan="3">Placement Params</th>  
  </tr>
  <tr>
    <th>Param</th>  
    <th>Attribute</th>  
    <th>Valid values</th>  
  </tr>
  <tr>
    <td>Categories Filter</td>
    <td><em>filter</em></td>
    <td>IGNORED because once this instruction is found the plugin will be enabled/disabled (as definedset by the <b>By Default</b> parameter set at the plugin global configuration or overwritten by this instruction) regardless of the category of the content article.</td>
  </tr>
  <tr>
    <td>Categories List</td>
    <td><em>categories</em></td>
    <td>IGNORED because once this instruction is found the plugin will be enabled/disabled (as definedset by the <b>By Default</b> parameter set at the plugin global configuration or overwritten by this instruction) regardless of the category of the content article.</td>
  </tr>
  <tr>
    <td>Element</td>
    <td><em>mode</em></td>
    <td><em>p</em> - Paragraph <br />
        <em>div</em> - Division <br />
    </td>
  </tr>
  <tr>
    <td>Class</td>
    <td><em>class</em></td>
    <td>any string <b>without</b> spaces</td>
  </tr>
  <tr>
    <td>Location</td>
    <td><em>location</em></td>
    <td><em>top</em> - Top of Article<br />
        <em>bot</em> - Bottom of Article<br />
        <em>mid</em> - Middle of Article<br />
    </td>
  </tr>
  <tr>
    <td>Position</td>
    <td><em>position</em></td>
    <td><em>center</em> - Centered<br />
        <em>1</em> - After 1st element from top<br />
        <em>2</em> - After 2nd element from top<br />
        <em>3</em> - After 3rd element from top<br />
        <em>4</em> - After 4th element from top<br />
        <em>5</em> - After 5th element from top<br />
        <em>6</em> - After 6th element from top<br />
        <em>7</em> - After 7th element from top<br />
        <em>8</em> - After 8th element from top<br />
        <em>9</em> - After 9th element from top<br />
        <em>10</em> - After 10th element from top<br />
        <em>-1</em> - Before 1st element from bottom<br />
        <em>-2</em> - Before 2nd element from bottom<br />
        <em>-3</em> - Before 3rd element from bottom<br />
        <em>-4</em> - Before 4th element from bottom<br />
        <em>-5</em> - Before 5th element from bottom<br />
        <em>-6</em> - Before 6th element from bottom<br />
        <em>-7</em> - Before 7th element from bottom<br />
        <em>-8</em> - Before 8th element from bottom<br />
        <em>-9</em> - Before 9th element from bottom<br />
        <em>-10</em> - Before 10th element from bottom<br />
    </td>
  </tr>
  <tr>
    <th colspan="3">Advanced Params</th>  
  </tr>
  <tr>
    <th>Param</th>  
    <th>Attribute</th>  
    <th>Valid values</th>  
  </tr>
  <tr>
    <td>Layout</td>
    <td><em>layout</em></td>
    <td>a valid layout file name (i.e. <em>default.php</em>)</td>
  </tr>
</table>


<br /> [:mag: Back to Index](#index)

### Override Design

Since the Injector Related is a content plugin, I'm sure you may want to customize its appearance at the point it renders the related articles list. So, yes, the plugin is ready for layout overrides and you may follow the instructions at the Joomla documentation on [Layout Overrides](https://docs.joomla.org/Layout_Overrides_in_Joomla#Plugin_Alternative_Layouts_.28Overriding_a_Plugin.29) to override the default template.


<br /> [:mag: Back to Index](#index)

### Translations

If not included, Injector Related can be fully translated to any language that your Joomla website has installed.

In accordance with the Joomla documentation regarding [Language  Files Conventions](https://docs.joomla.org/Specification_of_language_files#Language_file_naming_conventions_and_precedence) and the [Language Guidelines for 3rd Party Extensions](https://docs.joomla.org/Language_Guidelines_for_3rd_Party_Extensions), all the plugin language files are stored "local" within the plugin foolder, in example:

`JOOMLA_ROOT/plugins/content/injector_related/language`

To create a new translation, create the new folder xx-XX (_this is the language code for your translation_) within the language folder as follows:
```
JOOMLA_ROOT/plugins/content/injector_related/language/xx-XX
```

Create the **new language files** like:
```
JOOMLA_ROOT/plugins/content/injector_related/language/xx-XX/xx-XX.plg_content_injector_related.ini
JOOMLA_ROOT/plugins/content/injector_related/language/xx-XX/xx-XX.plg_content_injector_related.sys.ini
```

Then you can copy the content of both files from the originals at:
```
JOOMLA_ROOT/plugins/content/injector_related/language/en-GB/en-GB.plg_content_injector_related.ini
JOOMLA_ROOT/plugins/content/injector_related/language/en-GB/en-GB.plg_content_injector_related.sys.ini
```
Once you have copied the content of the original files to the **new language files**, you can translate them and save your changes. Now, you can see the plugin in your own language.

#### Sharing Your Translations

If you have translated the plugin to your own language and you want to share your translation, please contact me as stated in the [Support](#support) area. I would be glad to credit your work at the [Credits](#credits) section of this documentation.


<br /> [:mag: Back to Index](#index)

## Support

Please notice that this is a FREE (_gratis_) plugin. So, I can not commit to provide timely support on the plugin usage. 

**However:**

- You may contact me at arebalus \[at\] `gmail` \[dot\] com, but don't spect premium level support.
- If you think you have **detected a bug**, please feel free to [open an issue](../../issues) and I **wll try** to solve ASAP.


<br /> [:mag: Back to Index](#index)

## Contributions

If the Injector Related plugin is of help for you and you feel generous, you may make a donation of whatever amount you want. I will be greatly grateful and I will buy :coffee: to keep developing (or better yet,  :beer: and I will make a toast to you :smiley:).

[:thumbsup: **Contribute Now!**](https://www.paypal.me/racn/)

<br /> [:mag: Back to Index](#index)

## Credits

**Injector Related** was a custom development of [Magnus Arebalus](../../../../arebalus) for [Aportaciones Fiscales](https://www.aportacionesfiscales.com), a Mexican website of Tax related issues.
