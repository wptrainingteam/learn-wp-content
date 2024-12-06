# Plugin Directory Guidelines

## Introduction

The WordPress Plugin Directory is a repository of free plugins that are available for download and installation on WordPress sites. 

While anyone can upload their plugins to the directory, there are guidelines that must be followed to ensure that the directory is a safe place for all WordPress users – from the non-technical to the developer.

For this reason, all plugin developers must read and adhere to the guidelines before submitting their plugins to the directory.

In this lesson, you will learn about the expectations from you as a plugin developer, and the guidelines you must follow when submitting your plugin to the WordPress Plugin Directory.

## Developer expectations

As a plugin developer who would like to have their plugin submitted to the plugin directory, there are some expectations from you.

Developers, committers, and official supporters must comply with the [Plugin Directory Guidelines](https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/), the [WordPress Community Code of Conduct](https://make.wordpress.org/handbook/community-code-of-conduct/), and the [Support Forums Guidelines](https://wordpress.org/support/guidelines/) when applicable.

Not complying with these guidelines can result in:
- Plugins being removed or closed.
- Potential loss of plugin data, like reviews or code.
- A ban from hosting plugins for repeat violations.

Developers must keep their WordPress.org contact details up to date and responsive. Auto-replies or email systems that delay communication are not allowed.

Developers are responsible for writing secure code. Security issues can lead to plugins being closed or updated by the WordPress Security team in critical cases.

If unsure about guideline compliance, developers should email plugins@wordpress.org for clarification.

## Plugin guidelines

The guidelines for plugins submitted to the WordPress Plugin Directory are quite extensive. What follows is a summary of the key points.

## Plugins must be compatible with the GNU General Public License (GPL).

All code, data, images, and third-party resources in plugins hosted on WordPress.org must use a GPL or GPL-compatible license. Using the same license as WordPress (“GPLv2 or later”) is highly recommended. For compatible licenses, refer to the [list on gnu.org](https://www.gnu.org/philosophy/license-list.html#GPLCompatibleLicenses).

## Developers are responsible for the contents and actions of their plugins.

Plugin developers are responsible for ensuring all plugin files comply with the guidelines. Writing code to bypass the rules or re-adding removed code is prohibited. Before uploading, developers must confirm the licensing of all included files and comply with terms of use for third-party services and APIs. If licensing or terms cannot be verified, those resources cannot be used.

## A stable version of a plugin must be available from its WordPress Plugin Directory page.

WordPress.org only distributes the version of a plugin hosted in its directory. Developers must keep this version up to date, even if they develop elsewhere. Failing to do so or distributing outdated code via other methods may result in the plugin being removed.

## Code must be (mostly) human readable.

Plugins in the directory must not use obfuscation or unclear naming to hide code, as it creates unnecessary challenges for developers and is often associated with malicious behavior. Developers must provide public access to source code and build tools either by including them in the plugin or linking to the development location in the readme. Documenting how to use the tools is strongly recommended.

## Trialware is not permitted.

Plugins cannot lock features behind payments, upgrades, or trial periods, nor can they disable functionality after a quota or trial ends. Sandbox or test-only API access is also not allowed. However, paid services are permitted if all plugin code is fully available. Developers are encouraged to use external add-on plugins for premium features. Developer tools are reviewed individually. Upselling within plugins is allowed if it complies with admin experience guidelines.

## Software as a Service is permitted.

Plugins can interface with third-party services, including paid ones, if the service provides substantial functionality and is clearly documented in the plugin’s readme, ideally with a link to its Terms of Use. Prohibited services include:

- License/key validation services without adding substantial functionality.
- Services created by moving code out of the plugin to falsely claim added functionality.
- Storefronts that solely serve as product front-ends without offering a true service.

## Plugins may not track users without their consent.

Plugins must not contact external servers or collect user data without explicit, informed consent, typically through an opt-in method like registration or a checkbox. Documentation of data collection and use, including a privacy policy, should be included in the plugin’s readme.

## Prohibited practices include:

- Collecting user data without explicit confirmation.
- Misleading users into providing data as a condition for plugin use.
- Offloading unrelated assets like images or scripts to external servers.
- Poorly documented or undisclosed use of external data sources.
- Third-party ads that track usage or views.

Exceptions: Plugins integrating with Software as a Service (e.g., Twitter, Amazon CDN, Akismet) are considered to have implied consent upon configuration.

## Plugins may not send executable code via third-party systems.

Plugins may securely load code from approved external services, but they must not execute outside code for non-service purposes. Prohibited actions include installing updates, plugins, or themes from non-WordPress.org servers, using CDNs for anything other than fonts, and managing regularly updated data lists without explicit permission. All non-service JavaScript and CSS must be hosted locally. Using iframes to connect admin pages is discouraged; use APIs instead. Management services that push software updates are allowed only if these interactions happen on their own domain, not within the WordPress dashboard.

## Developers and their plugins must not do anything illegal, dishonest, or morally offensive.

Examples of prohibited behavior include:

- Manipulating search results or site traffic through unethical methods.
- Pressuring or misleading users into leaving reviews, or offering rewards for positive feedback.
- Pretending that free, included features require payment.
- Creating fake user accounts to fabricate reviews or support activity.
- Stealing or claiming another developer’s work as your own.
- Making false claims about legal compliance features.
- Using user resources for activities like crypto-mining without permission.
- Violating community standards, such as harassing others, breaking forum rules, or falsifying personal information to avoid sanctions.
- Exploiting loopholes to circumvent these guidelines.

## Plugins may not embed external links or credits on the public site without explicitly asking the user’s permission.

All credit displays or “Powered By” links included in plugin code must be optional and off by default. Users must explicitly choose to show these credits; they cannot be hidden behind vague terms or forced to display them for the plugin to work. If it’s a service, branding can appear on the service’s output, but not forced via the plugin’s code.

## Plugins should not hijack the admin dashboard.

Plugin notifications, prompts, and advertising should be minimal, contextual, and respectful of the WordPress admin experience. Any upgrade notices or alerts should be used sparingly, preferably appearing only in the plugin’s own settings page. If shown site-wide, they must be easy to dismiss and automatically clear once resolved. All error alerts should guide users on how to fix the issue and then disappear.

Overly intrusive or constant ads in the WordPress dashboard are discouraged and generally ineffective. Developers should avoid making it harder for users to manage plugins, and remember that referral tracking is not allowed. Including tasteful links to your website or social networks is welcome, but keep them user-friendly and clearly beneficial.

## Public facing pages on WordPress.org (readmes) must not spam.

Public-facing plugin pages must not be used for spam. This means:

- Don’t include unnecessary affiliate links, excessive or irrelevant tags, or use blackhat SEO tactics.
- Limit tags to five or fewer; use relevant terms, not competitor names.
- Required related products can be mentioned, but do so sparingly.
- Affiliate links must be clearly disclosed and must link directly (no redirects or cloaking).
- Write readmes for people, not search engines.

## Plugins must use WordPress’ default libraries.

Plugins must not bundle libraries already included in WordPress. Instead, they must rely on the versions packaged with WordPress for security and stability. This includes commonly used libraries like jQuery and SimplePie. Developers can refer to [WordPress’s documentation](https://developer.wordpress.org/reference/functions/wp_enqueue_script/#notes) for a full list of available default scripts.

## Frequent commits to a plugin should be avoided.

The WordPress SVN repository is for releasing ready-to-deploy code only. Each commit triggers a rebuild of the plugin’s zip files, so all submitted code—stable releases, betas, or release candidates—should be deployment-ready. Use clear, descriptive commit messages to help others understand changes. Avoid frequent minor tweaks and meaningless messages like “update,” as this strains the system and may appear as an attempt to manipulate the Recently Updated listings. An exception is updating the readme solely to indicate compatibility with the latest WordPress version.

## Plugin version numbers must be incremented for each new release.

Users are only alerted to updates when the plugin version is increased. The trunk readme.txt must always reflect the current version of the plugin.

## A complete plugin must be available at the time of submission

All plugins undergo review before approval, which is why a zip file is required. You cannot claim a directory name just to reserve it or protect a brand. If an approved plugin isn’t actually used, its directory name may be reassigned to another developer.

## Plugins must respect trademarks, copyrights, and project names.

Plugin names must not start with trademarks or brand names unless you have legal permission. If you don’t represent the brand, you need to name your plugin in a way that doesn’t imply affiliation. Using original, non-branded naming helps prevent confusion and is more memorable.

## WordPress.org reserves the right to maintain the Plugin Directory to the best of their ability.

WordPress.org reserves the right to update guidelines at any time, remove or disable plugins for reasons not explicitly stated, grant exceptions, reassign plugin ownership, and even modify plugins themselves for public safety. While they hold these powers, they pledge to use them sparingly, fairly, and with respect for both users and developers.

## Further reading

For the detailed list and explanations of the guidelines, refer to the [WordPress Plugin Directory Guidelines](https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/) page in the WordPress Plugin Developer Handbook.