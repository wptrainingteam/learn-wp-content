# Developer Tips: Converting your Shortcodes to Blocks

I can still vividly remember the first time I tried the block editor.

It was just after watching the WordCamp EU Paris Livestream, the one where Om interviewed Matt. Given that Europe is in a simialar timezone to South Africa, I could watch the chat live, instead of needing to catch up 12 hours later, like I usually did for State of the Word. Matt announced that the Gutenberg plugin was available in the WordPress plugin repository, and of course, I had to install it immediately and try it out. I could see the potential for innovating the editor experience almost instantly, and I was excited about the future of WordPress.

That was in 2017, yet it took me a full two years before I “learned JavaScript (more) deeply” and built my first blocks. My main hurdle was time. By 2017 I was a dad of two boys, running a business with my wife, freelancing through Codeable, and teaching jiu-jitsu, but I was dying to dive into block development. Rool forward a couple of years, and in 2020, I was given the opportunity to build the first set of blocks for the Seriously Simple Podcasting plugin, and I thoroughly enjoyed the experience. I’ve been a proponent of teaching other developers the power of blocks ever since.

Given that Seriously Simple Podcasting had been round for a while, the first blocks we built were based on our existing shortcodes. For plugin developers, shortcodes are great, but they don't have the best experience for users. Many of our plugin users where also fans of blocks, and could see how some of our most popular shortcodes would make great blocks, so it just made sense.

## Authors note

For the most of the code examples in this article from the Seriously Simple Podcasting plugin, I'm going to be focusing on the theory behind the implementation. The actual code implementation requires much more time and space than one article allows. There will be links to various pieces of documentation, both WordPress developer docs, and React and Mozilla MDN docs, which I recommend reading. 

