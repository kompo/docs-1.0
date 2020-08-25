@extends('app-docs',[
	'DocsSidebarL' => menu('DocsGeneralSidebar'),
	'DocsSidebarR' => menu('DocsSummarySidebar')
])

@section('doc-title', 'Installation & About')
@section('seo-title', 'Let\'s install Kompo. Time is ticking ⏰...')

@section('doc-content')

<!-- ------------------------------------------------------------- -->
<h2>Introduction</h2>

<p>
	<b>Kompo</b> is a full-stack library of components that helps you write, in a matter of seconds, a lot of the redundant things a web application needs, such as: <i>forms, queries and menus</i>
</p>
<p>
	It is a <b>Rapid Application Development</b> framework: it pushes the principle of <b>coding by convetion</b> to the extreme while attempting to give the developer as much flexibility as possible.
</p>


<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<h2>Quick Installation</h2>

<p>
  <b class="color2">1-</b> Install the PHP package with composer:
</p>

<pre><code class="language-none">composer require kompo/kompo</code></pre>

<p>
  <b class="color2">2-</b> Pick and add a stylesheet to your `&lt;header>`:
</p>
<vl-tabs class="code-tabs">
	<vl-tab name="Default">
		<pre class="mt-0"><code class="language-html">&lt;link href="https://unpkg.com/vue-kompo/dist/app.min.css" rel="stylesheet"></code></pre>
	</vl-tab>
	<vl-tab name="MD Outlined">
		<pre class="mt-0"><code class="language-html">&lt;link href="https://unpkg.com/vue-kompo/dist/app-mdol.min.css" rel="stylesheet"></code></pre>
	</vl-tab>
	<vl-tab name="MD Filled">
		<pre class="mt-0"><code class="language-html">&lt;link href="https://unpkg.com/vue-kompo/dist/app-mdfl.min.css" rel="stylesheet"></code></pre>
	</vl-tab>
	<vl-tab name="Floating">
		<pre class="mt-0"><code class="language-html">&lt;link href="https://unpkg.com/vue-kompo/dist/app-float.min.css" rel="stylesheet"></code></pre>
	</vl-tab>
</vl-tabs>

<p>
  <b class="color2">3-</b> Add the scripts before your closing `&lt;/body>` tag:
</p>
<pre><code class="language-html">&lt;script src="https://unpkg.com/vue-kompo/dist/app.min.js"></script>
</code></pre>

<p>
	<b class="color2">4-</b> (Optional) To test that everything is working fine, create your first Form:
</p>

<pre><code class="language-none">php artisan kompo:form KompoDemoForm --demo</code></pre>

<p>And render it inside the div with id `#app`.</p>

<pre><code class="language-html">&lt;div id="app">
   &#123;!! App\Http\Komposers\`KompoDemoForm`::`render`() !!}
&lt;/div>
</code></pre>

@tip(The quick installation tells Vue to mount in id="app". To change that or any other parameter in the installation, you need to build the assets yourself - see below. )

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

    <h2>Full Installation</h2>

<!-- ------------------------------------------------------------- -->
<h3>Requirements</h3>

<p>
	The requirements for Kompo are:
</p>

