# Common APIs - Dashboard Widgets

## Learning Objectives

Upon completion of this lesson the participant will be able to:

1. Describe what the Dashboard Widgets API is
2. Explain how to use the Dashboard Widgets API
3. Describe how to add a dashboard widget
4. Explain how to add a widget control
5. Describe how to save widget control data

## Outline

1. Introduction
2. What is the Dashboard Widgets API
3. How to use the Dashboard Widgets API
4. Adding a Dashboard Widget
5. Adding a widget control
6. Saving widget control data
7. Conclusion

## Introduction

Hey there, and welcome to Learn WordPress. 

In this tutorial, you're going to learn about the WordPress Dashboard Widgets API.

You will learn what the Dashboard Widgets API is, why you might want to use it, and how to add a dashboard widget to your WordPress site.

## What is the Dashboard Widgets API

The Dashboard Widgets API is a series of functions that allow you to add, remove, and modify the widgets that appear on the WordPress Dashboard. This can be handy if you want a direct way to interact with your users, either to show them information or to allow them to perform specific actions.

Here's an example of some custom dashboard widgets in action:

1. The Jetpack plugin adds a widget that shows you your site stats, and top posts and pages
2. The Seriously Simple Stats addon plugin for Seriously Simple Podcasting shows your episode stats, and the Seriously Simple Podcasting plugin also includes an RSS feed widget for Castos news, which is the company that owns and develops Seriously Simple Podcasting.

## How to use the Dashboard Widgets API

Since WordPress Version 2.7, the Dashboard Widgets API makes it straightforward to add new widgets to the WordPress administration dashboard. The main function you need to know about is the `wp_add_dashboard_widget()` [function](https://developer.wordpress.org/reference/functions/wp_add_dashboard_widget/). This function takes a number of parameters:

1. `$widget_id` - A unique ID for your widget, which is also used as the id attribute in the widget's HTML output
2. `$widget_name` - The name of your widget
3. `$callback` - The function that will be called to output the contents of your widget. This function should echo the contents of the widget

Additionally, there are some optional parameters that you can specify.

1. `$control_callback` - An optional function that will be called to output controls to configure data for the widget, as well as process any data that has been submitted by the controls
2. `$callback_args` - An optional array of arguments that will be passed to your callback function
3. `$context` - An optional string that defines the column that your widget will be shown in. The default is 'normal', but other options include 'side', 'column3', and 'column4'
4. `$priority` - An optional string that defines the priority of your widget, within the context. The default is 'core', but other options include 'default', 'high', and 'low'

To understand how this works, let's build an example dashboard widget

## Adding a Dashboard Widget

### Initial setup and output

To start, create a new plugin directory and plugin PHP file in the wp-content/plugins directory. For this example you can call it `wp-learn-dashboard-widgets`. 

For this plugin to be recognized as a WordPress plugin, you need to add a plugin header, with at minimum a value for Plugin Name. 

As with most WordPress APIs, you'll start by hooking into an action. In this case, use the `wp_dashboard_setup` [action](https://developer.wordpress.org/reference/hooks/wp_dashboard_setup/), which is fired when the dashboard is initialized. This is where you'll add our widget.

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

If you add this code to the empty plugin file, activate the plugin, and load the dashboard, you'll see your widget at the bottom of the first column of the dashboard.

You have a lot of flexibility with your content callback function, as long as it echos valid HTML. 

Try adding a list of the most recent posts to your widget, using the `wp_get_recent_posts()` [function](https://developer.wordpress.org/reference/functions/wp_get_recent_posts/).

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

You can also add some controls to configure your widget. Let's say you want to control how many posts are displayed. To do that, configure the control callback function. 

Add a function called `wp_learn_dashboard_widget_control()` to process the data.

```php
function wp_learn_dashboard_widget_control(){
    echo '<label>Enter number of posts to display</label>';
    echo '<input type="text" name="wp_learn_dashboard_widget_numberposts"/>';
}
```

When you refresh the dashboard, nothing appears to happen, but if you hover over the widget, you'll see a new link appear that says "Configure". Clicking on that link will display the form that you just created.

Now, you need to update the control callback to process the data that is submitted by the form. 

The save the data, use the `update_option()` [function](https://developer.wordpress.org/reference/functions/update_option/), which will store the value in the options table. 

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

Then you need to go back to your content callback function and update the `$args` array to use the value that you just saved. To fetch the value, you can use the `get_option()` [function](https://developer.wordpress.org/reference/functions/get_option/).

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

## Conclusion

For more information on working with dashboard widgets, including examples of how to use the optional callback arguments, context, and priority parameters, check out the [Dashboard Widgets API section](https://developer.wordpress.org/apis/dashboard-widgets/) at developer.wordpress.org.

Happy coding