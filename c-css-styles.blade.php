@extends('app-docs')

@section('doc-title', 'Kompo CSS Styles docs')
@section('seo-title', 'Infinite possibilities and eternal re-use - 🎨')

@section('doc-content')

<h2>CSS styles</h2>

<!-- ------------------------------------------------------------- -->
<h3>Komponents styles</h3>

<p>
    To style a specific component, you have multiple options.
</p>

<ol>
    <li>Either assign CSS classes using the `class` method or an id attribute using the `id` method.</li>
    <li>Or set the `$style` property as you would in the HTML attribute.</li>
    <li>Or override or extend the Komponent specific automatically generated class:<br>`'vl{Komponent Name}`.</li>
</ol>

<pre><code class="language-php">//1.a. Style from CSS by targeting id #my-input
_Input('Title')->id('my-input')
//CSS: #my-input{}

//1.b. Style from CSS by targeting class .text-input
_Input('Title')->class('text-input')
//CSS: .text-input{}

//2.a. Style in PHP with `class`
_Input('Title')->class('mb-6 text-gray-500')

//2.b. Style in PHP with `style`
_Input('Title')->style('border: 1px solid gainsboro')

//2.c. For Fields, you also have `inputClass` and `inputStyle`:
_Input('Title')->inputClass('mb-6 text-gray-500')
_Input('Title')->inputStyle('border: 1px solid gainsboro')

//3. Target the auto class 'vl{Komponent Name}' in CSS
_Input('Title') //This automatically has a .vlInput class
//CSS: .vlInput{}
</code></pre>


<!-- ------------------------------------------------------------- -->
<h3>Komposer styles</h3>

<p>
    You may set styling attributes to the Form, Query or Menu by leveraging the public `$id`, `$class` or `$style` properties of your Komposer class. 
</p>

@tip(This will target the <b>wrapper</b> element. It can also be useful for CSS rules that cascade down.)

<pre><code class="language-php">class MyForm extends Form
{
   public $id = 'my-custom-form-id';

   public $class = 'my-custom-form-class'; 
   //or..
   //public $class = 'p-4 text-center'; //add padding and center

   public $style = 'min-height:500px;background:red'; //add custom CSS 

   //...
}
</code></pre>


<!-- ------------------------------------------------------------- -->
<h3>Themes: SCSS global styles</h3>

<p>
  The form styles are highly configurable in scss. You can either <b>modify these variables</b> before you import your chosen theme or you can <b>simply create your own theme</b>! 
</p>

<p>The variable list for each of the 4 base themes are available here:</p>

<?php 
$stylesGitLink = [
  'https://github.com/kompo/vue-kompo/blob/master/sass/bootstrap-variables.scss',
  'https://github.com/kompo/vue-kompo/blob/master/sass/floating-variables.scss',
  'https://github.com/kompo/vue-kompo/blob/master/sass/md-filled-variables.scss',
  'https://github.com/kompo/vue-kompo/blob/master/sass/md-outlined-variables.scss'
];
?>

<ol>
  @foreach($stylesGitLink as $gitLink)
  <li><a href="{{$gitLink}}" target="_blank">{{ substr($gitLink, strrpos($gitLink, '/') + 1) }}</a></li>
  @endforeach
</ol>

<p>For example, let's say we want to modify some variables of the `md-filled` theme:</p>

@tip(Remove !default when you override any of the variables in your theme.)

<pre><code class="language-scss">
// We only modify the desired variables,
$form-field-margin-t: 1rem;
$form-field-margin-b: 1.6rem;
$form-control-bg: rgba(20, 20, 20, 0.5);
$form-control-placeholder-color: purple;
$form-control-border-radius: .5rem;
$form-control-border-color: black;

// ... before importing our desired theme pack.
@import 'vue-kompo/sass/md-filled-style';

</code></pre>

<p>
  🙏 If you create a nice theme, please do submit a PR to share it with the community!<br>
  🙏 And if you need some tweaks for your theme to work, don't hesitate to write to me or create a PR to suggest a change!
</p>

@endsection