<ul>
	<li>`Laravel 5.6+` application installed on your local server.</li>
	<li>`composer` to pull the vendor packages.</li>
	<li>`Node.js` + `npm` to build and pull the Front-End modules.</li>
	{{--<li>`Vue.js` front-end scaffolding as <a href="https://laravel.com/docs/master/frontend" target="_blank">described in Laravel's docs</a>.</li>--}}
</ul>

<!-- ------------------------------------------------------------- -->
<h3>Back-End setup</h3>

<p>
  Install `kompo/kompo` by running the following terminal command at your project's root folder:
</p>

<pre><code class="language-none">composer require kompo/kompo</code></pre>

<p>Then we take care of the Front-End setup.</p>

<!-- ------------------------------------------------------------- -->
<h3>Front-End setup</h3>
<!-- ------------------------------------------------------------- -->
<h4>Install the modules</h4>

<p>
  Next, you need to pull the front-end module into your development environment:
</p>

<pre><code class="language-none">npm install --save vue-kompo</code></pre>

<!-- ------------------------------------------------------------- -->
<h4>Import the javascript</h4>

<p>
  Once the install process is finished, you should import the javascript modules in your `app.js` . This will import the default bundle into your project and you will be able to use them everywhere in your Vue.js code.
</p>

<pre><code class="language-js">//app.js
window.Vue = require('vue');

//Requiring kompo after Vue has been required
require('vue-kompo')</code></pre>

<!-- ------------------------------------------------------------- -->
<h4>Import a style</h4>

<p>
  Kompo comes in many different styles. You may check out the different styles in the components page. Once you picked your prefered style, import the related scss code in your `app.scss`.
</p>

<pre><code class="language-scss">//app.scss -- Pick your favorite style

@import 'vue-kompo/sass/bootstrap-style';
//@import 'vue-kompo/sass/md-filled-style';
//@import 'vue-kompo/sass/md-outlined-style';
//@import 'vue-kompo/sass/floating-style';
</code></pre>

@tip(These styles can also be customized globally. Check <a href="{{ url('docs/css-styles') }}#themes-scss-global-styles">this page</a> out for more info.)

<!-- ------------------------------------------------------------- -->
<h4>Build the assets</h4>

<p>After that just compile the assets. </p>

<pre><code class="language-none">npm run dev</code></pre>

<p>And reference them in your template.</p>

<pre><code class="language-html" v-pre>&lt;!-- header -->
&lt;link href="&#123;{ `mix`('css/app.css') }}" rel="stylesheet">

&lt;!-- scripts -->
&lt;script src="&#123;{ `mix`('js/app.js') }}">&lt;/script></code></pre>

<p>
  You are now ready to start creating Komponents!
</p>

<!-- ------------------------------------------------------------- -->
<h3>Using with Tailwind</h3>

<p>
	Kompo works <b>really well with Tailwind CSS</b>! You will notice that Tailwind's well-defined classes and utilities really leverage the power of the Komponents based approach.
</p>

@tip(Be sure to import Tailwind <b>AFTER</b> vue-kompo styles; so that when you set Tailwind classes to Komponents, their default CSS is overriden.)

<pre><code class="language-scss">//app.scss

//Kompo's SCSS
@import 'vue-kompo/sass/bootstrap-style';

//Tailwind comes after and is able to override kompo defaults!
@import "tailwindcss/base";
//...
@import "tailwindcss/components";
//...
@import "tailwindcss/utilities";
</code></pre>

<p>
	Since the classes that appear later in the CSS file take precedence, you are able to override kompo's default CSS by adding Tailwind or Bootstrap classes to the component. For example, if you wish to change the default margins, you may append one of Tailwind's classes with the `class` method:
</p>

<pre><code class="language-php">// This will remove kompo's default top margin 
Input::form('Name')->class('mt-0')
</code></pre>

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

    <h2>About</h2>

<!-- ------------------------------------------------------------- -->
<h3><b>Open-source</b> & <b>Free</b></h3>

<p>
	Most of the components are created from scratch. Other more complex ones are cherrypicked, tested and integrated into a standardized system. But all of them are free and open source. The goal is that they continue to improve over time thanks to a combined community effort.
</p>

<p>
	The goal is to continue fine-tuning and enriching the library of components while keeping it free for developers - forever. However, donations are appreciated and will help us spend more of our time improving it.
</p>

<!-- ------------------------------------------------------------- -->
<h3>Feature requests & Bugs</h3>

<p>
	Please report any bugs in the issues tab of <a href="https://github.com/kompo/kompo">our github repo</a>.<br>
	We also welcome with open hands PR's for new components (form fields, catalog cards, menu items,...). If a component is approved by enough people from the community, we hope to add it to the library of available components for everyone to use.<br>
	You can also write to us at <a href="mailto:contact@kompo.io">contact@kompo.io</a> for any ideas or suggestions you may have.
</p>

<!-- ------------------------------------------------------------- -->
<h3>Kompo's mission</h3>

<h4>Improved developer happiness for experienced artisans</h4>
<p>
	By outsourcing the repetitive operations of writing HTML inputs, cards, tables, menus and the usual CRUD instructions, kompo aims to increase developer happiness by allowing him/her to focus on the relevant business logic. The goal is to get to a point where all a developer needs to do is set up the data model, configure the user input settings and see a result right away.
</p>

<h4>Breaking the barriers: Intuitive coding for beginners</h4>
<p>
	Kompo aims to break the barrier to entry for developers by making code more intuitive to write. It is currently a great starting point for beginners, who want to see results without necessarily going into tedious implementations. We also hope that one day it would be able to help school students who aspire to code, and even kids.
</p>

<h4>Rapid prototyping: buiding web SPAs as fast as it can get</h4>
<p>
	Whether it is to present a first draft to a client or simply tinkering with one of your ideas for a project, the goal is to offer you the fastest way to have a functional prototype up-and-running. The sooner the end-user tries out, the better the feedback. Less time is wasted in the process and everyone is happy :)
</p>

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

    <h2><i class="far fa-thumbs-up"></i> Improve this page</h2>

<p>
	To help us improve our docs, you may submit a PR to <a target="_blank" href="https://github.com/kompo/docs">our github repo for docs</a>. We really appreciate your contributions, thanks!
</p>

@endsection