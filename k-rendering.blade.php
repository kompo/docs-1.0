@extends('app-docs',[
	'DocsSidebarL' => menu('DocsGeneralSidebar'),
	'DocsSidebarR' => menu('DocsSummarySidebar')
])

@section('doc-title', 'Rendering Komposers')
@section('seo-title', 'Display in Blade, use in Vue or call directly from Route.')

@section('doc-content')

<h2>Rendering options</h2>

<p>
	There are 3 ways to render Komposers. The 3 methods supplement each other and you may well need to use them together in your app.
</p>

<ol>
	<li>A <b>Direct Route call</b> may render the komposer:<br>
    <span class="color3">&nbsp; -</span> inside a <b>Kompo layout</b> <br>
    <span class="color3">&nbsp; -</span> or return a <b>JSON object</b>.
  </li>
	<li>Simply render in your <b>Blade template</b>.</li>
	<li>Use <b>inside a Vue component</b> for complex features.</li>
</ol>


<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<!-- ------------------------------------------------------------- -->
<h2>Route render</h2>

<h3>Render in layout</h3>

<p>
  If you have defined a Kompo <a href="{{ url('docs/menus') }}#displaying-the-menus" target="_blank">Blade layout template</a>, you may render komposers directly in the layout directly from the route using the `Route::kompo` macro.
</p> 


<code-tabs name1="routes/web.php" php1="use Kompo\Library\ChangePasswordForm;
use App\Http\Komposers\AnswerForm;

Route::layout('my-template')->group(function(){ 

   Route::kompo('profile/change_password', ChangePasswordForm::class);

   Route::kompo('questions/{question_id}/answer/{id?}', AnswerForm::class); 

   // ... you may add as many komposers as desired under the same layout
});"></code-tabs>


<p>
  The routes inside the `layout` group will display the form in the section `content` of the template along with any navbar or sidebars it may contain. 
</p>

<h4>Changing the section name</h4>

@tip(Our layout already contains the desired navigation menus (navbars, sidebars,...) and has a default section `content` for displaying komposers.)

<p>If however, you have another template and you wish to use another section name, you may chain the `section()` method to the kompo Route:</p>

<code-tabs name1="routes/web.php" php1="Route::layout('my-template')->group(function(){ 

   Route::kompo('profile/change_password', ChangePasswordForm::class)
      ->section('my-section'); //loading the Komposer in another section

});"></code-tabs>

<h3>Render as JSON</h3>

<p>
  When the route is NOT wrapped in any layout, it will return the Komposer as a <b>JSON object</b>. This is useful when doing AJAX requests and you wish to load the Komposer in Panels and/or Modals.
</p>

<code-tabs name1="routes/web.php" php1="use App\Http\Komposers\AnswerForm;

//Loading the komposer as JSON object:
Route::kompo('answer/{id?}', AnswerForm::class);"></code-tabs>

<p>Then, in another Komposer, we may call this route and display it in a Modal for example:</p>

<pre><code class="language-php">Button::form('Open Answer Form')
   ->get('answer', ['id' => 123])->inModal()</code></pre>


<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->
<h2>Blade render</h2>
<!-- ------------------------------------------------------------- -->

<p>
    The `render` method will directly generate the vue component for you. The first part is nothing more than syntactic sugar for the second one.
</p>

<pre><code class="language-html" v-pre>&lt;!-- In your blade template -->
&#123;!! App\Http\Komposers\MyForm::render() !!}
&#123;!! App\Http\Komposers\MyTable::render() !!}

&lt;!-- or -->

&lt;!-- Same thing as this -->
&lt;vl-form :vkompo="&#123;{ new App\Http\Komposers\MyForm() }}">&lt;/vl-form>
&lt;vl-query :vkompo="&#123;{ new App\Http\Komposers\MyTable() }}">&lt;/vl-query></code></pre>

@tip(Remember to place `&lt;vl-form>` and  `&lt;vl-query>` inside the bootable Vue.js element, which is usually the div with id `#app`.)

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->
<!-- ------------------------------------------------------------- -->
<h2>Usage in Vue</h2>

<p>
    The front-end components `vl-form` and  `vl-query` have one required prop `:vkompo` prop where you inject the instantiated PHP class. Kompo encodes it automatically to JSON.
</p>

<pre><code class="language-html" v-pre>&lt;vl-form :vkompo="&#123;{ new App\Http\Komposers\MyForm() }}">&lt;/vl-form>
&lt;vl-query :vkompo="&#123;{ new App\Http\Komposers\MyTable() }}">&lt;/vl-query></code></pre>

<p>Check out the {!! docsRoute('Vue usage section', 'docs.vu') !!} for more detailed info.</p>

@endsection