# Block Attributes

One of the benefits of building blocks is the ability to allow users to control the block's appearance and behavior. This is done through block attributes.

Let's learn how to add attributes to a block, and now to add controls to your block to allow users to change those attributes.

## Adding attributes to a block

Attributes are the properties of a block that can be controlled by the user. For example, in the copyright date block, the starting year is an attribute that the user can change.

To add attributes to a block, you define them in the block's metadata in the `block.json` file.

Open the `block.json` file in the `src` directory, and add the following code:

```json
{
    "apiVersion": 2,
    "name": "core/paragraph",
    "title": "Paragraph",
    "category": "common",
    "attributes": {
      "startingYear": {
        "type": "string"
      }
    }
}
```