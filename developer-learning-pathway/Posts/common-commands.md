# Common commands

**Date:** 2025-01-22

**Status:** draft

| Command | Description |
|---------|-------------|
| `wp user list` | List all users on the site  
If you have many users you may wish to restrict the list to specific roles by adding `--role={ROLE}` |
| `wp user get {USER ID/NAME/EMAIL}` | Return a specific user |
| `wp user create {NAME} {USER EMAIL} --role={ROLE}` | Create a new user with the name, user email, and role defined  
This command will automatically create and return a secure password for the user  
This command does not create a matching WordPress.com user. If you are using [WordPress.com Secure Sign-On (SSO)](https://wordpress.com/en/support/wordpress-com-secure-sign-on-sso/) you should instead create the local user by inviting a user via *Users > Add New*. This will ensure a WordPress.com account and local user are both created. |
| `wp user update {USER ID/NAME/EMAIL --{KEY}={VALUE}` | Update a user  
Keys include `display_name`, `user_email`, `role`, and `user_pass` |
| `wp user reset-password {USER ID/NAME/EMAIL}` | Reset a user password with a random secure string  
This command can take multiple users if you put a space between each ID  
If you do not wish to notify users of password resets you can add `--skip-email` |
| `wp user add-role {USER ID} {ROLE}` | Add a role to a user  
This can be used to add default and custom roles |
| `wp user remove-role {USER ID} {ROLE}` | Remove a role from a user |
| `wp user delete {USER ID/NAME/EMAIL} --reassign={USER ID/NAME/EMAIL}` | Delete a user  
If you do not include the `--reassign={USER ID/NAME/EMAIL}` flag all content created by the user will be deleted |

</rewritten_file> 