# Introducing the WordPress Abilities API: A developer's guide

Since the official announcement of the [WordPress AI team](https://wordpress.org/news/2025/05/announcing-the-formation-of-the-wordpress-ai-team/), I’ve been keeping a close eye on the work the team has been doing. One of those projects, slated for inclusion in WordPress 6.9, is the brand new [Abilities API](https://github.com/WordPress/abilities-api).

This API is designed to bring a unified, discoverable, and secure approach to how WordPress core, plugins, and themes define and expose their capabilities — or "abilities." The Abilities API is part of [WordPress's AI Building Blocks](https://make.wordpress.org/ai/2025/07/17/ai-building-blocks/) initiative, aiming to unlock new possibilities for automation, AI integration, and smoother developer workflows.

The Abilities API provides a central registry where WordPress capabilities (or abilities) are registered in a format that’s both machine-readable and human-friendly. This means that abilities are not only discoverable by developers but also accessible programmatically by automation tools across different platforms, including [AI agents](https://github.com/resources/articles/what-are-ai-agents).

The key goals of this project are:

- **Discoverability:** List and inspect all available abilities through a standard interface.
- **Interoperability:** A Uniform schema enables unrelated components to compose [workflows](https://github.com/WordPress/gutenberg/issues/70710).
- **Security-first:** Explicit permission control on who or what can invoke abilities.
- **Gradual Adoption:** Starting as a [Composer](https://getcomposer.org/) package plugin with plans to smoothly migrate to WordPress core.

Think of it as a one-stop shop for what WordPress or any plugin/theme can do, registered in a way everyone (and everything) can understand.

Now, I don’t know about you, but I find it easier to understand how something works when I can see it in action. To do that, I’m going to implement Abilities in a plugin I maintain, List All URLs.

\[\!NOTE\] You can find a full copy of the plugin code in [this GitHub repository](https://github.com/wptrainingteam/list-all-urls).

## List All URLs

The plugin itself is minimalist. It registers a Tools sub-menu item that opens an admin page where you can select to list the URLs of all Posts, Pages, or Custom Post Types on your WordPress site.

![][image1]

![][image2]

There is one primary function that powers the fetching and display of the data, the `list_all_urls_generate_url_list()` [function](https://github.com/wptrainingteam/list-all-urls/blob/c61ef4a4ea36acf279a8a2ff9038737db1bdbd25/list-all-urls.php#L49). This function receives two arguments and generates the list of URLs to display on the page.

```php
/**
* Generate a list of URLs based on the provided arguments
* Optionally make them clickable links
*
* @param array $arguments Arguments to customize the URL generation.
* @param bool  $makelinks Whether to return clickable links or plain URLs (escaped).
*
* @return array List of generated URLs.
*/
function list_all_urls_generate_url_list( array $arguments = array(), bool $makelinks = false ): array {
   $default_args   = array(
           'post_type'      => 'post',
           'posts_per_page' => - 1,
           'post_status'    => 'publish',
   );
   $args           = wp_parse_args( $arguments, $default_args );
   $posts          = get_posts( $args );

   $links = array();
   foreach ( $posts as $post ) {
      $permalink = get_permalink( $post );
      if ( $makelinks ) {
         $links[] = '<a href="' . esc_url( $permalink ) . '">' . esc_html( $permalink ) . '</a>';
      } else {
         $links[] = esc_html( $permalink );
      }
   }

   return $links;
}
```

Internally, this function calls the WordPress `get_posts()` function to retrieve the actual data and then formats it based on whether it should be displayed as a list of clickable links or not.

There are a couple of smaller quality-of-life improvements I’d like to add, such as the option to limit results for sites with a large number of URLs, the ability to filter URLs by category or date range, and the option to export the returned URLs.

However, two of the larger features I want to add are:

1. A way to access the URL list outside of WordPress (ie, a REST API endpoint) to be able to hook the data into external services.
2. A block that would allow anyone to add the list of URLs to any implementation of the Block Editor (ie, on any post or page, or even in a template).

To implement this, I’d need to set up a few things.

\[\!NOTE\] If you prefer browsing the code for this solution, you can see the full implementation in action by checking out the `rest-blocks` [branch](https://github.com/wptrainingteam/list-all-urls/tree/rest-blocks) of the GitHub repository.

I’ll need to register a custom REST API route and associated GET endpoint using `register_rest_route()`, which should get the posts for the route.

That would need a callback function to fetch the data, which in turn could call `list_all_urls_generate_url_list` without the `$makelinks` parameter to return the data.

```
add_action( 'rest_api_init', 'list_all_urls_register_rest_route' );
function list_all_urls_register_rest_route (): void {
   register_rest_route(
           'list-all-urls/v1',
           '/urls',
           array(
               'methods' => 'GET',
               'callback' => 'list_all_urls_rest_fetch_all_urls',
               'args' => array(
                       'type' => array(
                               'validate_callback' => function( $param ) {
                                   return is_string( $param );
                               }
                       ),
               ),
           )
   );
}

function list_all_urls_rest_fetch_all_urls( $arguments ){
   if ( isset($arguments['type'] ) ) {
       $post_type = sanitize_text_field( wp_unslash( $arguments['type'] ) );
   } else {
       $post_type = 'any';
   }
   $args = array(
       'post_type'      => $post_type,
   );
   return list_all_urls_generate_url_list( $args );
}
```

For the custom block, I would scaffold the block structure using [create-block](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/), and then utilize the custom REST API endpoint and the [api-fetch](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-api-fetch/) package to fetch the data for the block’s `Edit` component and render it in the Editor.

```
export default function Edit() {
    const [urls, setUrls] = useState([]);

    useEffect(() => {
        apiFetch( { path: '/list-all-urls/v1/urls' } ).then( ( urls ) => {
            setUrls( urls );
        } );
    }, []);

    if ( ! urls ) {
        return (
            <div { ...useBlockProps() }>
                <p>{ __(
                    'Loading...',
                    'list-all-urls'
                ) }</p>
            </div>
        );
    }

    let urlsList = urls.map( ( url ) => {
        return <li><a href={ url }>{ url }</a></li>;
    });

    return (
        <div { ...useBlockProps() }>
            <ul>{ urlsList }</ul>
        </div>
    );
}
```

I’d probably make it a dynamic block that calls the `list_all_urls_generate_url_list` function in a `render.php` file, which is configured to render on the front end.

```
<?php
/**
* Render file for the List All URLs block.
*/
$block_attributes = get_block_wrapper_attributes();
$urls = list_all_urls_generate_url_list( array( 'post_type' => 'any' ), true );
$urlList = '';
foreach ( $urls as $url ) {
   $urlList .= '<li>' .  wp_kses_post( $url ) . '</li>';
}
?>
<div <?php echo $block_attributes; ?>>
   <ul>
       <?php echo $urlList; ?>
   </ul>
</div>
```

I'd probably want to update the block to support [attributes](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/) so that users can select which post types should be used to generate the list of URLs. I would also want to add an attribute to allow a user to select whether to return a list of clickable links or not.

However, this is already a significant amount of code just to make it possible to access the list URLs functionality via the REST API and the Block Editor, while maintaining the existing admin page.

It’d be really nice if I could wrangle most of that just by registering it all in one place, and then access and execute it everywhere I need to.

This is the perfect problem that registering a custom Ability would solve.

## Installing the Abilities API

To get started, make sure you are working  with the latest version of the API.

Currently, there are three ways you can install and test the Abilities API:

- You can clone the [GitHub repository](https://github.com/WordPress/abilities-api/) into your `wp-content/plugins` directory, install any dependencies, run the build steps, and activate the plugin.
- You can download the latest version from the [releases](https://github.com/WordPress/abilities-api/releases) page of the GitHub repository, and then upload and install the plugin zip file.
- You can require the [Composer package](https://packagist.org/packages/wordpress/abilities-api) as a dependency of your plugin or theme.

My preferred method for testing the Abilities API is to clone the GitHub repository, as this ensures I have the latest version of the code on the `trunk` branch.

```
git clone git@github.com:WordPress/abilities-api.git
cd abilities-api
composer install
npm install
npm run build 
```

However, when I’m building Abilities into plugins, I prefer to install the composer package, which is what I’ll do to integrate it into List All URLs

```
cd /wp-content/plugins/list-all-urls
composer require wordpress/abilities-api
```

## Abilities to the rescue

In this case, a custom Ability can handle most, if not all, of the required core functionality. The [PHP docs](https://github.com/WordPress/abilities-api/blob/trunk/docs/php-api.md) contain everything you need on how to register and use Abilities in PHP, but let's take a look at what I need for List All URLs.

\[\!NOTE\] As before, if you prefer to browse the full code for this implementation, you can check out the `abilities` [branch](https://github.com/wptrainingteam/list-all-urls/tree/abilities) of the GitHub repository.

Registering an Ability in PHP is possibly using the `wp_register_ability()` function. To ensure the Ability is registered correctly, this function should always be called inside a callback hooked into the  `wp_abilities_api_init` action hook

```
add_action( 'wp_abilities_api_init', 'list_all_urls_register_abilities' );
/**
 * Register the ability to list all URLs
 *
 * @return void
 */
function list_all_urls_register_abilities() {
    wp_register_ability(
        'list-all-urls/urls',
        array(
            'label' => __( 'Get All URLs', 'list-all-urls' ),
            'description' => __( 'Retrieves a list of URLs from the WordPress site, optionally as clickable anchor links.', 'list-all-urls' ),
            'category' => 'site',
            'input_schema' => array(
                'type' => 'object',
                'properties' => array(
                        'post_type' => array(
                                'type' => 'string',
                                'description' => 'The post type to retrieve URLs from (e.g., post, page, custom post type).',
                            ),
                        'posts_per_page' => array(
                                'type' => 'integer',
                                'description' => 'Number of posts to retrieve. Use -1 to retrieve all posts.',
                        ),
                        'post_status' => array(
                                'type' => 'string',
                                'description' => 'The status of the posts to retrieve (e.g., publish, draft).',
                        ),
                        'makelinks' => array(
                                'type' => 'boolean',
                                'description' => 'Whether to return URLs as clickable anchor links.',
                        ),
                ),
            ),
            'output_schema' => array(
                    'type' => 'object',
                    'properties' => array(
                            'url' => array(
                                    'type' => 'string',
                                    'description' => 'URL or clickable link to the URL'
                            )
                    )
            ),
            'execute_callback' => 'list_all_urls_generate_url_list',
            'permission_callback' => '__return_true',
        )
    );
}
```

## Diving into Ability registration

Registering an Ability requires a unique identifier (`list-all-urls/urls`) and an array of arguments. Most of the arguments are optional, but the required ones are:

- `label`: A human-readable name for the Ability.
- `description`: A brief description of what the Ability does.
- `category`: The category under which the Ability is grouped. You can register your own Ability Categories, but in this case, I’m using the available `site` category
- `output_schema`: The schema that defines the structure of the data returned by the Ability.
- `execute_callback`: The function that will be called when the Ability is executed.
- `permission_callback`: A function that determines whether the current user has permission to execute the Ability.

The `input_schema` argument is optional, but highly recommended if your Ability requires input parameters. In my case, I'd like to pass in the same arguments that `list_all_urls_generate_url_list()` accepts.

Setting these schemas not only lets the Ability know what data it can expect and return, but also enables automatic validation of that data. For example, if I tried passing a `posts_per_page` value in the input that didn’t evaluate to an integer, it would automatically cause a validation error and stop the Ability from executing.

You’ll notice that I’m using the original `list_all_urls_generate_url_list()` function as the Ability `execute_callback`, so nothing has to change there.

### Getting and using an ability in PHP

The next step is to update the internal admin page to fetch and execute the ability.

All that really needs to change here is setting up the `$input` array, fetching the ability, and executing it.

```
$input = array(
    'post_type'      => $post_type,
    'posts_per_page' => - 1,
    'post_status'    => 'publish',
    'makelinks'      => $makelinks,
);

$urlsAbility = wp_get_ability( 'list-all-urls/urls' );
$urls = $urlsAbility->execute( $input );
```

Now, what I really like about this implementation is how extendable this is. If I wanted to allow other plugin or theme developers to make use of this functionality, all I have to do is document the ability id, the input schema, and the output schema.

There are also functions available to both check which abilities are available — [`wp_get_abilities()`](https://github.com/WordPress/abilities-api/blob/trunk/docs/php-api.md#getting-all-registered-abilities-wp_get_abilities) —  and whether a specific Ability is available or not  — [`wp_has_ability()`](https://github.com/WordPress/abilities-api/blob/trunk/docs/php-api.md#checking-if-an-ability-is-registered).

During development, you could use these functions to fetch and inspect individual abilities.

For example, checking which abilities are currently available:

```shell
wp> $abilities = wp_get_abilities();
=> array(4) {
  ["core/get-site-info"]=>
  object(WP_Ability)#2691 (9) {
    ["name":protected]=>
    string(18) "core/get-site-info"
    ["label":protected]=>
    string(20) "Get Site Information"
    ["description":protected]=>
    string(113) "Returns site information configured in WordPress. By default returns all fields, or optionally a filtered subset."
    ["category":protected]=>
    string(4) "site"
    ["input_schema":protected]=>
    array(4) {
      ["type"]=>
      string(6) "object"
      ["properties"]=>
      array(1) {
        ["fields"]=>
        array(3) {
          ["type"]=>
          string(5) "array"
          ["items"]=>
          array(2) {
            ["type"]=>
            string(6) "string"
            ["enum"]=>
            array(8) {
              [0]=>
              string(4) "name"
              [1]=>
              string(11) "description"
              [2]=>
              string(3) "url"
              [3]=>
              string(5) "wpurl"
              [4]=>
              string(11) "admin_email"
              [5]=>
              string(7) "charset"
              [6]=>
              string(8) "language"
              [7]=>
              string(7) "version"
            }
          }
          ["description"]=>
          string(81) "Optional: Limit response to specific fields. If omitted, all fields are returned."
        }
      }
      ["additionalProperties"]=>
      bool(false)
      ["default"]=>
      array(0) {
      }
    }
    ["output_schema":protected]=>
    array(3) {
      ["type"]=>
      string(6) "object"
      ["properties"]=>
      array(8) {
        ["name"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(15) "The site title."
        }
        ["description"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(17) "The site tagline."
        }
        ["url"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(18) "The site home URL."
        }
        ["wpurl"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(31) "The WordPress installation URL."
        }
        ["admin_email"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(37) "The site administrator email address."
        }
        ["charset"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(28) "The site character encoding."
        }
        ["language"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(30) "The site language locale code."
        }
        ["version"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(22) "The WordPress version."
        }
      }
      ["additionalProperties"]=>
      bool(false)
    }
    ["execute_callback":protected]=>
    object(Closure)#2689 (2) {
      ["static"]=>
      array(1) {
        ["site_info_fields"]=>
        array(8) {
          [0]=>
          string(4) "name"
          [1]=>
          string(11) "description"
          [2]=>
          string(3) "url"
          [3]=>
          string(5) "wpurl"
          [4]=>
          string(11) "admin_email"
          [5]=>
          string(7) "charset"
          [6]=>
          string(8) "language"
          [7]=>
          string(7) "version"
        }
      }
      ["parameter"]=>
      array(1) {
        ["$input"]=>
        string(10) "<optional>"
      }
    }
    ["permission_callback":protected]=>
    object(Closure)#2690 (0) {
    }
    ["meta":protected]=>
    array(2) {
      ["annotations"]=>
      array(3) {
        ["readonly"]=>
        bool(true)
        ["destructive"]=>
        bool(false)
        ["idempotent"]=>
        bool(true)
      }
      ["show_in_rest"]=>
      bool(true)
    }
  }
  ["core/get-user-info"]=>
  object(WP_Ability)#2694 (9) {
    ["name":protected]=>
    string(18) "core/get-user-info"
    ["label":protected]=>
    string(20) "Get User Information"
    ["description":protected]=>
    string(129) "Returns basic profile details for the current authenticated user to support personalization, auditing, and access-aware behavior."
    ["category":protected]=>
    string(4) "user"
    ["input_schema":protected]=>
    array(0) {
    }
    ["output_schema":protected]=>
    array(4) {
      ["type"]=>
      string(6) "object"
      ["required"]=>
      array(6) {
        [0]=>
        string(2) "id"
        [1]=>
        string(12) "display_name"
        [2]=>
        string(13) "user_nicename"
        [3]=>
        string(10) "user_login"
        [4]=>
        string(5) "roles"
        [5]=>
        string(6) "locale"
      }
      ["properties"]=>
      array(6) {
        ["id"]=>
        array(2) {
          ["type"]=>
          string(7) "integer"
          ["description"]=>
          string(12) "The user ID."
        }
        ["display_name"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(29) "The display name of the user."
        }
        ["user_nicename"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(35) "The URL-friendly name for the user."
        }
        ["user_login"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(32) "The login username for the user."
        }
        ["roles"]=>
        array(3) {
          ["type"]=>
          string(5) "array"
          ["description"]=>
          string(31) "The roles assigned to the user."
          ["items"]=>
          array(1) {
            ["type"]=>
            string(6) "string"
          }
        }
        ["locale"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(46) "The locale string for the user, such as en_US."
        }
      }
      ["additionalProperties"]=>
      bool(false)
    }
    ["execute_callback":protected]=>
    object(Closure)#2692 (0) {
    }
    ["permission_callback":protected]=>
    object(Closure)#2693 (0) {
    }
    ["meta":protected]=>
    array(2) {
      ["annotations"]=>
      array(3) {
        ["readonly"]=>
        bool(true)
        ["destructive"]=>
        bool(false)
        ["idempotent"]=>
        bool(true)
      }
      ["show_in_rest"]=>
      bool(false)
    }
  }
  ["core/get-environment-info"]=>
  object(WP_Ability)#2697 (9) {
    ["name":protected]=>
    string(25) "core/get-environment-info"
    ["label":protected]=>
    string(20) "Get Environment Info"
    ["description":protected]=>
    string(156) "Returns core details about the site's runtime context for diagnostics and compatibility (environment, PHP runtime, database server info, WordPress version)."
    ["category":protected]=>
    string(4) "site"
    ["input_schema":protected]=>
    array(0) {
    }
    ["output_schema":protected]=>
    array(4) {
      ["type"]=>
      string(6) "object"
      ["required"]=>
      array(4) {
        [0]=>
        string(11) "environment"
        [1]=>
        string(11) "php_version"
        [2]=>
        string(14) "db_server_info"
        [3]=>
        string(10) "wp_version"
      }
      ["properties"]=>
      array(4) {
        ["environment"]=>
        array(3) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(109) "The site's runtime environment classification (can be one of these: production, staging, development, local)."
          ["enum"]=>
          array(4) {
            [0]=>
            string(10) "production"
            [1]=>
            string(7) "staging"
            [2]=>
            string(11) "development"
            [3]=>
            string(5) "local"
          }
        }
        ["php_version"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(44) "The PHP runtime version executing WordPress."
        }
        ["db_server_info"]=>
        array(3) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(69) "The database server vendor and version string reported by the driver."
          ["examples"]=>
          array(2) {
            [0]=>
            string(6) "8.0.34"
            [1]=>
            string(15) "10.11.6-MariaDB"
          }
        }
        ["wp_version"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(48) "The WordPress core version running on this site."
        }
      }
      ["additionalProperties"]=>
      bool(false)
    }
    ["execute_callback":protected]=>
    object(Closure)#2695 (0) {
    }
    ["permission_callback":protected]=>
    object(Closure)#2696 (0) {
    }
    ["meta":protected]=>
    array(2) {
      ["annotations"]=>
      array(3) {
        ["readonly"]=>
        bool(true)
        ["destructive"]=>
        bool(false)
        ["idempotent"]=>
        bool(true)
      }
      ["show_in_rest"]=>
      bool(true)
    }
  }
  ["list-all-urls/urls"]=>
  object(WP_Ability)#2698 (9) {
    ["name":protected]=>
    string(18) "list-all-urls/urls"
    ["label":protected]=>
    string(12) "Get All URLs"
    ["description":protected]=>
    string(87) "Retrieves a list of URLs from the WordPress site, optionally as clickable anchor links."
    ["category":protected]=>
    string(13) "list-all-urls"
    ["input_schema":protected]=>
    array(2) {
      ["type"]=>
      string(6) "object"
      ["properties"]=>
      array(4) {
        ["post_type"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(73) "The post type to retrieve URLs from (e.g., post, page, custom post type)."
        }
        ["posts_per_page"]=>
        array(2) {
          ["type"]=>
          string(7) "integer"
          ["description"]=>
          string(58) "Number of posts to retrieve. Use -1 to retrieve all posts."
        }
        ["post_status"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(59) "The status of the posts to retrieve (e.g., publish, draft)."
        }
        ["makelinks"]=>
        array(2) {
          ["type"]=>
          string(7) "boolean"
          ["description"]=>
          string(49) "Whether to return URLs as clickable anchor links."
        }
      }
    }
    ["output_schema":protected]=>
    array(2) {
      ["type"]=>
      string(6) "object"
      ["properties"]=>
      array(1) {
        ["url"]=>
        array(2) {
          ["type"]=>
          string(6) "string"
          ["description"]=>
          string(32) "URL or clickable link to the URL"
        }
      }
    }
    ["execute_callback":protected]=>
    string(31) "list_all_urls_generate_url_list"
    ["permission_callback":protected]=>
    string(13) "__return_true"
    ["meta":protected]=>
    array(2) {
      ["annotations"]=>
      array(3) {
        ["readonly"]=>
        NULL
        ["destructive"]=>
        NULL
        ["idempotent"]=>
        NULL
      }
      ["show_in_rest"]=>
      bool(true)
    }
  }
}

```

Checking if a specific Ability is available:

```shell
wp> $found = wp_has_ability('list-all-urls/urls');
=> bool(true)

```

Fetching a single Ability:

```shell
wp> $ability = wp_get_ability('list-all-urls/urls');
=> object(WP_Ability)#2698 (9) {
  ["name":protected]=>
  string(18) "list-all-urls/urls"
  ["label":protected]=>
  string(12) "Get All URLs"
  ["description":protected]=>
  string(87) "Retrieves a list of URLs from the WordPress site, optionally as clickable anchor links."
  ["category":protected]=>
  string(13) "list-all-urls"
  ["input_schema":protected]=>
  array(2) {
    ["type"]=>
    string(6) "object"
    ["properties"]=>
    array(4) {
      ["post_type"]=>
      array(2) {
        ["type"]=>
        string(6) "string"
        ["description"]=>
        string(73) "The post type to retrieve URLs from (e.g., post, page, custom post type)."
      }
      ["posts_per_page"]=>
      array(2) {
        ["type"]=>
        string(7) "integer"
        ["description"]=>
        string(58) "Number of posts to retrieve. Use -1 to retrieve all posts."
      }
      ["post_status"]=>
      array(2) {
        ["type"]=>
        string(6) "string"
        ["description"]=>
        string(59) "The status of the posts to retrieve (e.g., publish, draft)."
      }
      ["makelinks"]=>
      array(2) {
        ["type"]=>
        string(7) "boolean"
        ["description"]=>
        string(49) "Whether to return URLs as clickable anchor links."
      }
    }
  }
  ["output_schema":protected]=>
  array(2) {
    ["type"]=>
    string(6) "object"
    ["properties"]=>
    array(1) {
      ["url"]=>
      array(2) {
        ["type"]=>
        string(6) "string"
        ["description"]=>
        string(32) "URL or clickable link to the URL"
      }
    }
  }
  ["execute_callback":protected]=>
  string(31) "list_all_urls_generate_url_list"
  ["permission_callback":protected]=>
  string(13) "__return_true"
  ["meta":protected]=>
  array(2) {
    ["annotations"]=>
    array(3) {
      ["readonly"]=>
      NULL
      ["destructive"]=>
      NULL
      ["idempotent"]=>
      NULL
    }
    ["show_in_rest"]=>
    bool(true)
  }
}
```

When fetching all Abilities and individual Abilities, the whole Ability object is returned, so you’re able to see what the Ability does, and what the expected inputs and outputs are.

### REST API support out of the box

Another really cool thing about Abilities is that you can enable their REST API endpoints in a similar way to when creating a Custom Post Type. If you enable the `meta.show_in_rest` argument on the Ability it will support the [Abilities REST API endpoints](https://github.com/WordPress/abilities-api/blob/trunk/docs/rest-api.md) by default.

```php
'meta' => array(
       'show_in_rest' => true,
),
```

The [Abilities REST API endpoints](https://github.com/WordPress/abilities-api/blob/trunk/docs/rest-api.md) are available under the `wp-json/wp-abilities/v1` namespace. Access to the Abilities REST API endpoints requires an authenticated user, which supports all the same authentication methods as the WordPress REST API.

As with the PHP functions, you can perform a series of default Ability actions using this namespace, including:

- listing all Abilities by sending a *GET* request to `/wp-json/wp-abilities/v1/abilities`
- retrieving a single Ability by sending a *GET* request to `wp-json//wp-abilities/v1/{namespace/ability}`, where `{namespace/ability}` is the unique ID of your registered Ability (eg `list-all-urls/urls`)
- executing an Ability by sending either a *GET* or *POST*  request (depending on the Ability's `readonly` setting) to `/wp-json/wp-abilities/v1/{namespace/ability}/run`.

What's more, the `permission_callback` set during Ability registration is also respected when executing Abilities via the REST API. This ensures that only authenticated users with the correct permissions can execute any given Ability via the REST API.

All that, just by enabling a single argument on registration\!

## Abilities are coming to Core\!

The server-side Abilities registration, retrieval, and execution, including REST API support, was recently [approved to be merged in WordPress 6.9](https://core.trac.wordpress.org/ticket/64098). This means that once [6.9 is released in early December](https://make.wordpress.org/core/6-9/), you’ll have access to all this functionality out of the box in WordPress. You can even help test it right now by following the instructions in the [Help Test WordPress 6.9](https://make.wordpress.org/test/2025/10/21/help-test-wordpress-6-9/) post.

## Using an ability in JavaScript

Now, you might be thinking, “Ok, but I still have to use something like the `fetch-api` to fetch the data from the REST API endpoints?” Well, dear reader, that’s the fun part.

The Abilities API also includes a [JavaScript client](https://github.com/WordPress/abilities-api/blob/trunk/docs/javascript-client.md), which provides built-in support for fetching and executing custom Abilities in the browser.

Currently, the JavaScript client is only available in the GitHub repository; however, the goal is to eventually ship it as a Gutenberg package. As a result, there’s a good chance it will be included in WordPress core by version 7.0 (or possibly earlier via the Gutenberg plugin).

If you require the Abilities API as a composer package, you can start using it in your plugins or themes, without causing any conflicts with the Core Abilities API.

The JavaScript client also ships with functions to list, fetch and execute custom Abilities, as well as a function to create custom Abilities in JavaScript. In the case of List all URLs, I only need the function to execute my Ability.

```javascript
import { executeAbility } from '@wordpress/abilities';
```

And then I can execute the Ability, passing it a JSON object for the required input, and use the data it returns in my block.

```javascript
useEffect(() => {
   executeAbility( 'list-all-urls/urls', { 'makelinks': attributes.makeLinks } ).then( ( urls ) => {
       setUrls( urls );
   } );
}, []);

```

## Where to learn more and get involved

- Follow the development of the Abilities API in the official [GitHub repository](https://github.com/WordPress/abilities-api) and be sure to [read the docs](https://github.com/WordPress/abilities-api/tree/trunk/docs).
- Join discussions on the WordPress Slack [\#core-ai channel](https://wordpress.slack.com/archives/C08TJ8BPULS/p1747960962509329) or follow the [Core AI team blog](https://make.wordpress.org/ai/) for updates and news.

## We’re just scratching the surface of the possibilities.

My colleague [Em Shreve](http://profiles.wordpress.org/emdashcodes/) (who was instrumental in building out the Abilities API JavaScript client) describes the Abilities API like this:

A first-class, cross-context functional API that other tools and applications can use to interface with WordPress.

Think of all the different ways in which WordPress core, plugins, and themes make their capabilities available to developers. WordPress doesn’t currently force a standard method to build and communicate a public API, so the available options could be anything from a series of action and filter hooks, to publicly accessible global functions, to a series of REST API endpoints, to objects that are meant to be extended, or any combination of all these.

Abilities solves this problem by allowing WordPress developers to define a standardized and discoverable method to expose common functionalities. These Abilities can then be accessed and executed from the application (PHP) and presentation (JavaScript) layers of WordPress, as well as by external applications (REST API), all in the same standardized way.

Not only that, but if you combine Abilities with something like the [WordPress MCP adapter](https://github.com/WordPress/mcp-adapter), you can even allow any AI agents to interact with your Abilities, unlocking even more possibilities. (Now that's a blog post for another day\!)

The Abilities API is an exciting new way to develop with WordPress, and I look forward to seeing what it enables for the future of WordPress.