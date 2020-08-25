@extends('app-docs',[
	'DocsSidebarL' => menu('DocsGeneralSidebar'),
	'DocsSidebarR' => menu('DocsSummarySidebar')
])

@section('doc-title', 'Calling Komponents')
@section('seo-title', 'Create a beautiful komposition of chained Komponents...')

@section('doc-content')

<h2>Komponent types</h2>

<p>
    Before we start writing Komposers, we need to learn about Komponents and how to instantiate them. There are 4 categories of komponents:
</p>

<!-- ------------------------------------------------------------- -->
<h3><span class="text-gray-400">1</span> Fields</h3>

<p>
  A <b>Field</b> represents the user's input in the form. It send values to the back-end, performs its specific data transformation duties and syncs attributes and/or relationships with the database.
</p>

<pre><code class="language-php">Select::form('Tags')

Input::form('Enter your phone number')->name('phone_number')</code></pre>

<!-- ------------------------------------------------------------- -->
<h3><span class="text-gray-400">2</span> Layouts</h3>

<p>
  A `Layout` has child Komponents and allows us to display them in different arrangements, such as columns, tabs or flex boxes. They accept as many arguments as desired in the layout.
</p>

<pre><code class="language-php">//Displays into equal-width columns
Columns::form(
   Input::form('First name'),
   Input::form('Last name')
   //... 
)
</code></pre>

<!-- ------------------------------------------------------------- -->
<h3><span class="text-gray-400">3</span> Triggers</h3>

<p>
    A `Trigger` allows users to interact with the form and perform AJAX requests. 
</p>

<pre><code class="language-php">//Performs a POST request
Button::form('Save')->post('route', ['id' => 1])

//Redirects to login page
Link::form('Login')->href('login')</code></pre>

<h3><span class="text-gray-400">4</span> Blocks</h3>

<p>
    A `Block` does nothing but display HTML. However, you will use them a lot. Most common ones are Html, Title, Badge or Alert.
</p>

<pre><code class="language-php">Html::form('&lt;i class="icon">&lt;i> By clicking, you agree to sell your soul.')

Title::form('Edit your post')</code></pre>

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<h2>Instantiation</h2>

<!-- ------------------------------------------------------------- -->
<h3>The `::form` method</h3>

<p>
    To <b>instantiate</b> the komponent, we call static function `::form` on the desired class. The reason behind this technique is that it allows us to continuously chain methods that enrich the komponent (see Method Chaining in next section).
</p>

<pre><code class="language-php">//Namespaces always start with Kompo\...
use Kompo\Form;
use Kompo\Input;
use Kompo\Button;
use Kompo\Columns;

//or shorthand version:
//use Kompo\{Form, Input, Button, Columns};

class MyForm extends Form
{
   public function komponents()
   {
      return [
         //Non-layout components: the first parameter is always the label.
         Input::form('Enter your phone number'),
         Button::form('&lt;span>Save&lt;/span>'),  //<--You may pass it HTML too.

         //Layout components: you may add many child komponents as arguments.
         Columns::form(
            Input::form('First Name'),
            Input::form('Last Name'),
            ...
         )
      ];
   }
}</code></pre>

@tip(This is the recommended way of instantiating Komponents since it creates NO naming conflicts and is the most IDE-friendly.)

<!-- ------------------------------------------------------------- -->
<h3>Prefixed helpers</h3>

<p>
    There's another way of calling Komponents, depending on developer preference, that provides some concision advantages. You may use underscore '_' prefixed helper functions, which are constructed by prefixing an underscore to the Komponent's base class name.
</p>

<pre><code class="language-php">//No need to import namespaces or static ::form call
_Input('Enter your phone number')
_Button('&lt;span>Save&lt;/span>')
_Columns(
   _Input('First Name'),
   _Input('Last Name')
)
</code></pre>

<p>
  These functions offer two advantages:
</p>

<ul>
  <li>They rid us of the need of importing namespaces on top of our classes, which can sometimes become a crowded place.</li>
  <li>And remove the need to call the static `::form` method every time.</li>
</ul>

@danger(To use these functions, you have to be certain that your project has no already existing function with _ + Komponent name.)

<p>
  If your app already has such a named function, the kompo function will simply not be declared and will not be available.
