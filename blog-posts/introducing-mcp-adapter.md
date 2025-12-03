# From Abilities to AI Agents: Introducing the WordPress MCP Adapter

One of the major new developer updates to come to WordPress 6.9 release was the **Abilities API**. You can read the full introduction to [The Abilities API](https://developer.wordpress.org/news/2025/11/introducing-the-wordpress-abilities-api/) to learn more about its design, and use cases, but it's a new way for plugins and themes to expose functionality in a standardized, cross-context manner.

One of the other major benefits of the Abilities API is that it **lays the groundwork for AI integration**. By defining abilities with clear input/output schemas and permission checks, plugins can make their functionality discoverable and callable by AI agents.

The **MCP Adapter** is the first official AI solution that builds on top of the Abilities API. It allows you to connect AI applications like Claude Desktop, Claude Code, GitHub Copilot, and other MCP enabled clients to any WordPress site, so that they can **discover and call those abilities directly**, as if your WordPress site were a built‑in AI toolset.

In this post, you'll learn what MCP is, how the WordPress MCP Adapter works, and how to get started exposing your abilities to AI agents.

## Quick recap: Abilities as the foundation

The Abilities API gives WordPress a **first-class, cross-context functional API** that standardizes how core, plugins, and themes expose what they can do.

You define an ability once with:

- A unique name (e.g. `list-all-urls/urls`)
- A typed **input schema** and **output schema**
- An **execute_callback** that does the work
- A **permission_callback** that enforces capabilities

Once registered, that ability is:

- Executable in PHP via `wp_get_ability()->execute()`
- Discoverable and callable via the Abilities REST API endpoint
- Usable in JavaScript via `@wordpress/abilities`

The **MCP Adapter** builds on this: it takes those abilities and **exposes them to AI agents** using the Model Context Protocol as tools, resources, and prompts.

## What is the Model Context Protocol (MCP)?

## What is the Model Context Protocol

The Model Context Protocol (MCP) is an open standard that defines a consistent way for AI applications to connect to external tools, systems, and data sources. It acts like a “universal adapter” between large language models and the services they need. Instead of writing custom integrations for each model and each API, developers expose capabilities through MCP servers and let any compatible AI client discover and call them. 

In practice, an MCP client (such as an AI-powered IDE or chat assistant) connects to one or more MCP servers, asks what operations and resources they provide, and then invokes those operations using a shared, well-defined protocol. This makes it much easier to build AI workflows that mix model reasoning with live data—files, databases, web services, or, in WordPress’s case, abilities registered via the Abilities API, without tying your integration to a single vendor.

## What is the WordPress MCP Adapter?

The **WordPress MCP Adapter** is an official package in the _AI Building Blocks for WordPress_ initiative. Its job is to bridge the **Abilities API** and the **Model Context Protocol (MCP)** so that AI agents can:

- Discover your abilities as **MCP tools**
- Read WordPress data as **MCP resources**
- Use structured **prompts** generated from abilities
- Communicate via **HTTP** or **STDIO** transports[1]

Key capabilities:[1]

- **Automatic ability → MCP conversion**
    - Every registered WordPress ability can become an MCP tool/resource/prompt.
- **Default MCP server out of the box**
    - A ready-to-use server that exposes all registered abilities.
- **HTTP and STDIO support**
    - Use HTTP for remote/production, STDIO (via WP‑CLI) for local/dev.
- **Error handling and observability**
    - Pluggable error and observability handlers for logging and monitoring.
- **Multiple servers & transports**
    - Create separate servers for staging vs production, or for specific plugins.

In practice, this means: **if your plugin already registers abilities, you are one step away from letting Claude or VS Code call them.**[1]

***

## Installing the MCP Adapter

The recommended way to use MCP Adapter in a plugin or theme is via Composer.[1]

### 1. Require the dependencies

From your plugin directory:

```bash
composer require wordpress/abilities-api wordpress/mcp-adapter
```

This installs:

- `wordpress/abilities-api` – the Abilities API package
- `wordpress/mcp-adapter` – the MCP bridge[1]

### 2. (Recommended) Use Jetpack Autoloader

If multiple plugins on a site depend on the MCP Adapter or Abilities API, use the Jetpack Autoloader to avoid version conflicts:[1]

```bash
composer require automattic/jetpack-autoloader
```

In your main plugin file:

```php
<?php
// Load the Jetpack autoloader instead of vendor/autoload.php.
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload_packages.php';
```

This ensures only one compatible version of shared packages is loaded across plugins.[1]

### 3. Initialize the adapter

In your plugin bootstrap file:

```php
<?php
use WP\MCP\Core\McpAdapter;

add_action( 'plugins_loaded', function () {
    if ( ! class_exists( McpAdapter::class ) ) {
        // Optionally show an admin notice for missing dependency.
        return;
    }

    // Initialize MCP Adapter and its default server.
    McpAdapter::instance();
} );
```

Once this runs:

- The **default MCP server** is registered.
- All abilities defined via `wp_register_ability()` become discoverable by MCP clients.[1]

***

## Example: Exposing the “List All URLs” ability to AI

The original Abilities API post introduced a `list-all-urls/urls` ability that wraps the `list_all_urls_generate_url_list()` function from the List All URLs plugin.

A simplified version of that ability registration looks like this:

```php
add_action( 'wp_abilities_api_init', 'list_all_urls_register_abilities' );

/**
 * Register the ability to list all URLs.
 */
function list_all_urls_register_abilities(): void {
    wp_register_ability(
        'list-all-urls/urls',
        array(
            'label'       => __( 'Get All URLs', 'list-all-urls' ),
            'description' => __( 'Retrieves a list of URLs from the WordPress site, optionally as clickable anchor links.', 'list-all-urls' ),
            'category'    => 'site',
            'input_schema'  => array(
                'type'       => 'object',
                'properties' => array(
                    'post_type'      => array(
                        'type'        => 'string',
                        'description' => 'Post type (post, page, or custom).',
                    ),
                    'posts_per_page' => array(
                        'type'        => 'integer',
                        'description' => 'Number of posts to retrieve. Use -1 for all.',
                    ),
                    'post_status'    => array(
                        'type'        => 'string',
                        'description' => 'Status to retrieve (publish, draft, etc).',
                    ),
                    'makelinks'      => array(
                        'type'        => 'boolean',
                        'description' => 'Return URLs as clickable anchor links.',
                    ),
                ),
            ),
            'output_schema' => array(
                'type'  => 'array',
                'items' => array(
                    'type'       => 'string',
                    'description'=> 'URL or clickable link to the URL.',
                ),
            ),
            'execute_callback'    => 'list_all_urls_generate_url_list',
            'permission_callback' => function () {
                return current_user_can( 'manage_options' );
            },
            'meta' => array(
                'show_in_rest' => true,
            ),
        )
    );
}
```

With the **MCP Adapter initialized**, this same ability is automatically exposed as:

- An **MCP tool** (callable by AI agents)
- Optionally, an MCP **resource** or **prompt**, depending on configuration[1]

No extra glue code is needed. The hard part—defining the ability—was already done in the previous post.

***

## Connecting Claude or other MCP clients (local STDIO)

For local development, the easiest way to connect an AI client to WordPress is via **STDIO** using WP‑CLI and the MCP Adapter.[1]

### 1. Start from the default MCP server

The adapter ships with a **default server** that automatically exposes all registered abilities via MCP.[1]

You can interact with it directly using WP‑CLI:

```bash
# List all available MCP servers
wp mcp-adapter list

# Start an MCP STDIO server for the default WordPress instance
wp mcp-adapter serve \
  --server=mcp-adapter-default-server \
  --user=admin
```

The `serve` command expects JSON-RPC requests over STDIO and responds using the MCP 2025‑06‑18 spec.[1]

### 2. Configure Claude Desktop (or Claude Code, Cursor, etc.)

Most MCP-aware tools accept a JSON configuration describing MCP servers. A typical STDIO configuration for a local WordPress site looks like this:[1]

```json
{
  "mcpServers": {
    "wordpress-default": {
      "command": "wp",
      "args": [
        "--path=/path/to/your/wordpress/site",
        "mcp-adapter",
        "serve",
        "--server=mcp-adapter-default-server",
        "--user=admin"
      ]
    }
  }
}
```

What this does:[1]

- Launches `wp mcp-adapter serve` from your WordPress root
- Uses the **default MCP server** (which exposes all abilities)
- Authenticates as the specified WordPress user (e.g. `admin`)

Security note:

- Treat the MCP client **as that user**. If you pass `--user=admin`, the AI can do anything that user can do, subject to each ability’s `permission_callback`.
- For production or shared environments, strongly consider using a dedicated low-privilege user and restricting which abilities you expose.

### 3. Using abilities from within Claude

Once configured, your AI client will:

- Discover tools like:
    - `core/get-site-info`
    - `core/get-environment-info`
    - `list-all-urls/urls`
    - Any custom abilities you registered[1]
- See schemas (input/output) for each tool
- Call them with structured arguments

Example workflows you can trigger from Claude:

- “Use `list-all-urls/urls` to fetch all published pages as clickable links, then group them into a sitemap proposal.”
- “Call `core/get-environment-info` and explain whether this server is suitable for enabling feature X.”[1]

Because tools are strongly typed via the Abilities API, Claude can reliably construct the right payloads and interpret the responses.

***

## Connecting from VS Code / Claude Code / Copilot-style tools

Editor integrations like **Claude Code**, **Cursor**, and next-generation Copilot-style tools are increasingly MCP-aware. They typically share the same MCP configuration format used above.[1]

To wire them up to WordPress:

1. Ensure `wp` is available on your system path.
2. Use the same STDIO configuration shown earlier, pointing `--path` to your WordPress install.
3. Restart the editor or AI extension so it reloads the MCP configuration.

From there, your editor AI can:

- **Inspect your running WordPress site** via abilities (e.g. `core/get-site-info`).
- **Fetch content** using a custom `my-plugin/get-posts` ability.
- **Cross-reference code and live data**:
    - “Look at the `List All URLs` plugin in this repo, then call `list-all-urls/urls` on the dev site and check whether the output matches the expected behavior.”

This lets you build workflows where the AI understands both **your codebase** and your **live site’s state**.

***

## Practical scenario 1: AI-powered content inventory with List All URLs

Using the List All URLs ability and MCP Adapter together, you can create a powerful **content inventory** workflow for audits, SEO, and migrations.[1]

### Step 1: Ensure the ability is registered

Use the `list-all-urls/urls` ability from the Abilities API article (or the variant above). This already exposes all URLs with optional filters and link formatting.

### Step 2: Initialize MCP Adapter

Initialize `McpAdapter::instance()` in your plugin so the ability is auto-exposed as an MCP tool.[1]

### Step 3: Connect an AI client via STDIO

Use the JSON MCP config pointing to:

```bash
wp mcp-adapter serve \
  --server=mcp-adapter-default-server \
  --user=admin
```

### Step 4: Drive the workflow from the AI client

In Claude or a compatible editor:

1. Ask the AI to **call** `list-all-urls/urls` with parameters like:
    - `post_type`: `any`
    - `posts_per_page`: `-1`
    - `post_status`: `publish`
    - `makelinks`: `false`
2. Have the AI:
    - Group URLs by path segment (e.g. `/blog/`, `/docs/`, `/products/`)
    - Flag content older than a specific date using additional abilities (for example, a `my-plugin/get-posts` ability returning dates)
    - Generate a redirect and pruning plan (e.g. for a site redesign)

All of this happens **without building a custom REST route, admin UI, or integration layer** beyond the ability registration.

***

## Practical scenario 2: Environment-aware debugging assistant

The MCP Adapter ships with core abilities such as `core/get-environment-info`, which expose environment diagnostics (PHP version, DB info, WordPress version, etc.).

With MCP Adapter initialized:

1. An AI client can list abilities and discover `core/get-environment-info`.[1]
2. It can call the tool and receive structured environment data like:
    - `environment` (production, staging, development, local)
    - `php_version`
    - `db_server_info`
    - `wp_version`

You can combine this with custom abilities to create an AI “debugging assistant” that:

- Fetches environment info
- Fetches plugin/theme lists via your own abilities
- Analyzes the data and suggests actions:
    - “You’re on PHP 7.4 but this plugin recommends PHP 8.1+.”
    - “Your database server version is X; this could affect feature Y.”

Again, no extra protocol code is needed—the MCP Adapter turns your abilities into tools AI agents understand.[1]

***

## Advanced: Creating a custom MCP server for your plugin

For more control, you can define your own **MCP server** that exposes only a subset of abilities, uses custom error handling, or has different transports.[1]

Example:

```php
use WP\MCP\Core\McpAdapter;
use WP\MCP\Servers\DefaultServerFactory;
use WP\MCP\Transport\HttpTransport;
use WP\MCP\Infrastructure\ErrorHandling\ErrorLogMcpErrorHandler;
use WP\MCP\Infrastructure\Observability\NullMcpObservabilityHandler;

add_action( 'mcp_adapter_init', function ( McpAdapter $adapter ) {
    $adapter->create_server(
        'my-server-id',                      // Unique server ID
        'my-namespace',                      // REST API namespace
        'mcp',                               // REST route
        'My MCP Server',                     // Display name
        'Custom MCP server for my plugin',   // Description
        'v1.0.0',                            // Version
        array(
            HttpTransport::class,            // Use HTTP transport
        ),
        ErrorLogMcpErrorHandler::class,      // Error handler
        array(                               // Abilities to expose as tools
            'list-all-urls/urls',
            'my-plugin/get-posts',
        ),
        array(),                             // Resources (optional)
        array(),                             // Prompts (optional)
        NullMcpObservabilityHandler::class   // Observability handler
    );
} );
```

This gives you:

- A dedicated server exposing **only the abilities you choose**
- An HTTP endpoint under your chosen namespace/route for MCP clients to use
- Freedom to run different configurations for different environments (e.g. read-only in production, broader access in staging)[1]

You can then point an MCP client to the HTTP endpoint using a remote MCP proxy (such as the `@automattic/mcp-wordpress-remote` package) configured with environment variables like `WP_API_URL`, `WP_API_USERNAME`, and `WP_API_PASSWORD`.[1]

***

## Security and best practices

Because MCP clients act as **logged-in WordPress users**, treat them as part of your application surface area:

- **Use `permission_callback` carefully**
    - Each ability should check the minimum capability needed (`manage_options`, `edit_posts`, etc.).
    - Avoid `__return_true` for destructive operations such as deleting content.
- **Use dedicated users for MCP access**
    - Especially in production, create a specific role/user with limited capabilities.
    - Do not expose powerful abilities to unaudited AI clients.
- **Prefer read-only abilities for public MCP endpoints**
    - For HTTP transports exposed over the internet, focus on read-only diagnostics, reporting, and content access.
- **Monitor and log usage**
    - Use custom error and observability handlers to integrate with your logging/monitoring stack.[1]

***

## How to start experimenting today

To recap, a minimal “hello AI” path for a WordPress developer looks like this:

1. **Define an ability** using `wp_register_ability()`, with clear input/output schemas and a safe `permission_callback`.
2. **Install and initialize the MCP Adapter** using Composer and `McpAdapter::instance()`.
3. **Connect an MCP-aware AI client** (Claude Desktop, Claude Code, VS Code extension, etc.) via STDIO using `wp mcp-adapter serve`.
4. **Let the AI discover and call your abilities**, and iterate from there.

If you already have plugins using the Abilities API, the MCP Adapter turns them into **AI-ready APIs** with very little additional work.[1]

This combination—Abilities API plus MCP Adapter—gives WordPress developers a powerful path to:

- Build **AI-assisted admin tools**
- Offer **AI-powered workflows** to clients and teams
- Keep WordPress at the center of content, code, and AI automation

And this is still just the beginning of what AI Building Blocks for WordPress are designed to unlock.

[1](https://developer.wordpress.org/news/2025/11/introducing-the-wordpress-abilities-api/)