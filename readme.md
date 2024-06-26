# Orchard Recovery Center Plugin (orc)

```
Contributors: martin-wedepohl
Tags: ORC
Requires at least: 4.7
Tested up to: 5.4.1
Requires PHP: 5.6
Stable tag: 0.5.7
```

## Description

orc is a gutenburg plugin containing all the blocks and custom post types used by the Orchard Recovery Website.

This project was bootstrapped with [Create Guten Block](https://github.com/ahmadawais/create-guten-block).

## Installation

### Clone the git repository

```bash
git clone git@github.com:martin-at-orchard/ORC-Plugin.git orc
```

### Configure the application

```bash
cd orc
npm install
composer install
```

## Development

### After adding a new class

```
composer dump
```

### If adding new JavaScript for the front end or custom post types

Modify the gulpfile.js to compile and minify the new script then run gulp

```
gulp js
```

### If changing/adding any blocks

In plugin.php change the constant DEVELOPMENT to true

```
npm run start
```

### For production build

In plugin.php change the constant DEVELOPMENT to false

```
npm run build
```

## Frequently Asked Questions (FAQ)

### Where can you get support

If you get stuck, or have questions you can email Martin Wedepohl martin@wedepohlengineering.com or go to https://github.com/martin-at-orchard/ORC-Plugin and open a new Issue.

## Changelog

### 2020-05-11 Version: 0.5.7
* FIX: aria-label in wrong place

### 2020-05-11 Version: 0.5.6
* aria-label additions

### 2020-05-11 Version: 0.5.5
* Erroneous esc_attr caused class and id of staff to be invalid

### 2020-05-11 Version: 0.5.4
* Added display on front page for testimonials
* Fixed typo in contact template
* Use text string for Text Domain rather than constant
* Added aria-label for buttons and links

### 2020-05-06 Version: 0.5.3
* Typo in javascript code for the email strings

### 2020-05-06 Version: 0.5.2
* Added email status/success page
* Added instructions on templates

### 2020-05-06 Version: 0.5.1
* Shortcodes and code for sending emails

### 2020-05-06 Version: 0.5.0
* Move settings to it's own directory
* Move fields to it's own class

### 2020-05-06 Version: 0.4.1
* Set SMTP configuration in Plugin Options

### 2020-05-02 Version: 0.4.0
* Added email addresses to the plugin
* Add Contact form
* Add sending of email
* Add SMTP option

### 2020-04-30 Version: 0.3.8
* Ability to display all the social links in one block
* All backend blocks now black on white rather than black on transparent

### 2020-04-29 Version: 0.3.7
* Use get_the_post_thumbnail instead of custom building the image tag
* Formatting changes

### 2020-04-29 Version: 0.3.6
* Added block for Contact and Social Links

### 2020-04-28 Version: 0.3.5
* Replaced deprecated wp.editor with wp.blockEditor

### 2020-04-28 Version: 0.3.4
* Use states rather than props to display information in the block editor

### 2020-04-28 Version: 0.3.3
* Added trusted partners

### 2020-04-27 Version: 0.3.2
* Allow click for admissions and testimonials

### 2020-04-27 Version: 0.3.1
* Testimonials are now in a blockquote
* Fixed version for the package.json

### 2020-04-23 Version: 0.3.0
* Allow selection of which items to render
* Allow selection of the number of posts
* Allow enabling the display of various post information

### 2020-04-21 Version: 0.2.0
* Simplified all render functions to a div with h3 and other divs containing the information from the custom post type

### 2020-04-21 Version: 0.1.20
* Improvements to the staff render function
* Combined code for programs and staff javascript handling

### 2020-04-20 Version: 0.1.19
* Added shortcode to get link for social links
* Contacts shortcode returns a span rather than a paragraph
* Social links added to Plugin settings

### 2020-04-17 Version: 0.1.18
* Added options for paragraph class and clickable link for orc_contact shortcode

### 2020-04-17 Version: 0.1.17
* Programs use flex instead of grid

### 2020-04-17 Version: 0.1.16
* Shortcode added for phone/fax/text numbers
* Added Font Awesome Icons

### 2020-04-16 Version: 0.1.15
* Added border to programs
* Changed programs to use grid rather than flex
* Formatting changes

### 2020-04-15 Version: 0.1.14
* Updated ReadMe
* Removed default blocks directory
* Moved JavaScript from directories into common source directory
* Use gulp to compile and minify non-block JavaScript

### 2020-04-15 Version: 0.1.13
* Added Instructions page
* Added options settings page
* Error checking in orc_years_since shortcode
* Moved all custom post types under the ORC Plugin menu

### 2020-04-14 Version: 0.1.12
* Fixed phpcs error

### 2020-04-14 Version: 0.1.11
* Settings for videos, testimonials and staff moved to the blocks panel
* Staff departments fetched in php and passed to JavaScript

### 2020-04-12 Version: 0.1.10
* Added color picker
* Wide class moved to global
* Programs now has color and border width
* Render adds wide-class for full width program

### 2020-04-10
* Improvements to the programs render
* Added frontend javascript

### 2020-04-08 Version: 0.1.9
* Videos have the ability to have width, height and alignment

### 2020-04-07 Version: 0.1.8
* Added Videos

### 2020-04-07 Version: 0.1.7
* Added Media Coverage

### 2020-04-07 Version: 0.1.6
* FIX: Only remove padding for the programs featured image
* Added Tours

### 2020-04-07 Version: 0.1.5
* Added Testimonials

### 2020-04-07 Version: 0.1.4
* Added Admissions
* FIX: Class error in scss
* FIX: Typo with register block type

### 2020-04-07 Version: 0.1.3
* Added Programs

### 2020-04-07 Version: 0.1.2
* Added shortcodes class
* Added debug class
* Added staff block

### 2020-04-06 Version: 0.1.1
* Use namespaces
* Use classes
* Demo block created

### 2020-04-06 Version: 0.1.0
* Initial commit