Additionally, because of the amount of code that made up some of the blocks I was building, I’m going to simplify the actual code used. However, you can browse the repo at this point in time [via this url](https://github.com/CastosHQ/Seriously-Simple-Podcasting/tree/7286c7571b3240c0ac270ee5b8d2f700888c8bcc). Where I talk about the implementation I will also link directly to the actual files I’m referencing in the article.

That being said, I wrote this code over 2 years ago, while I was still learning to develop blocks myself, so there might be some things that could have been done better. I’m sure there are, but I can't go back and change them now.

Finally, this article will not guide you through the entire process of converting your existing shortcodes to blocks. It will, however, give you some tips and tricks that I wish I had known when I was first starting out.

## Not every shortcode should be a block!

Let’s get this out of the way, not every shortcode should be a block. The WordPress shortcode system is powerful and has its place. I certainly don’t see it being removed at any time in the future. Heck, even the block editor has a shortcode block.

[Image of shortcode block]

There are many cases where a shortcode still makes sense over a block. One of my favorite examples of a shortcode that doesn't need to be a block is the time shortcode that’s used on the Make WordPress blogs

```
[time relative]Tuesday 07:00 UTC[/time]
```

If you’ve never seen it in use before, this seemingly simple shortcode facilitates the meetings of all of the WordPress contributor teams. When used correctly, it will render the time in the reader's local time zone. This is extremely valuable for a globally distributed team of WordPress contributors, as it makes sure they can plan and schedule their meetings correctly.

While it would be possible to turn this into a block, it’s not something I would do, as there’s no real benefit for the writer to add this as a block.

When considering if your shortcode should be turned into a block, I’d suggest asking yourself the following questions:

- Does the shortcode allow for multiple pieces of functionality?  If the shortcode just does one thing and does it well, it can probably be left as a shortcode. The time shortcode above is a good example of this.
- Will making this into a block mean it’s easier to use? Generally, shortcodes that have loads of attributes, or combinations of attributes that render information differently make the best targets for block support.
- Does the shortcode render complicated HTML, that will make a huge difference to the user if they can see it in the editor? Often, this question will override your answers from the first two questions. A single-use shortcode or a shortcode that’s easy to use as-is might still make sense as a block if rendering its output in the editor makes life easier for the user.

I generally find if I can answer yes to any of these questions, it makes sense to convert the shortcode to a block.

Finally, I would add that when I say “convert to ” I don’t mean “replace with”. For backward compatibility purposes, your shortcodes should still exist and work. Especially if you have a popular product that folks have been using for a while. You wouldn't want an update to suddenly render all their content broken!

## Learning React

Before we go any further, if you want to build blocks, it's a really good idea to understand the basics of how React works. I'm not here to argue the pros and cons of choosing React for blocks, that ship has sailed. If you want to build on top of the framework, whether you use the React JSX route, or the plain JavaScript route, you will need to understand React fundamentals.

For my own purposes, I took the [React for Beginners course by Wes Bos](https://reactforbeginners.com/), and I can't recommend it enough. It's a great introduction to React, doesn't cost the earth, and supports purchasing parity, so it's cheaper depending on your geographic location. While the course itself is a little dated, the fundamentals of React haven't changed, so it's still a great place to start. It also has an active Slack community, which is very helpful for asking questions about how React works, or any of the new features.

## Intro to Create Block

I love developer tools that make complicated things easier. I’m on record as being a big fan of things like WP-CLI and Laravel’s Artisan. So when I started looking into block development, I was pleased to discover [the Create Block tool](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/).

Developed primarily by the same team of developers working on the block editor, Create Block allows you to quickly scaffold a new block from your terminal. The actual block code is fairly limited, it's just rendering a paragraph element, but it gives you a good base to start with.

Create Block was key to my early journeys into block development, as I could scaffold a new block and inspect the code, to see how it all fits together.

What I really appreciate about Create Block is that you only need to have Node.js and npm installed on your computer to use it. Fortunately, npm comes preinstalled when you install Node.js, and with the right tools, it’s fairly straightforward to install Node.js on any operating system.

I create a tutorial on Learn WordPress on how to [set up and use Create Block](https://learn.wordpress.org/tutorial/using-the-create-block-tool/).

Once you have Node.js installed, using Create Block to scaffold a new plugin is just a single command from inside your `plugins` directory.

```
npx @wordpress/create-block plugin-slug
```

This will create a plugin called `plugin-slug` in your `plugins` directory, with all the base files you need for block development.

There are a bunch of other options available to the command, which I highly recommend reading about [in the documentation](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/#usage) but one of my favorite recent additions is the `--no-plugin option`. 

Essentially what `--no-plugin` does is only scaffold the block code, which means you can use it to add block support to an existing plugin, or even a theme. I don’t recommend trying this out until you fully understand how to block code is registered for WordPress, but once you do, this is a very handy feature.

## Converting simple shortcodes that use default block edit/save functionality

I enjoy using Create Block so much, I recorded a 47-minute tutorial on it back in 2020, which I turned into a shorter series of tutorials for Learn WordPress. If you’ve never tried to convert a shortcode to a block, I recommend watching them first, to get a good foundation of what you’ll need to get going.

- https://learn.wordpress.org/tutorial/using-the-create-block-tool/
- https://learn.wordpress.org/tutorial/converting-a-shortcode-into-a-block/
- https://learn.wordpress.org/tutorial/styling-your-wordpress-block/
- https://learn.wordpress.org/tutorial/using-block-attributes-to-enable-user-editing/

Simply put, if you have a plugin with a shortcode that just renders a specific set of HTML, you use Create Block to scaffold a new block, copy the reelvant code from the scaffoled block to your plugin, and then convert the HTML from your shortcode into JSX to be returned in the block’s `edit` and `save` functions.

This was the process I followed when I started converting the Seriously Simple Podcasting plugin to support blocks.

The first block I created was the based on the [Player Shortcode](https://support.castos.com/article/134-ssplayer-shortcode) block.

The initial implementation of the player shortcode called the `load_media_player` function which originally used the built-in WordPress `wp_audio_shortcode` [function](https://developer.wordpress.org/reference/functions/wp_audio_shortcode/) and renders the audio player that’s included in WordPress, based on [mediaelement.js](http://www.mediaelementjs.com/)

In version [1.19.2](https://github.com/CastosHQ/Seriously-Simple-Podcasting/releases/tag/1.19.2) of the plugin we added a more feature rich player we dubbed the “HTML5 player”  that used a more [complicated markup](https://github.com/CastosHQ/Seriously-Simple-Podcasting/blob/master/templates/players/castos-player.php), [CSS](https://github.com/CastosHQ/Seriously-Simple-Podcasting/blob/master/assets/css/castos-player.css) for styling and [JavaScript](https://github.com/CastosHQ/Seriously-Simple-Podcasting/blob/master/assets/js/castos-player.js) for functionality like skipping forward and backwards.

The shortcode could be added to a podcast enabled post without any attributes, and it would render the player for that post.

Turning this shortcode into a block was an easier implementation than anything else I still had to do, I tackled this first.

I scaffolded a new plugin using Create Block, and then copied over the `package.json` file, and the entire `src` directory.

At the time, we were using grunt to minify our CSS and JavaScript assets for production use, so I had to merge the scaffolded [package.json](https://github.com/CastosHQ/Seriously-Simple-Podcasting/blob/7286c7571b3240c0ac270ee5b8d2f700888c8bcc/package.json) with what we already had.

Then I took the HTML from the player template, copied it verbatim over the JSX in the return function of the block's `edit` function.

Example HTML

```HTML
<div class="castos-player <?php echo $player_mode ?>-mode" data-episode="<?php echo $episode_id?>">
	<div class="player">
    <!-- player image -->
        <div class="player__body">
            <!-- player body -->
        </div>
    </div>
</div>
```

Example JSX

```js
edit: () => {
    return (
        <div class="castos-player <?php echo $player_mode ?>-mode" data-episode="<?php echo $episode_id?>">
            <div class="player">
                <!-- player image -->
                <div class="player__body">
                    <!-- player body -->
                </div>
            </div>
        </div>
    );
},
```

My next step was to replace any HTML attributes with DOM properties (for example changing `class="castos-player"` `className={castos-player}`), and convert any PHP variables into JavaScript ones. I'd have to somehow pass the variables like the `episode_id` and `player_mode` to the block, which I figured I could do using the `props` object passed to the `edit` or `save` functions.

```js
edit: (props) => {
    const episode_id = props.episode_id;
    const player_mode = props.player_mode;
    return (
        <div className="castos-player {player_mode}-mode" data-episode="{episode_id}">
            <div class="player">
                <!-- player image -->
                <div class="player__body">
                    <!-- player body -->
                </div>
            </div>
        </div>
    );
},
```

This was one of the early lessons I learned about JSX, it's very much like HTML, but it's not exactly HTML. However, because it's very similar to HTML, it's easy to convert existing HTML to JSX, and then make the necessary changes to make it work.

For the first iteration I also neeeded to wire up the CSS from the shortcode. At the time [I managed this using](https://github.com/CastosHQ/Seriously-Simple-Podcasting/blob/7286c7571b3240c0ac270ee5b8d2f700888c8bcc/php/classes/blocks/class-player-block.php) `enqueue_block_editor_assets` action, but today I'd probably do it using the style option in the `block.json` [metadata](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#style).

This code is now lost to time, but for the first iteration I just passed the variables as hardcoded attributes to the block's `props`, so that I could see it all working. I would figure out how to update the block code to make it possible for the user to choose the episode and player mode later.

Once I code the player code to render in the editor, I made sure to update the save function with the same code, so that the block would render the same way on the front end.

I was happily surprised to see it all work, and my player HTML rendered, with styling intact, in both the editor and the front end. 

This did however make the edit and save functions HUGE! And I knew that they would grow even more, because I was planning to add additional functionality to the block, like the ability to select the episode to be rendered. It was at this point I remembered learning about creating [custom React components](https://reactjs.org/docs/react-component.html).

## Creating custom components to reduce redundancy

Components in React are essentially a way to avoid repeating your code. If you've ever built blocks using things like BlockControls, or RichText, you're using a component. Components are great because they allow you to write once, and use everywhere. In this case, moving the Castos Player HTML to a component made complete sense, because then I could write and manage the code once, and reuse it for both the edit and save functions bo the block.

The first thing I did, was move the JSX for the player into a `/components/CastosPLayer.js` component, and update the variables to be passed in as props.

```js
class CastosPlayer extends Component {
    render() {
        return (
            <div className="{this.props.className} {this.props.player_mode}-mode" data-episode="{this.props.episode_id}">
                <div class="player">
                    <!-- player image -->
                    <div class="player__body">
                        <!-- player body -->
                    </div>
                </div>
            </div>
        );
    }
}
export default EditCastosPlayer;
```

Then I imported the component into the main block `index.js` file

```js
import CastosPlayer from "./components/CastosPlayer";
```
Finally, I used the JSX syntax to implement the component in my edit function, passing the variables as props to the component.

```js:

```js
edit: (props) => {
    const episode_id = props.episode_id;
    const player_mode = props.player_mode;
    const className = props.className;
    return (
        <CastosPlayer
            className="{className}
			episodeId={episode_id}
            playerMode={player_mode}
		/>
    );
},
```

If I remember correctly, at the time, I also had to pass the className variable to the component.

However, as the functionality for the edit function grew, I started realising that it would make sense to have a separate EditCastosPlayer component as well. So I did just that:

```js

import CastosPlayer from "./CastosPlayer";

class EditCastosPlayer extends Component {
    constructor({className, episodeId, playerMode}) {
        super(...arguments);
        this.state = {
            className: className,
            episodeId: episodeId,
            playerMode: playerMode,
        };
    }
    render() {
        return (
            <CastosPlayer
                className={this.state.className}
                episodeId={this.state.episodeId}
                playerMode={this.state.playerMode}
            />
        );
    }
}
export default EditCastosPlayer;
```

I used the `this.state` object to store any specific variables passed to the component. I'm not sure if this was the right way to do this, but it worked.

I then needed to import the EditPlayer component into the main block file:

```js
import EditPlayer from './components/EditPlayer';
```

My final block edit function ended up looking pretty tidy.

```js
edit: EditPlayer,
```

I found custom components to be so useful, that I ended up creating custom components for pretty much [everything eventually](https://github.com/CastosHQ/Seriously-Simple-Podcasting/tree/master/src/components), and it's something I still do today when I see code that's going to be repeated, or could be simplified by separating it into it's own component. 

## A note on importing packages and components

In the code examples above, you'll see I'm using the `import` keyword to import a specific component.

If you happen to be [looking through the code repo](https://github.com/CastosHQ/Seriously-Simple-Podcasting/tree/7286c7571b3240c0ac270ee5b8d2f700888c8bcc), in some places I import things from WordPress packages like this:

```js
const {Component} = wp.element;
````

Yet in other places I import like this.

```js
import {Component} from "@wordpress/element";
```

This is purely down to me not fully understanding how importing code works in JavaScript. In the first example, I'm importing the `Component` class from the global `wp` package, which exists whenever the block editor is active. In the second, I'm importing from the `@wordpress/element` package, which is a node dependency of the plugin. Either way, when the code is transpiled, it's amounts to the same thing. However, as I'm relying on JSX and the `npm build` step to transpile the code, I should have used the second method throughout.

## Using apiFetch to get WP data to power blocks

Of course, as the block functionality grew, I needed to get the data from the WordPress REST API. Because we wanted to allow the user to select the episode to be rendered, I needed to get a list of all the episodes. I used the [apiFetch](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-api-fetch/) package to do this.

The beauty of using apiFetch is that it's geared towards using the WordPress REST API, so all I had to do was import it into my code, and then pass it an endpoint of episodes, for it would return the data.

```js
import apiFetch from '@wordpress/api-fetch';
```

```js
const populateEpisodes = () => {
    let fetchPost = 'ssp/v1/episodes';
    apiFetch({path: fetchPost}).then(posts => {
        // do something with the returned posts
    });
}
```

# Using core-data to get WP data to power blocks

Something I didn't know about until recently, is that the [core-data](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-core-data/) package exists. It's a package that allows you to get data from the WordPress REST API, without having to use apiFetch.

The difference between using apiFetch and the core-data package, is that core-data is more similar to using WordPress' built-in functions to get data.

For example, this is what the code would look like using core-data to replicate the WordPress [get_users()](https://developer.wordpress.org/reference/functions/get_users/) function, to return a list of users from the WordPress site.

```js
import {useSelect} from "@wordpress/data";

const users = useSelect( ( select ) => {
        return select( 'core' ).getUsers();
}, [] );
```

This is pretty cool if the only data you need to query is core WordPress data, like users, posts, etc.

WordPress core contributor Adam Zieliński recently created an amazing course on Learn WordPress on how to use [core data](https://learn.wordpress.org/course/using-the-wordpress-data-layer/), which I recommend checking out.

## Conditional rendering

One of the other cool things I learned about why React is so powerful is the ability to conditionally render differnt things, based on the value of a boolen variable.

So for example, when the user added the Castos Player block to their post or page, they should first see a select box, populated with all the episodes fetch via the apiFetch call above.

```js
<div className={this.state.className}>
    Select podcast Episode
    <select ref={this.episodeRef}>
        {this.state.episodes.map((item, key) =>
            <option value={item.id}>{item.title}</option>
        )}
    </select>
    <button onClick={activateEpisode}>Go</button>
</div>
```

However, once they select the episode, and click the button to trigger the `activateEpisode` function, the select box should disappear, and the Castos Player should be rendered.

So, I created a boolean variable named `editing` in the EditPlayer component constructor, and set it to `true` by default. 

```js
class EditPlayer extends Component {
	constructor({className}) {
		super(...arguments);
		let editing = true;
```

Then, in the `activateEpisode` function, once the episode has been selected and the relevant episode data loaded, I'd set it to `false`.

Finally, I'd wrap the component's return in a conditional, and then either return the select code, or return the CastosPLayer component, depending on the value of `editing`.

```js
if (editing) {
    return (
        <div className={this.state.className}>
            Select podcast Episode
            <select ref={this.episodeRef}>
                {this.state.episodes.map((item, key) =>
                    <option value={item.id}>{item.title}</option>
                )}
            </select>
            <button onClick={activateEpisode}>Go</button>
        </div>
    );
} else {
    return [
        controls, (
            <CastosPlayer className={this.state.className}
                          episodeImage={this.state.episode.episodeImage}
                          episodeFileUrl={this.state.episode.episodeFileUrl}
                          episodeTitle={this.state.episode.episodeTitle}
                          episodeDuration={this.state.episode.episodeDuration}
                          episodeDownloadUrl={this.state.episode.episodeDownloadUrl}
            />
        )];
}
```

It's a tiny thing, but I was really pleased with that solution. It saved users the hassle of having to hunt down the episode ID, and enter it in a field in the block Sidebar somewhere. They could just add the block, select the episode, and it would render it right in the editor!

## Where to find help

I'd be lying if I said I manage to figure this all out by myself. Besides completing the React course, I found various resources extremely helpful.

Whenever folks ask me where to find help, my first suggestion is to try and find an experienced contributor to lean on. Through my connections in the WordPress community, I was able to make connections with a few folks who were willing to help me answer specific questions. I'm very grateful to them for their time and patience. Many Gutenberg contributors are sponsored full time to work on Gutenberg, and so I'd recommend trying to reach out to one of them via the [Make WordPress Slack](https://make.wordpress.org/chat/). I'm sure they'd be happy to help. 

You are also very welcome to reach out to me directly. I'm happy to help where I can, and if I don't have the answers, I'll do my best to either find out, or connect you with the right person. 

I'd also recommend reading the [Block Editor Handbook](https://developer.wordpress.org/block-editor/). It's a great resource for learning about the various components and packages that make up Gutenberg. But take the time to read it thoroughly, and then ask questions when you don't understand something. What I found really helpful was to try and solve the current problem with the info in the handbook, and then when I reached out to folks directly, to include my code, and the handbook link, so they could see what I'd tried, and then help me figure out what I was missing. This is imminently more helpful than just asking a generalised question without providing any context.

Last, but very much not least, look at other block code. Find plugins in the WordPress plugin repository that have implemented blocks, and dive into what they have done, and how they've solved problems. 