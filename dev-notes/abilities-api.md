## Abilities API in WordPress 6.9

WordPress 6.9 introduces the **Abilities API**, a new foundational system that enables plugins, themes, and WordPress core to register and expose their capabilities in a standardized, machine-readable format. This API creates a unified registry of functionality that can be discovered, validated, and executed consistently across different contexts, including PHP, REST API endpoints, and future AI-powered integrations.

The Abilities API is part of the broader AI Building Blocks for WordPress initiative, providing the groundwork for AI agents, automation tools, and developers to understand and interact with WordPress functionality in a predictable manner.

### What is the Abilities API?

An **ability** is a self-contained unit of functionality with defined inputs, outputs, permissions, and execution logic. By registering abilities through the Abilities API, developers can:

- Create discoverable functionality with standardized interfaces
- Define permission checks and execution callbacks
- Organize abilities into logical categories
- Validate inputs and outputs
- Automatically expose abilities through REST API endpoints

Rather than burying functionality in isolated functions or custom AJAX handlers, abilities are registered in a central registry that makes them accessible through multiple interfaces.

### Core Components

The Abilities API introduces three main components to WordPress 6.9:

#### 1\. PHP API

A set of functions for registering, managing, and executing abilities:

**Ability Management:**

- `wp_register_ability()` – Register a new ability
- `wp_unregister_ability()` – Unregister an ability
- `wp_has_ability()` – Check if an ability is registered
- `wp_get_ability()` – Retrieve a registered ability
- `wp_get_abilities()` – Retrieve all registered abilities

**Ability Category Management:**

- `wp_register_ability_category()` – Register an ability category
- `wp_unregister_ability_category()` – Unregister an ability category
- `wp_has_ability_category()` – Check if an ability category is registered
- `wp_get_ability_category()` – Retrieve a registered ability category
- `wp_get_ability_categories()` – Retrieve all registered ability categories

#### 2\. REST API Endpoints

The Abilities API automatically exposes registered abilities through REST API endpoints under the `wp-abilities/v1` namespace:

- `GET /wp-abilities/v1/categories` – List all ability categories
- `GET /wp-abilities/v1/categories/{slug}` – Get a single ability category
- `GET /wp-abilities/v1/abilities` – List all abilities
- `GET /wp-abilities/v1/abilities/{name}` – Get a single ability
- `GET|POST /wp-abilities/v1/abilities/{name}/run` – Execute an ability

#### 3\. Hooks

New action hooks for integrating with the Abilities API:

**Actions:**

- `wp_abilities_api_categories_init` – Fired when the ability categories registry is initialized (register categories here)
- `wp_abilities_api_init` – Fired when the abilities registry is initialized (register abilities here)
- `wp_before_execute_ability` – Fired before an ability executes
- `wp_after_execute_ability` – Fired after an ability finishes executing

**Filters:**

- `wp_register_ability_category_args` – Filters ability category arguments before registration
- `wp_register_ability_args` – Filters ability arguments before registration

### Registering Abilities

Abilities must be registered on the `wp_abilities_api_init` action hook. Attempting to register abilities outside of this hook will trigger a `_doing_it_wrong()` notice, and the Ability registration will fail.

#### Basic Example

Here's a complete example of registering an ability category and an ability:

```php
<?php
add_action( 'wp_abilities_api_categories_init', 'my_plugin_register_ability_categories' );
/**
 * Register ability categories.
 */
function my_plugin_register_ability_categories() {
    wp_register_ability_category(
        'content-management',
        array(
            'label'       => __( 'Content Management', 'my-plugin' ),
            'description' => __( 'Abilities for managing and organizing content.', 'my-plugin' ),
        )
    );
}

add_action( 'wp_abilities_api_init', 'my_plugin_register_abilities' );
/**
 * Register abilities.
 */
function my_plugin_register_abilities() {
    wp_register_ability(
        'my-plugin/get-post-count',
        array(
            'label'              => __( 'Get Post Count', 'my-plugin' ),
            'description'        => __( 'Retrieves the total number of published posts.', 'my-plugin' ),
            'category'           => 'content-management',
            'input_schema'       => array(
                'type'       => 'object',
                'properties' => array(
                    'post_type' => array(
                        'type'        => 'string',
                        'description' => __( 'The post type to count.', 'my-plugin' ),
                        'default'     => 'post',
                    ),
                ),
            ),
            'output_schema'      => array(
                'type'       => 'object',
                'properties' => array(
                    'count' => array(
                        'type'        => 'integer',
                        'description' => __( 'The number of published posts.', 'my-plugin' ),
                    ),
                ),
            ),
            'execute_callback'   => 'my_plugin_get_post_count',
            'permission_callback' => function() {
                return current_user_can( 'read' );
            },
        )
    );
}

/**
 * Execute callback for get-post-count ability.
 */
function my_plugin_get_post_count( $input ) {
    $post_type = $input['post_type'] ?? 'post';
    
    $count = wp_count_posts( $post_type );
    
    return array(
        'count' => (int) $count->publish,
    );
}
```

