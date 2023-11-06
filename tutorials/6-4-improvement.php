[WordPress 6.4 Source of Truth
](https://nomad.blog/2023/10/09/wordpress-6-4-source-of-truth/)

## New TT4 theme

https://make.wordpress.org/core/2023/08/24/introducing-twenty-twenty-four/

## Create Block Theme

Theme creator tools right inside the editor!!!!

## Block hooks

https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/#block-hooks-optional

- Still very new!
- Only works on block theme templates, template parts, and block patterns. These must be loaded from the theme's files, and not from user changes.
- The block must NOT render anything from the save function, currently only seems to work with dynamic blocks (ie using PHP to render the block output)
- [List of core blocks](https://developer.wordpress.org/block-editor/reference-guides/core-blocks/)

Add render to block.json
```
"render": "file:./render.php"
```
Create render.php file
```
<div <?php echo get_block_wrapper_attributes(); ?> >
	<p><?php echo esc_html( $attributes['content'] ); ?></p>
</div>
```
Remove save function from block.js

## Rename Group blocks for improved organization

## useBlockEditingMode

https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockeditingmode
```
var useBlockProps = blockEditor.useBlockProps;
```
```
useBlockEditingMode('disabled');
```
## PHP Compatibility notes

https://make.wordpress.org/hosting/handbook/server-environment/#php