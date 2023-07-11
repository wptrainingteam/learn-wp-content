# Common APIs - Dashboard Widgets

## Learning Objectives

Upon completion of this lesson the participant will be able to:

1. Describe what the Dashboard Widgets API is
2. Explain how to use the Dashboard Widgets API
3. Describe how to add a dashboard widget
4. Explain how to add a widget control
5. Describe how to save widget control data
6. Explain how to add a widget to a specific column
7. Describe how to add a widget to a specific priority

## Outline

1. Introduction
2. What is the Dashboard Widgets API
3. How to use the Dashboard Widgets API
4. Adding a Dashboard Widget
5. Adding a widget control
6. Working with the callback arguments
7. Widget context argument
8. Widget priority argument

## Introduction

Hey there, and welcome to Learn WordPress. 

In this tutorial, you're going to learn about the WordPress Dashboard Widgets API.

You will learn 

## What is the Dashboard Widgets API

The Dashboard Widgets API is a series of functions that allow you to add, remove, and modify the widgets that appear on the WordPress Dashboard. This can be handy if you want a direct way to interact with your users, either to show them information or to allow them to perform specific actions.

Here's an example of some custom dashboard widgets in action:

1. The Jetpack plugin adds a widget that shows you your site stats, and top posts and pages
2. The Seriously Simple Stats addon plugin for Seriously Simple Podcasting shows your episode stats, and the Seriously Simple Podcasting plugin also includes an RSS feed widget for Castos news, which is the comapny that owns and develops SSP.

## How to use the Dashboard Widgets API

