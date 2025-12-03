# How the WordPress database stores your Site Editor settings, styles, templates and patterns

**Date:** 2025-01-19

**Status:** draft

Recently someone asked a question about creating a pattern in the Site Editor and why it seemed to go missing when they created and applied a child theme.

I always forget how changes to Site Editor settings, styles, templates and patterns are saved, so it means doing a dive into the relevant tables to refresh my memory. I figured instead of wasting a few hours every time, I'd blog the details.

## Block theme component overview

As a quick review, the three main components that make up a block theme are

- theme.json (aka the global settings and styles)
- Templates and template parts
- Patterns

Many people don't realise this, but you can include classic theme features like adding custom styles in the `style.css` or custom functions in a `functions.php` file in a block theme. But that's a different blog post for another day.

Depending on your point of view, one of the benefits of block themes is that it's possible for users with the relevant permissions to edit all the above mentioned components of a block theme in the Site Editor, without needing to edit the original theme files. 

## Global Settings and Styles

In order to see where the Global Settings and Styles are stored, I'm going to edit the Global Styles of a WordPress site using the current Twenty Twenty-Five theme, and change the default font to Volkorn & Fire Code, and save the changes.

When you make changes to theme styles in the Global Styles interface and hit **Save**, the changes are saved to the `wp_posts` table in the WordPress database. Global Styles are stored as a specific custom post type.

Below is a breakdown of the important fields and the data that is stored for changes made to a theme's Global Styles.

- `post_content`: stores the updated Global Styles data, in JSON format
- `post_title`: stores a value of "Custom Styles"
- `post_name`: stores the slug of the post as "wp-global-styles-{theme-slug}", where {theme-slug} is the slug of the active theme
- `post_type`: stores the post type as "wp_global_styles"

To view this data, I'm using the database client TablePlus, and running the following MySQL query on the `wp_posts` table:

```sql
SELECT post_title, post_name, post_type, post_content FROM `wp_posts` WHERE post_type = 'wp_global_styles' and post_name = 'wp-global-styles-twentytwentyfive'
```

What you will notice is that the updated Global Styles data is only the changes I made to affect the default font for the theme.

```json
{
  "styles": {
    "typography": {
      "fontFamily": "var(--wp--preset--font-family--fira-code)",
      "fontSize": "var(--wp--preset--font-size--medium)",
      "letterSpacing": "-0.18px",
      "lineHeight": "1.5"
    },
    "blocks": {
      "core/post-author-name": {
        "typography": {
          "fontWeight": "300"
        }
      },
      "core/post-terms": {
        "typography": {
          "fontWeight": "300"
        }
      },
      "core/post-title": {
        "typography": {
          "fontWeight": "400",
          "letterSpacing": "-0.96px"
        }
      },
      "core/pullquote": {
        "typography": {
          "fontFamily": "var(--wp--preset--font-family--vollkorn)",
          "fontSize": "var(--wp--preset--font-size--x-large)",
          "fontWeight": "400"
        },
        "elements": {
          "cite": {
            "typography": {
              "fontFamily": "var(--wp--preset--font-family--fira-code)",
              "fontWeight": "300",
              "letterSpacing": "-0.14px"
            }
          }
        }
      },
      "core/quote": {
        "typography": {
          "fontFamily": "var(--wp--preset--font-family--fira-code)",
          "fontWeight": "500",
          "letterSpacing": "-0.18px"
        }
      },
      "core/site-title": {
        "typography": {
          "fontFamily": "var(--wp--preset--font-family--vollkorn)",
          "fontSize": "var(--wp--preset--font-size--x-large)"
        }
      }
    },
    "elements": {
      "button": {
        "typography": {
          "fontFamily": "var(--wp--preset--font-family--fira-code)",
          "fontSize": "var(--wp--preset--font-size--medium)",
          "fontWeight": "400",
          "letterSpacing": "-0.36px"
        }
      },
      "heading": {
        "typography": {
          "fontFamily": "var(--wp--preset--font-family--vollkorn)"
        }
      },
      "h1": {
        "typography": {
          "fontSize": "48px",
          "letterSpacing": "-0.96px;"
        }
      },
      "h2": {
        "typography": {
          "fontSize": "38px",
          "letterSpacing": "-0.96px"
        }
      },
      "h3": {
        "typography": {
          "fontSize": "32px",
          "letterSpacing": "-0.64px"
        }
      },
      "h4": {
        "typography": {
          "fontSize": "28px",
          "letterSpacing": "-0.56px"
        }
      },
      "h5": {
        "typography": {
          "fontSize": "24px",
          "letterSpacing": "-0.48px"
        }
      }
    }
  },
  "settings": {
    "typography": {
      "fontFamilies": {
        "theme": [
          {
            "name": "Vollkorn",
            "slug": "vollkorn",
            "fontFamily": "Vollkorn, serif",
            "fontFace": [
              {
                "src": [
                  "file:./assets/fonts/vollkorn/Vollkorn-Italic-VariableFont_wght.woff2"
                ],
                "fontWeight": "400 900",
                "fontStyle": "italic",
                "fontFamily": "Vollkorn"
              },
              {
                "src": [
                  "file:./assets/fonts/vollkorn/Vollkorn-VariableFont_wght.woff2"
                ],
                "fontWeight": "400 900",
                "fontStyle": "normal",
                "fontFamily": "Vollkorn"
              }
            ]
          },
          {
            "name": "Fira Code",
            "slug": "fira-code",
            "fontFamily": "\"Fira Code\", monospace",
            "fontFace": [
              {
                "src": [
                  "file:./assets/fonts/fira-code/FiraCode-VariableFont_wght.woff2"
                ],
                "fontWeight": "300 700",
                "fontStyle": "normal",
                "fontFamily": "\"Fira Code\""
              }
            ]
          }
        ]
      }
    },
    "isGlobalStylesUserThemeJSON": true,
    "version": 3
  }
}
```

This is one of the cool things about using the JSON format. When the theme is loaded, WordPress reads the `theme.json` file into memory, and merges any JSON for that theme stored in the `wp_posts` table, generating the final JSON Global Settings and Styles to be used for front end rendering. 

While the `wp_posts` table stores the JSON content, there's also data stored in the database that controls the relationship between the record in the `wp_posts_table` and the currently active theme. To dig further into this, let's also fetch the ID of the record from the table.

```sql
SELECT ID, post_title, post_name, post_type, post_content FROM `wp_posts` WHERE post_type = 'wp_global_styles' and post_name = 'wp-global-styles-twentytwentyfive'
```

In this case, my local ID is 5.

Theme data and active theme relationship 