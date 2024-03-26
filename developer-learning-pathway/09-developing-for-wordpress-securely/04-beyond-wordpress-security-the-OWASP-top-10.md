You will learn about common vulnerabilities to consider when building a WordPress plugin or theme, examples of how to prevent each type of vulnerability, and where to find more information around developing plugins and themes securely.

## Common Vulnerabilities

Security is an ever-changing landscape, and vulnerabilities evolve over time. You just have to take a look at the [Open Web Application Security Project (OWASP) Top 10 list](https://owasp.org/www-project-top-ten/) to see how things have changed from 2017 to 2021.

The benefit of using WordPress as the base of your next development project, is that many of these vulnerabilities have already been addressed by the WordPress core team.

However, if you're building a custom theme or plugin that's going to process any user data, you will need to ensure that your code does not create any of the common security vulnerabilities discussed in this lesson.

In the [Introduction to securely developing plugins](https://learn.wordpress.org/tutorial/introduction-to-securely-developing-plugins/) tutorial, we looked at the 5 main security principles that you should follow when developing a custom plugin or theme.

1. Securing (sanitizing) input
2. Data validation
3. Securing (escaping) output
4. Preventing untrusted requests
5. Checking User Capabilities

To see these 5 principles in action, we'll be looking at the code of a badly coded form submission plugin, and fixing any security vulnerabilities we find.