@extends('app-docs',[
	'DocsSidebarL' => menu('DocsGeneralSidebar'),
	'DocsSidebarR' => menu('DocsSummarySidebar')
])

@section('doc-title', 'Writing Komposers')
@section('seo-title', 'Komposers are like Controllers, but highly specialized.')

@section('doc-content')

<h2>What are Komposers?</h2>

<p>To put it simply, Komposers are <b>highly specialized Controllers</b>. </p>

<p>
	They are classes where you define Komponents along with some basic functionality such as route configurations, booting behavior, authorization or validation rules. And they will handle <b>AJAX requests made by their Komponents</b> according to their specialization. 
</p>

<p>
	There are 3 types of Komposers:
</p>

<ul>
	<li>`Form` &nbsp;komposers: They attach to a single Model and sync user input with the DB (and storage for File uploads, for example).</li>
	<li>`Query` komposers: They handle a query, a collection of Models (or plain arrays), browsing cards, paginating, filtering, sorting. etc...</li>
	<li>`Menu` &nbsp;komposers: They define Navigation menus (Navbar, Sidebar, Footer), perform turbo-navigation and are shared across different pages.</li>
</ul>


<h2>Generating templates</h2>

<!-- ------------------------------------------------------------- -->
<h3>Artisan commands</h3>

<p>
    To generate Komposers, the easiest way is to call the artisan commands below from the root directory of your project with the class name as a parameter. These commands will create a template for the class in the `app/Http/Komposers` directory of your application. 
</p>

<h4>Generating a Form</h4>

<pre><code class="language-none">php artisan kompo:form PostForm</code></pre>

<h4>Generating a Query</h4>

<pre><code class="language-none">php artisan kompo:query PostsQuery</code></pre>

<h4>Generating a Table</h4>

<pre><code class="language-none">php artisan kompo:table PostsTable</code></pre>


<div class="flex flex-col items-center sm:flex-row sm:justify-between sm:items-start sm:mb-6">
	<div class="w-full sm:flex-1 sm:mr-4">

		<h4>Generating a Menu</h4>

		<pre><code class="language-none">php artisan kompo:menu Navbar</code></pre>

		<h4>Nesting in subfolders</h4>

		<p>
		    You may also target a subfolder. For example, I like to put Menus in their own folder:
		</p>

		<pre><code class="language-none">php artisan kompo:menu Menus/Navbar</code></pre>

	</div>
	<img class="mt-4" src="{{asset('img/folder-structure.png')}}" alt="Kompo folder structure">
</div>

@tip(The folder structure convention is <b>NOT</b> technically required, it is just the default one kompo uses. You may organize your komposers however you wish.)

<p>Next, start creating your own Komposer by looking the specific docs of each one.</p>

@endsection