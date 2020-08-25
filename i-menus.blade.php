@extends('app-docs')

@section('doc-title', 'Kompo menus docs')
@section('seo-title', 'Navbars, Sidebars, Footers & Other responsive Menu items')

@section('doc-content')

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<h2>Writing Menus</h2>

@tip(There is nothing better than examples to understand how to write Menus, so make sure to check out <a href="{{ url('library/navigation') }}" target="_blank">these templates</a> too.)


<!-- ------------------------------------------------------------- -->
<h3>Menu template</h3>

<p>
  The different navigation items are defined in the `komponents()` method of the <b>Menu</b> class. This class also contains useful properties for some navigation settings.
</p>

<pre><code class="language-php">&lt;?php

namespace App\Menus;

class MyMenu extends Menu
{
   public $fixed = true;   //If the menu fixed or scrollable?
   public $order = 1; //The order of display of menus, integer between [1-4]. 
   public $containerClass = 'container'; //Adding a wrapper div with a class to the menu.

   public $class = 'px-4 py-2 border-bottom border-gray-300';
   public $id = 'some-id';

   //This is the only required method. It returns an array of Komponents.
   public function komponents()
   {
      return [
         //The Menu Komponents
      ];
   }

   //This method is fired at the very beginning of the cycle.
   public function created() { ... }
}
</code></pre>

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<h2>SPA, Turbo links & SEO</h2>

<p>Kompo has been built with the objective of creating the SPA feel very easily.</p>

<!-- ------------------------------------------------------------- -->
<h3>Turbo Links</h3>

<p>Kompo is able to smart load pages for you. If you are using the same Kompo layout for two routes, Kompo will not do a full page refresh. Instead, it will simply swap the content and navigation items of the new page with the old ones, the same way turbolinks do.</p>

<h4>Disable turbo</h4>

<p>If a link leads to a page with the same layout, Kompo will automatically load the new content by turbo navigation. If for some reason, you wish to disable that, you may do so by chaining the `noTurbo` method to the link.</p>

<pre><code class="language-php">Link::form('Full refresh')->href(...)->noTurbo()</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>SEO Notice</h3>

<p>Unlike other `kompo` modules, it is important for navbars, sidebars and menus in general to be rendered on the server side for improved SEO. For that reason, the menu items have been built using <b>Blade</b> and not Vue.js.</p>

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

    <h2>Menu komponents</h2>

<p>
    Kompo offers an array of menu-specific components such as <b>Dropdown</b>, <b>CollapseOnMobile</b>, <b>Logo</b>... but you may also include any other components from the library such as <b>Html</b>, <b>Link</b>, <b>Button</b> or even form fields...
</p>

<pre><code class="language-php">public function komponents()
{ 
   return [
      Logo::form('&lt;b>Kompo&lt;/b>')->image('img/kompo-logo.png'),
      NavSearch::form('Search the docs...'),
      CollapseOnMobile::form('&#9776;')->leftMenu(

         Dropdown::form('Docs')->submenu(
            Link::form('Forms')->href('docs/forms'),
            Link::form('Queries')->href('docs/queries'),
            Link::form('Menus')->href('docs/menus')
         ),
         Button::form('Contact us')->post(...)->inModal()

      )->rightMenu(

         Auth::user() ?
            AuthMenu::form(Auth::user()->name)->icon('fa fa-user') :
            Link::form('Login')->get('login.modal')->inModal()

      )
   ];
}</code></pre>

  <h3>Logo</h3>
  @include('api.component-desc-doc',['component' => 'Logo'])
  <h3>Collapse</h3>
  @include('api.component-desc-doc',['component' => 'Collapse'])
  <h3>CollapseOnMobile</h3>
  @include('api.component-desc-doc',['component' => 'CollapseOnMobile'])
  <h3>Dropdown</h3>
  @include('api.component-desc-doc',['component' => 'Dropdown'])
  <h3>NavSearch</h3>
  @include('api.component-desc-doc',['component' => 'NavSearch'])

@endsection