#### More Complex Example

Here's an example with more advanced input validation and error handling:

```php
<?php
add_action( 'wp_abilities_api_init', 'my_plugin_register_text_analysis_ability' );
/**
 * Register a text analysis ability.
 */
function my_plugin_register_text_analysis_ability() {
    wp_register_ability(
        'my-plugin/analyze-text',
        array(
            'label'              => __( 'Analyze Text', 'my-plugin' ),
            'description'        => __( 'Performs sentiment analysis on provided text.', 'my-plugin' ),
            'category'           => 'text-processing',
            'input_schema'       => array(
                'type'       => 'object',
                'properties' => array(
                    'text' => array(
                        'type'        => 'string',
                        'description' => __( 'The text to analyze.', 'my-plugin' ),
                        'minLength'   => 1,
                        'maxLength'   => 5000,
                    ),
                    'options' => array(
                        'type'       => 'object',
                        'properties' => array(
                            'include_keywords' => array(
                                'type'        => 'boolean',
                                'description' => __( 'Whether to extract keywords.', 'my-plugin' ),
                                'default'     => false,
                            ),
                        ),
                    ),
                ),
                'required' => array( 'text' ),
            ),
            'output_schema'      => array(
                'type'       => 'object',
                'properties' => array(
                    'sentiment' => array(
                        'type'        => 'string',
                        'enum'        => array( 'positive', 'neutral', 'negative' ),
                        'description' => __( 'The detected sentiment.', 'my-plugin' ),
                    ),
                    'confidence' => array(
                        'type'        => 'number',
                        'minimum'     => 0,
                        'maximum'     => 1,
                        'description' => __( 'Confidence score for the sentiment.', 'my-plugin' ),
                    ),
                    'keywords' => array(
                        'type'        => 'array',
                        'items'       => array(
                            'type' => 'string',
                        ),
                        'description' => __( 'Extracted keywords (if requested).', 'my-plugin' ),
                    ),
                ),
            ),
            'execute_callback'   => 'my_plugin_analyze_text',
            'permission_callback' => function() {
                return current_user_can( 'edit_posts' );
            },
        )
    );
}

/**
 * Execute callback for analyze-text ability.
 * 
 * @param $input
 * @return array
 */
function my_plugin_analyze_text( $input ) {
    $text = $input['text'];
    $include_keywords = $input['options']['include_keywords'] ?? false;
    
    // Perform analysis (simplified example)
    $sentiment = 'neutral';
    $confidence = 0.75;
    
    $result = array(
        'sentiment'  => $sentiment,
        'confidence' => $confidence,
    );
    
    if ( $include_keywords ) {
        $result['keywords'] = array( 'example', 'keyword' );
    }
    
    return $result;
}
```

### Ability Naming Conventions

Ability names should follow these practices:

- Use namespaced names to prevent conflicts (e.g., `my-plugin/my-ability`)
- Use only lowercase alphanumeric characters, dashes, and forward slashes
- Use descriptive, action-oriented names (e.g., `process-payment`, `generate-report`)
- The format should be `namespace/ability-name`

### Categories

Abilities must be assigned to a category. Categories provide better discoverability and help organize related abilities. Categories must be registered before the abilities that reference them using the `wp_abilities_api_categories_init` hook.

### JSON Schema Validation

The Abilities API uses JSON Schema for input and output validation. WordPress implements a validator based on a subset of JSON Schema Version 4\. The schemas serve two purposes:

1. Automatic validation of data passed to and returned from abilities
2. Self-documenting API contracts for developers

Defining schemas is mandatory when there is a value to pass or return.

### Using REST API Endpoints

Developers can also enable Abilities to support the default REST API endpoints. This is possible by setting the `meta.show_in_rest` argument to `true` when registering an ability.