</p>

@tip(Rest assured: A fresh installation of Laravel does not create any conflicts with these functions, neither do PHP internal functions.)



<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<h2>Komponents API</h2>

<p>
  There's a whole library of chainable Komponent methods that help playing with their HTML, create Front-end interactions or perform AJAX requests.
</p>

<p>Each komponent has it's own set of well-documented methods that can be specific to it, to it's type (field, layout, ...) or shared across all komponents.</p>

<a href="{{route('api')}}" target="_blank" class="vlBtn vlBtnSecondary">
  Check out all the available methods by Komponent
</a>

<!-- ------------------------------------------------------------- -->
<h3>Method Chaining</h3>

<p>
    The core principle of Kompo is to be able to declare komponents and all their required configuration in a single PHP expression. Here are some examples of how method chaining allows us to enrich komponents' properties and features:
</p>

<pre><code class="language-php">
Input::form('Your full name')
   ->name('full_name') //Setting the name attribute, 
   ->icon('icon-plus') //adding an icon,
   ->required()        //and making the field required.

Country::form('Pick a country')
   ->name('country')
   ->`default`('CA')   //Setting a default country.

//Image upload with automatic thumbnail creation.
Image::form('Profile pic')
   ->withThumbnail()
   ->extraAttributes(['collection' => 'profile'])

//Columns example with different grid widths...
Columns::form(

   Date::form('Contact date')->col('col-4'),
   Textarea::form('Subject')->col('col-8')

)->alignStart() //... and top alignment.</code></pre>

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<h2>Fields in depth</h2>

<!-- ------------------------------------------------------------- -->
<h3>Label & Name</h3>

<p>
    When a Field is instantiated, two things happen:<br>
    1) it is assigned a default label using the double underscore `__` helper function in Laravel;<br>
    2) set the name attribute of the field to the `snake_case($label)`.
</p>

<pre><code class="language-php">// This will have a label of __('First Name')
// and a name attribute of 'first_name'
Input::form('First Name')
</code></pre>

  <p>If however, the desired name attribute doesn't correspond to the snake cased version of the label, you may explicitely set it by chaining the `name()` method to the component:</p>

<pre><code class="language-php">Input::form('Enter your phone number')->name('phone_number')</code></pre>

<h3>Assigning a value</h3>

<p>
	To set a certain value to a field, for example, when dealing with hidden fields, you may chain the `->value($value)` method to the komponent:
</p>

<pre><code class="language-php">Hidden::form('category_id')->value(1)</code></pre>

<p>
  	There are many other useful methods for fields including toggling and backend requests. To see the full list, check out the <a href="{{route('component-api', ['component' => 'Input'])}}" alt="Form components API" target="_blank">form components api</a>.
</p>

<!-- ------------------------------------------------------------- -->
<h3>Attributes & Relationships</h2>

<p>
    In Eloquent Forms, the <b>names of the fields drive the DB saving process</b>. The field names should either match an attribute of the Model (DB column name) or a relationship method. This allows us to automatically: 
</p>

<ul>
    <li><b>On display</b> - assign the model's attributes and load the relationships into the fields values.</li>
    <li><b>On submit</b> - save the fields attributes to the model's DB table and sync/associate/save the relationships according to the methods defined in your Eloquent Model.</li>
</ul>

<p>
    For example, our Eloquent Model has a <b>title</b> attribute and a <b>tags</b> relationship:
</p>

<pre><code class="language-php">class Post extends Model
{
   //'title' is a table column

   public function tags() 
   {
      return $this->belongsToMany(Tag::class);
   }
}</code></pre>

<p>
    Our Form can now automatically save the title and sync the belongsToMany tags. Note that there is <u>NO need to specify the type of relationship</u> in the Form. Kompo infers them from your Model.
</p>

<pre><code class="language-php">class EloquentForm extends Form
{
   public static $model = Post::class;
   
   public function komponents()
   {
      return [
         Input::form('Title'), //<-- this field has a name of title, like the attribute.

         MultiSelect::form('Enter one or more tags')
            ->name('tags')  //<-- this name matches the belongsToMany tags() method in the model.
            ->optionsFrom('id','name'),

         SubmitButton::form('Save')
      ];
   }
}</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>Nested relationships</h3>

