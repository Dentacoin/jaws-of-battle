# Toolset Gutenberg Blocks

A set of blocks and core block extension for the WordPress Gutenberg editor.

## Contributing

### Getting started

You should have <a href="https://nodejs.org/en/">Node.js installed first</a>. You should be running a Node version matching the [current active LTS release](https://github.com/nodejs/Release#release-schedule) or newer for this plugin to work correctly. You can check your Node.js version by typing `node -v` in the Terminal prompt.

You should also have the latest release of <a href="https://npmjs.org">npm installed</a>, npm is a separate project from Node.js and is updated frequently. If you've just installed Node.js which includes a version of npm within the installation you most likely will need to also update your npm install. To update npm, type this into your terminal: `npm install npm@latest -g`

### Development

Open a terminal (or if on Windows, a command prompt) and navigate to the `toolset-blocks` directory inside Toolset Common. Now type `npm install` to get the dependencies all set up. Once that finishes, you can type `npm run dev`. The JS/SCSS compilers and linters will start working on the background. Start writing your code and as soon as it is saved the new compiled JS and CSS files will be stored inside the assets folder, in the `js` and `css` files respectively. 

### Architecture ###  

Each Toolset Gutenberg block, normal block or core block extensions, is self packed in a folder inside `toolset-blocks/blocks`. This folder contains at least:
* The PHP class that will instantiate the block and will also handle all the assets registering and enqueueing. For the case that the our block is a [Dynamic Block](https://wordpress.org/gutenberg/handbook/blocks/creating-dynamic-blocks/), this file can also contain the rendering callback. It is advised though to use the [Toolset AJAX](https://git.onthegosystems.com/toolset/toolset-common/blob/develop/inc/toolset.ajax.class.php) class for creating a proper rendering callback in order for it to be testable. See [this implementation](https://git.onthegosystems.com/toolset/toolset-common/blob/feature/views-1445/inc/autoloaded/ajax_handler/get_view_block_preview.php) for example.
* The main JS file, usually has the name `index.js`, that contains the block registration and all of its functionality. It's up to the developer to break the code into smaller pieces by creating and importing components. Keep in mind that this file needs to be registered and enqueued on the block PHP class.
* Even though it might not be necessary, a frontend JS file can be included, usually has the name `frontend.js` that contains all the code needed for the frontend part of the block.
* A folder named CSS, which contains all the styles for the block, both editor and frontend styles. This folder usually contains two files:
    * A file, which usually has the name `editor.scss` or `editor.css` which contains all the needed CSS for the block to render properly on the Gutenberg editor screen. For the case of a `.scss` file, the file needs to be imported inside the main JS file of the block in order to be compiled properly.
    * A file, which usually has the name `style.scss` or `style.css` which contains all the needed CSS for the block to render properly on the frontend. Again, for the case of a `.scss` file, the file needs to be imported inside the main JS file of the block in order to be compiled properly.
    
In order for the new block to be "compiled" properly, an entry point needs to be added in `webpack.config.js` inside the `entry` object. If the new block is called `dummy` then the entry should be 

```
'entry.block.editor': './blocks/dummy/index.js'
```

If the block also contains frontend scripts, then another entry should be inserted, which will be like 

```
'entry.block.frontend': './blocks/dummy/frontend.js'
```

For the block to be usable, the final step is to load it. To do so, the needed code needs to be added inside `Toolset_Blocks::load_blocks`. For example something like this:

```
// Load Toolset Dummy Gutenberg Block
$dummy_block = new Toolset_Blocks_Dummy();
$dummy_block->init_hooks();
```

After the block assets, JS and SCSS, are compiled, the will be moved to the proper folders inside `toolset-blocks/assets`

**REMINDER**

**Never touch the JS and CSS files inside the `toolset-blocks/assets` folder as they will be overwritten the next time you will run `npm run dev`.**