```php
    wp_register_ability(
        'my-plugin/get-post-count',
        array(
            'label'              => __( 'Get Post Count', 'my-plugin' ),
            'description'        => __( 'Retrieves the total number of published posts.', 'my-plugin' ),
            'category'           => 'content-management',
            'input_schema'       => array(
                'type'       => 'object',
                'properties' => array(
                    'post_type' => array(
                        'type'        => 'string',
                        'description' => __( 'The post type to count.', 'my-plugin' ),
                        'default'     => 'post',
                    ),
                ),
            ),
            'output_schema'      => array(
                'type'       => 'object',
                'properties' => array(
                    'count' => array(
                        'type'        => 'integer',
                        'description' => __( 'The number of published posts.', 'my-plugin' ),
                    ),
                ),
            ),
            'execute_callback'   => 'my_plugin_get_post_count',
            'permission_callback' => function() {
                return current_user_can( 'read' );
            },
            'meta'               => array(
                'show_in_rest' => true,
            )
        )
    );
```

Access to all Abilities REST API endpoints requires an authenticated user. The Abilities API supports all WordPress REST API authentication methods:

- Cookie authentication (same-origin requests)
- Application passwords (recommended for external access)
- Custom authentication plugins

Once enabled, it's possible to list, fetch, and execute Abilities via the REST API endpoints:

**List All Abilities:**

```shell
curl -u 'USERNAME:APPLICATION_PASSWORD' \
  https://example.com/wp-json/wp-abilities/v1/abilities
```

**Get a Single Ability:**

```shell
curl -u 'USERNAME:APPLICATION_PASSWORD' \
https://example.com/wp-json/wp-abilities/v1/abilities/my-plugin/get-post-count
```

**Execute an Ability:**

```shell
curl -u 'USERNAME:APPLICATION_PASSWORD' \
  -X POST https://example.com/wp-json/wp-abilities/v1/abilities/my-plugin/get-post-count/run \
  -H "Content-Type: application/json" \
  -d '{"input": {"post_type": "page"}}'
```

The API automatically validates the input against the ability's input schema, checks permissions via the ability's permission callback, executes the ability, validates the output against the ability's output schema, and returns the result as JSON.

### Checking and Retrieving Abilities

You can check if an ability exists and retrieve it programmatically:

```php
<?php
// Check if an ability is registered
if ( wp_has_ability( 'my-plugin/get-post-count' ) ) {
    // Get the ability object
    $ability = wp_get_ability( 'my-plugin/get-post-count' );
    
    // Access ability properties
    echo $ability->get_label();
    echo $ability->get_description();
}

// Get all registered abilities
$all_abilities = wp_get_abilities();

foreach ( $all_abilities as $ability ) {
    echo $ability->get_name();
}
```

### Error Handling

Abilities should handle errors gracefully by returning `WP_Error` objects:

```php
<?php
function my_plugin_delete_post( $input ) {
    $post_id = $input['post_id'];
    
    if ( ! get_post( $post_id ) ) {
        return new WP_Error(
            'post_not_found',
            __( 'The specified post does not exist.', 'my-plugin' ),
        );
    }
    
    $result = wp_delete_post( $post_id, true );
    
    if ( ! $result ) {
        return new WP_Error(
            'deletion_failed',
            __( 'Failed to delete the post.', 'my-plugin' ),
        );
    }
    
    return array(
        'success' => true,
        'post_id' => $post_id,
    );
}
```

### Backward Compatibility

The Abilities API is a new feature in WordPress 6.9 and does not affect existing WordPress functionality. Plugins and themes can adopt the API incrementally without breaking existing code.

For developers who want to support both WordPress 6.9+ and earlier versions, check if the API functions exist before using them:

```php
<?php
if ( function_exists( 'wp_register_ability' ) ) {
    add_action( 'wp_abilities_api_init', 'my_plugin_register_abilities' );
}
```

### Or

```php
if ( class_exists( 'WP_Ability' ) ) {
    add_action( 'wp_abilities_api_init', 'my_plugin_register_abilities' );
}
```

### Further Resources

- [Abilities API GitHub Repository](https://github.com/WordPress/abilities-api/)
- [PHP API Documentation](https://github.com/WordPress/abilities-api/blob/trunk/docs/php-api.md)
- [REST API Documentation](https://github.com/WordPress/abilities-api/blob/trunk/docs/rest-api.md)
- [Hooks Documentation](https://github.com/WordPress/abilities-api/blob/trunk/docs/hooks.md)
- [Core Trac Ticket \#64098](https://core.trac.wordpress.org/ticket/64098)
- [Abilities API Handbook](https://make.wordpress.org/ai/handbook/projects/abilities-api/)
- [AI Building Blocks Initiative](https://make.wordpress.org/ai/2025/07/17/abilities-api/)