Added in WordPress Version 2.7, the Dashboard Widgets API makes it simple to add new widgets to the administration dashboard. The main function you need to know about is the `wp_add_dashboard_widget()` [function](https://developer.wordpress.org/reference/functions/wp_add_dashboard_widget/). This function takes a number of parameters:

1. `$widget_id` - A unique ID for your widget, which is also used as the id attribute in the widget's HTML output
2. `$widget_name` - The name of your widget
3. `$callback` - The function that will be called to output the contents of your widget. This function should echo the contents of the widget
4. `$control_callback` - An optional function that will be called to output controls to configure data for the widget, as well as process any data that has been submitted by the controls
5. `$callback_args` - An optional array of arguments that will be passed to your callback function
6. `$context` - An optional string that defines the column that your widget will be shown in. The default is 'normal', but other options include 'side', 'column3', and 'column4'
7. `$priority` - An optional string that defines the priority of your widget, within the context. The default is 'core', but other options include 'default', 'high', and 'low'

To understand how this works, let's build an example dashboard widget

## Adding a Dashboard Widget

### Initial setup and output

To start, let's just build a widget that outputs some text. 

As with most WordPress APIs, we'll start by hooking into an action. In this case, we'll use the `wp_dashboard_setup` [action](https://developer.wordpress.org/reference/hooks/wp_dashboard_setup/), which is fired when the dashboard is initialized. This is where we'll add our widget.

```php
add_action( 'wp_dashboard_setup', 'wp_learn_dashboard_widget' );
function wp_learn_dashboard_widget(){
    wp_add_dashboard_widget(
        'wp_learn_dashboard_widget',
        'Learn WordPress Dashboard Widget',
        'wp_learn_dashboard_widget_callback'
    );
}

function wp_learn_dashboard_widget_callback(){
    echo '<p>Hello, World!</p>';
}
```

If you add this code to an empty plugin file, enable the pluigin, and load the dashboard, you'll see your widget at the bottom of the first column of the dashboard.

You can do pretty much anything with your content callback function. Let's add a list of the most recent posts to our widget, using the wp_get_recent_posts() [function](https://developer.wordpress.org/reference/functions/wp_get_recent_posts/).

```php
function wp_learn_dashboard_widget_callback(){
    $args = array(
        'numberposts' => 5,
        'post_status' => 'publish'
    );
    $recent_posts = wp_get_recent_posts( $args );
    echo '<ul>';
    foreach( $recent_posts as $recent ){
        echo '<li><a href="' . get_permalink( $recent['ID'] ) . '">' . $recent['post_title'] . '</a></li>';
    }
    echo '</ul>';
}
```

## Adding a widget control

Now, let's add some controls to configure our widget. Let's say we want to control how many posts are displayed. To do that, we configure the control callback function. We'll add a function called `wp_learn_dashboard_widget_control()` to process the data.

```php
function wp_learn_dashboard_widget_control(){
    echo '<label>Enter number of posts to display</label>';
    echo '<input type="text" name="wp_learn_dashboard_widget_numberposts" value='.$number_posts.' />';
}
```

When you refresh the dashboard, nothing appears to happen, but if you hover over the widget, you'll see a new link appear that says "Configure". Clicking on that link will display the form that we just created.

Now, we need to add a function to process the data that is submitted by the form. This is where the control callback comes in. We'll add a function called `wp_learn_dashboard_widget_control()` to process the data.

Entering the new value and clicking submit will process the widget control, but we need to accept that data and save it somewhere. This is also handled inside the `wp_learn_dashboard_widget_control()` function.

The save the data, we'll use the `update_option()` [function](https://developer.wordpress.org/reference/functions/update_option/), which will store the value in the options table. 

```php
function wp_learn_dashboard_widget_control_callback(){
    if (isset($_POST['wp_learn_dashboard_widget_numberposts'])){
        update_option( 'wp_learn_dashboard_widget_numberposts', sanitize_text_field( $_POST['wp_learn_dashboard_widget_numberposts'] ) );
    }
    $number_posts = get_option( 'wp_learn_dashboard_widget_numberposts', 5 );
    echo '<label>Enter number of posts to display</label>';
    echo '<input type="text" name="wp_learn_dashboard_widget_numberposts" value='.$number_posts.' />';
}
```

Then we need to go back to our content callback function and update the `$args` array to use the value that we just saved. To fetch the value, we'll use the `get_option()` [function](https://developer.wordpress.org/reference/functions/get_option/).

```php
function wp_learn_dashboard_widget_callback(){
    $numberposts = get_option( 'wp_learn_dashboard_widget_numberposts', 5 );
    $args = array(
        'numberposts' => $numberposts,
        'post_status' => 'publish'
    );
    $recent_posts = wp_get_recent_posts( $args );
    echo '<ul>';
    foreach( $recent_posts as $recent ){
        echo '<li><a href="' . get_permalink( $recent['ID'] ) . '">' . $recent['post_title'] . '</a></li>';
    }
    echo '</ul>';
}
```

## Working with the callback arguments

The last thing we'll do is add a title to our widget. To do this, we'll use the `$callback_args` parameter of the `wp_add_dashboard_widget()` function. This parameter accepts an array of arguments that will be passed to the callback function. 

```php
add_action( 'wp_dashboard_setup', 'wp_learn_dashboard_widget' );
function wp_learn_dashboard_widget(){
    wp_add_dashboard_widget(
        'wp_learn_dashboard_widget',
        'Learn WordPress Dashboard Widget',
        'wp_learn_dashboard_widget_callback',
        'wp_learn_dashboard_widget_control',
        array(
            'title' => 'Learn WordPress Dashboard Widget'
        )
    );
}
```

Then, we'll update the content callback function to use the new argument. As you'll note in the documentation, the `$args` parameter is passed as the second argument of the callback function, the first being the screen object, which is blank in the context of a dashboard widget. 

```php
function wp_learn_dashboard_widget_callback( $screen, $args ){
    $numberposts = get_option( 'wp_learn_dashboard_widget_numberposts', 5 );
    $post_args = array(
        'numberposts' => $numberposts,
        'post_status' => 'publish'
    );
    $recent_posts = wp_get_recent_posts( $post_args );
    echo '<h2>' . $args['title'] . '</h2>';
    echo '<ul>';
    foreach( $recent_posts as $recent ){
        echo '<li><a href="' . get_permalink( $recent['ID'] ) . '">' . $recent['post_title'] . '</a></li>';
    }
    echo '</ul>';
}
```

## Widget context argument

The default for the widget context is `normal`, which means that the widget will appear in the first column of the dashboard. You can also set the context to `side`, which will place the widget in the second column of the dashboard. Setting the context to `column3` or `column4` will place the widget in column 3 or 4 respectively. 

## Widget priority argument

The default for the widget priority is `core`, which means that the widget will appear at the bottom of any other widgets with a `core` priority. You can also set the priority to `high`, which will place the widget at the top of the column. Additional priority values are `default`, and `low`. Note that you can't easily control if your widget appears at the top of any other widgets with a `high` priority, although it is possible to resort [manually resort the array of widgets](https://developer.wordpress.org/apis/dashboard-widgets/#forcing-your-widget-to-the-top)

## Conclusion

And that wraps up this tutorial on creating a dashboard widget. For for information on dashboard widgets, check out the [documentation](https://developer.wordpress.org/apis/dashboard-widgets/) at developer.wordpress.org.

Happy coding