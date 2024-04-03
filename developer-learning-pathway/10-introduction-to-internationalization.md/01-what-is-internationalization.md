# What is Internationalization?

## Introduction

Internationalization is the process of developing software in a way that it can easily be translated into other languages without any changes to the source code.

Let's learn what internationalization is in the context of WordPress, why it is important, and learn where to find more information on how to implement it in your WordPress development.

## What is Internationalization?

WordPress is used all over the world, by people who speak many languages. 

Therefore, any text strings in WordPress need to be coded so that they can be easily translated into other languages.

Let's look at an example:

When you log into your WordPress dashboard for the first time, the heading "Dashboard" appears at the top of the page.

You can find the code that generates this title at line 33 of the `wp-admin/index.php` file.

```php
$title       = __( 'Dashboard' );
```

Here the $title variable is set to "Dashboard".

Notice how the text string is wrapped in the `__()` function. This is a WordPress function that is used to make the text string translatable.

If you were to access a user's profile, and update it to a different language, the text string would be translated into that language.

So, for example, if I change my user profile to Spanish, the title would be translated to "Escritorio".

But the code underneath would remain the same.

This is possible because the language file for Spanish has been installed on the site, and contains the translation for the text string "Dashboard".

```
#. translators: Network menu item.
#: wp-includes/admin-bar.php:431 wp-includes/admin-bar.php:596
#: wp-includes/admin-bar.php:716 wp-includes/deprecated.php:2822
#: wp-includes/deprecated.php:2824 wp-admin/index.php:33 wp-admin/menu.php:24
#: wp-admin/my-sites.php:142 wp-admin/user/menu.php:10
#: wp-admin/includes/class-wp-ms-sites-list-table.php:746
#: wp-admin/network/index.php:21 wp-admin/network/menu.php:11
#: wp-admin/network/site-info.php:138 wp-admin/network/site-settings.php:95
#: wp-admin/network/site-themes.php:181 wp-admin/network/site-users.php:226
msgid "Dashboard"
msgstr "Escritorio"
```

Because the translation function is used, once the language has been set for the user, WordPress will search for the translation for this word in the relevant language file, and either display or store the translation, depending on the context.

Internationalization is the process of writing your code so that any text strings that might be displayed to the user are translatable by wrapping them in the correct translation function. 

Internationalization is often abbreviated as i18n (because there are 18 letters between the letters i and n).

The process of translating and adapting the Internationalized text strings to a specific locale or language is called Localization.

While localization is outside the scope of this lesson, you can read more about it [in the Localization section](https://developer.wordpress.org/apis/internationalization/localization/) of the Common APIs handbook in the WordPress developer resources.

## What Internationalization is not

Internationalization is not the same as making sure your content is available in multiple languages on the front end of your website. 

This is more commonly referred to as making sure your content is multilingual or translated. 

Because this content is stored in the database, it's better to have a fully translated copy of your site for each language you want to support. 

This can be achieved using plugins like [TranslatePress](https://wordpress.org/plugins/translatepress-multilingual/), [Polylang](https://wordpress.org/plugins/polylang/), or [WeGlot](https://wordpress.org/plugins/weglot). 

## Where to find more information

The WordPress developer resources have a great section on [Internationalization](https://developer.wordpress.org/apis/internationalization/) in the Common APIs handbook. It includes an overview of the process, as well as a page which lists the [functions that are commonly used](https://developer.wordpress.org/apis/internationalization/internationalization-functions/) in WordPress for Internationalization. It also contains a section on [Internationalization Guidelines](https://developer.wordpress.org/apis/internationalization/internationalization-guidelines/), which is a must-read for any developer who is looking to make their WordPress plugin or theme translatable.
