# Working with custom post types



## Custom post types

By default, the WordPress template hierarchy for posts applies to custom post types.

This means that WordPress will use the single post template to display the content of a custom post type, and the archive post template to display a list of custom post types.

However, the template hierarchy can be overridden by creating custom templates for custom post types.

These custom templates can be created by adding a specific file to the theme's templates directory using the following naming convention:

- For a single custom post type: `single-{post_type}.html`
- For an archive of custom post types: `archive-{post_type}.html`

Where `{post_type}` is the name of the custom post type.