<p>You may also modify a Model's child attribute directly from its' Form (as long as it is a One-to-One relationship). To do so, you may use a nested `{relationship}.{attribute}` syntax (separated by a dot).</p>

<p>Let's say a <b>User</b> Model has one <b>Profile</b> Model. In the User Form, you may directly modify one of the Profile's attributes.</p>

<pre><code class="language-php">class UserForm extends Form
{
   public static $model = User::class;
   
   public function komponents()
   {
      return [
         Textarea::form('profile.about_me') // 'profile()' is the HasOne relationships
                                            // 'about_me' is a Profile attribute
         SubmitButton::form('Save')
      ];
   }
}</code></pre>

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<h2>Interactions & AJAX</h2>

<p>To perform an interaction, we need to assign two things: `1) an event trigger` and `2) an Action`. Here is a list of currently available event triggers:</p>

<ul>
  <li>`onClick`</li>
  <li>`onChange`</li>
  <li>`onFocus`</li>
  <li>`onBlur`</li>
  <li>`onInput` (debounced 500ms by default)</li>
  <li>`onLoad` </li>
  <li>`onEmit` (when a Vue event is emitted)</li>
  <li>`onSuccess` (when an AJAX request returns a success response)</li>
  <li>`onError` (when an AJAX request returns an error response)</li>
</ul>

<!-- ------------------------------------------------------------- -->
<h3>Writing interactions</h3>

<p>
  Let's say we have a Select and we want to submit the Form when it's value changes. We may write this multiple ways:
</p>

<pre><code class="language-php">//The short way: Higher Order Interactions
Select::form('Pick an option')->onChange->submit()

//Even shorter way: Default interactions
Select::form('Pick an option')->submit() //because Select's default interaction is onChange

//A longer way
Select::form('Pick an option')->onChange(function($e){
   $e->submit();
   //Allows us to add more instructions
})

//The longest way - multiple event trigger
Select::form('Pick an option')->on(['change', 'blur'], function($e){
   $e->submit();
   //Allows us to add more instructions
})
</code></pre>


<!-- ------------------------------------------------------------- -->
<h3>Default interactions</h3>

<p>Each Komponent (and Komposer - see later) have a default trigger which is the most used way the Komponent interacts. For example, the default interaction of a : 
</p>

<ul>
  <li>A Field is `onChange` by default.</li>
  <li>A Trigger (Button, Link) is `onClick` by default.</li>
  <li>A Panel is `onLoad` by default.</li>
  <li>An Input is `onInput` by default (debounced by 500ms).</li>
  <li>A Komposer (Form, Query) is `onSuccess` by default.</li>
</ul>

<!-- ------------------------------------------------------------- -->
<h3>Front-End Actions</h3>

<p>
  Actions can either be <b>pure Front-end interactions</b>, for example: hiding, toggling...
</p>
<pre><code class="language-php">Button::form('Toggle')
   ->toggleId('some-id') //toggles #some-id when clicked

Rows::form()->id('some-id') //the id of the toggled element
</code></pre>

<p>If you chain them, they will execute synchronously.</p>

<!-- ------------------------------------------------------------- -->
<h3>AJAX Actions & Chaining</h3>
<p>
  Or they can be <b>AJAX interactions</b> (asynchronous requests to the backend). For example, let's say we want to make a POST request to the backend and handle the response.
</p>

<pre><code class="language-php">//The short way: Default + Higher Order Interactions
Select::form('Pick an option')
   ->post('option-selected')
   ->onSuccess->inModal()
   ->onError->alert('Something went wrong')

//The long way - full control
Select::form('Pick an option')->on('change', function($e){
   $e->post('option-selected') //makes a POST request to url('option-selected')
     ->on('success', function($e){
        $e->inModal(); //displays the response in a Modal
     })
     ->on('error', function($e){
        $e->alert('Something went wrong'); //opens an alert
     });
})</code></pre>

<p>When you chain them, they execute <b>asynchronously</b>.</p>

@tip(The full list of actions each Komponent can do is available in the <a href="{{ url('component-api', ['component' => 'Select']).'#ajax-http' }}">Komponents API</a> and there is an post that explains <a href="{{ url('library/ajax') }}">AJAX interactions in depth</a> in the Examples section.)

@endsection