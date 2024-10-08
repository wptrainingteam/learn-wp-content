
# Good practices

While WordPress hooks are quite easy to begin with, it is however harder to master them.


## Naming hooks

The first point comes with naming hooks.

### Actions

As an action involves a change of state, I would advise you to use a verb within its name.

```php
do_action('delete_post');
```

Then if the action is happening before the actual event the convention is to prefix it with `pre_`.

```php
do_action('pre_delete_post');
```

Finally, if the action is after the actual event, it is advised to use a past temps inside the name.

```php
do_action('deleted_post');
```

### Filters

On the other side, a filter is a value and due to that it is advised to use nouns within its name.

```php
apply_filters('sitemap_url', 'https://example.org/sitemap.xml');
```

## Handling the filter type mess

When using a filter, the type from the value is never guaranteed even it is set in the docblock.

### The problem
There is a simple reason for that: It is the last callback that decides what is returned by the filter, and that also means the last callback decides the type from the value.

That might not seem as an issue at first, but you need to remember when you are creating your custom hooks that you are not the one who will be writing the callbacks but your users.



### The solution