# Submitting your theme to WordPress.org

When you've made sure your theme includes all the required files, passes the theme review guidelines, and you've thoroughly tested your theme, you're ready to submit your theme to the WordPress.org theme directory.

Let's dive into what this process looks like.

## Archive your theme

The process of uploading your theme for review requires you to upload a zip file of the entire local theme directory.

There are a number of ways to achieve this, from using the terminal to using your operating system's file manager.

For example, if you're using a Unix-based operating system, you can use the `zip` command in the terminal to create a zip file of your theme:

```bash
zip -vr new-theme.zip new-theme/
```

The zip file should contain the theme files inside the theme directory. 

Make sure not to include any files that are not part of the theme, such as version control files, package dependency files, or any other files that are not required for the theme to function.

## A note on the theme name

It's important to note that the theme name as it's registered on the WordPress.org theme directory, sometimes also called the theme slug, is determined by the name of the directory name of your theme when you create it.

So, for example, for the "Twenty Twenty-Four" theme, the directory name `twentytwentyfour`.

When a new theme is submitted, the theme name is automatically generated from the directory name. This name is then used everywhere in the theme directory, from the theme URL, to the location of the theme's code repository on WordPress.org.

It's therefore a good idea to choose a theme name that is unique, and that reflects the title of your theme.

## Upload your theme

Once you have the zip archive ready, you can upload it to the WordPress.org theme directory.

To do this, browse to https://wordpress.org/themes/upload/. 

You will be required to log in with your WordPress.org account. If you don't already have a WordPress.org account, you can create one by clicking on the **Create an account** link, and completing the required information.

Once you're logged in, you can upload your theme by clicking on the **Choose File** button, and selecting the zip archive of your theme.

You will also be required to confirm that you have permission to upload the theme, that it complies with the theme review guidelines, and that it is GPL compatible.

Once you've chceked all those boxes, click the **Upload** button to upload the theme for review.

## Theme review process

Once the theme is uploaded, a number of actions take place automatically.

The theme files are extracted, and the new theme is created in the WordPress.org theme directory. This means that the theme has a theme url, based on the theme name as well as an SVN repository for the theme code, all hosted in the WordPress.org infrastructure. The process will also run the theme through a series of automated checks, the same checks as in the Theme Check plugin.

The theme is then added to the review queue, where it will be reviewed by a member of the WordPress theme review team. This queue is managed in software called [Trac](https://trac.edgewall.org/), which is a bug tracking system used by the WordPress community.

A new trac ticket is opened, and the theme details are added to the ticket. The theme reviewer will then download the theme, and review it against the theme review guidelines.

If a theme ticket has no update from the theme reviewer within the first 48 hours after assignment, you can request that the theme is returned to the new queue, and a new reviewer is assigned.

Any communication between the theme reviewer and the theme author will take place in comments in the trac ticket, and the theme author will be notified of any issues that need to be addressed.

Trac automatically sends an email to the theme author when the ticket is updated, so it's important to keep an eye on your email for any updates. This will be the email you used when you registered your WordPress.org account.

If a theme ticket has no update from the theme author for 7 days it may be closed due to inactivity.

Once a theme passes all required checks, the reviewer marks the theme as approved. The theme is then added to a final review queue in trac, where a key reviewer will do the final review.

If the theme passes the final review, it is marked as live, and the theme will show up in WordPress.org theme directory.

## Further reading

You can read more about the theme review process in the [WordPress theme review team handbook](https://make.wordpress.org/themes/handbook/review/).