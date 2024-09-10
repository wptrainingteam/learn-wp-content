# Nested Blocks

## Introduction

One of the benefits of developing blocks is the ability to nest blocks within other blocks. 

This allows you to support more complex user requirements by combining multiple blocks together.

Let's take a look at how nested blocks work, and how you can create them in your own block plugins.

## Nested blocks in the Comments block

The Comments block is a good example of a block that uses nested blocks. 

When you add a Comments block to a theme template in the Site Editor, you'll see that it automatically includes other blocks, the Comments Title block, Comments Template, Comments Pagination and Comments Form.

These blocks are all nested within the Comments block, and are displayed in the Site Editor as a single block.

## Creating nested blocks

Nested blocks are created by using the InnerBlocks component of the @wordpress/block-editor package.


