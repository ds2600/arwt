<div class="image-container" style="margin:auto; width:100%;">
    <div align="center">
        <img src="/images/arwt-transparent.png" alt="ARWT Logo" width="200" height="200">
        <div class="caption">You can also use HTML/CSS on markdown pages.</div>
    </div>
</div>

# Hello ARWT!
### What is ARWT?
ARWT (Amateur Radio Website Template) is a basic web site template to help amateur radio operators make their sites a little prettier.  

Check out the ARWT [repo](https://www.github.com/ds2600/arwt) for more information.

### Why ARWT?
While we all enjoy the simplicity of plain HTML, it's nice to have a little style.  ARWT is a simple template that you can use to spruce up your amateur radio web presence.

### How do I use ARWT?
If you're reading this in a web browser and it looks all pretty, that means you've gotten ARWT up and running.  You'll want to make sure your call sign is being displayed in the upper left corner - if it's not, update your config file.

If that looks good, to start all you have to do is use markdown to edit the content in this file (it's in pages/home.md).

If you need help with markdown, you can check out [this handy guide](https://www.markdownguide.org/basic-syntax/) and play around with it on [Stackedit](https://stackedit.io).

### How do I add additional content using markdown?
1. Add your new markdown file to the <inline-code>pages/markdown</inline-code> directory.
2. Open the <inline-code>/config/menu.php</inline-code> file and follow the existing formatting to add a new entry to the menu.  Make sure the *url* matches the name of the file you created in step 1.
3. Save everything and you're done!  

### How do I add additional content using pure HTML?
1. Add your HTML file to the <inline-code>pages/html</inline-code> directory.
2. Open the <inline-code>/config/menu.php</inline-code> file and follow the existing formatting to add a new entry to the menu.  Make sure the *url* matches the name of the file you created in step 1.
3. Save everything and you're done!  

### How do I use the FCC ULS Search?
This is a little more complicated, but I will be updating with instructions soon.  

Since it's a relatively complex page, **it is recommended that you do not modify the *public/fcc-uls-search.php* file**.

### Anything else?
If you find any bugs or if there are specific features you'd like to see added, create a [Github](https://www.github.com/ds2600/arwt) issue. I'll likely eventually put a roadmap of features up, just for my own sanity.  

If you'd like to contribute, feel free to fork the repo and submit a pull request.  I'll review it and merge it in if it looks good.  I'm not a professional developer, so I'm sure there are plenty of things that could be done better.  I'm open to suggestions.  

The whole project is being release under the MIT License, basically you can do whatever you want with it with some minor caveats.  See the LICENSE file for more details. I'd appreciate you leaving the repo link in the footer, but it's not required.
