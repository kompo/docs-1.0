@extends('app-docs')

@section('doc-title', 'Kompo forms docs')
@section('seo-title', 'Single-class Self-handling Eloquent-ready Full-stack Forms 👌')

@section('doc-content')

<h2>Komposing a form</h2>

<!-- ------------------------------------------------------------- -->
<h3>Displaying Komponents</h3>

<p>
   The `komponents` method is where you define the Komponents that will be rendered on initial display. 
</p>

<pre><code class="language-php">&lt;?php

namespace App\Http\Komposers;

use Kompo\{Form, Input, MultiImage, SubmitButton};

class MyForm extends Form
{
   /*
   * We define the Form fields here:
   */
   public function komponents()
   { 
      return [
         Input::form('Title'),

         MultiImage::form('Images'),

         SubmitButton::form('Save')
      ];
   }
}
</code></pre>

@tip(You may also return a single Komponent if you have a short Form.)

<pre><code class="language-php">public function komponents()
{ 
   return Select::form('Title');
}</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>Using Layouts</h3>

<p>Layouts are a great way of organizing your Form fields and creating a wrapper for adding styles and classes.</p>

<pre><code class="language-php">public function komponents()
{ 
   return Rows::form( //wrapping two Komponents in a Layout

      Input::form('Title'),

      SubmitButton::form('Save')

   )->class('p-4 border border-gray-300'); //Adding styles to the wrapper
}</code></pre>

<h3>Loading AJAX Komponents</h3>

<p>
   You may also load additional Komponents by AJAX after the initial display, when a user performs an action for example. To do so, we need 3 things:
</p>

<ol>
  <li>A trigger: in the example below, the Button triggers the back-end request on Click with `getKomponents`, which specifies the method from which to fetch the new Komponents.</li>
  <li>A Panel where the new Komponents will be displayed.</li>
  <li>Finally, define the method that will return a new array of Komponents to be included in the Form.</li>
</ol>

<pre><code class="language-php">&lt;?php

namespace App\Http\Komposers;

use Kompo\{Form, Button, Panel, Input};

class MyForm extends Form
{
   public function komponents()
   { 
      return [
         Button::form('Load sector fields')
            ->getKomponents('sectorFields')  //Trigger & method
            ->inPanel(),

         Panel::form() //The new Komponents will be loaded here
      ];
   }

   /*
   * Returns additional Komponents by AJAX.
   */
   public function sectorFields()
   {
      //perform authorization here -- see related section

      return [
         Input::form('Sector'),
         Input::form('SubSector')
      ];
   }
}
</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>Submit trigger</h3>

<p>
    To indicate which komponent(s) will trigger the submit phase, you may chain the `->submit()` method to the komponent of your choice
</p>

<pre><code class="language-php">Link::form('Save me')->submit()
Select::form('Pick a plan')->submit()</code></pre>

<p>or simply use the `SubmitButton`.</p>

<pre><code class="language-php">SubmitButton::form('Save')
//Same as: Button::form('Save')->submit()
</code></pre>

<p>Note that, if no event type is specified, by default:</p>

<ul>
  <li>A <b>Trigger</b> komponent (Button, Link, ...) will submit <b>onClick</b>,</li>
  <li>A <b>Field</b> komponent (Input, Select, ...) will submit <b>onChange</b> after blur.</li>
</ul>

<p>
   However, you may also use other compatible handlers, such as <b>onLoad</b>, <b>onFocus</b>, <b>onBlur</b>... or <b>onInput</b> which debounces the request (submit while the user is typing):
</p>

<pre><code class="language-php">//Default is debounced 500ms
Input::form('Name')->onInput->submit()

//Debounced 800ms
Input::form('Name')->onInput->debounce(800)->submit()</code></pre>


<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->
<h2>Form parameters</h2>

<!-- ------------------------------------------------------------- -->
<h3>Class properties</h3>

<p>
   Form classes have specific properties that you may override to define set HTML attributes or specify some behavior. The following list represents the overridable properties: 
</p>

<pre><code class="language-php">class MyForm extends Form
{
    /*
    * HTML and Style properties
    */
    public $id = 'my-form-id'; //set the id attribute
    public $class = 'p-4 bg-gray-100'; //set the class attribute
    public $style = 'max-width:85vw;height:100px'; //set the style attribute
    public $noMargins = false; //By default, Fields have vertical margins. Disable with true

    /*
    * Submit properties
    */
    protected $preventSubmit = false; //If true: will not submit - only emit the form data.
    public $emitFormData = true; //If false: will not emit on submit.
    protected $failedAuthorizationMessage = 'You are not allowed to submit this form'; //If you wish to override the default message.

    /*
    * Response properties
    */
    protected $redirectTo = 'home'; //Redirects to 'home' route after successful submit.
    protected $redirectMessage = 'Success! Redirecting...'; //Displays a redirect message in Alert.
    protected $refresh = false; //If true: will refresh the form after an Eloquent submit.

    /*
    * Eloquent properties
    */
    public $model = Post::class; //Attaches a Model. See Eloquent Forms.
    protected $hideModel = true; //Hide or display the model instance in response.


}</code></pre>

<h4>Reserved keywords</h4>

<p>
   The following keywords are <b>used internally by Kompo</b> and you cannot use them in your Form. A considerable effort has been put to reduce this list as much as possible.
</p>

<pre><code class="language-php">class MyForm extends Form
{
    //CANNOT be used in your Class.
    public $vueComponent;
    public $bladeComponent;
    public $komponents;
    public $interactions;
    public $data;
    protected $_kompo;
}</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>Session store</h3>
<!-- ------------------------------------------------------------- -->

<p>
    You will encounter many cases when you will need a form with one or more external parameters. You may pass <b>an associative array of key/value variables</b> that is available for use in the Form and stored in the "session" for use during the submit phase too.
    For example, when a Form fills an Answer with a parent Question, we need to include the parent Question's id in the Form class:
</p>

<pre><code class="language-php">new AnswerForm(['question_id' => $questionId])</code></pre>

<p>
    In our <b>Form</b> class, we retrieve the stored data thanks to the `store()` method.
</p>


<pre><code class="language-php">//AnswerForm class
public function komponents()
{
   return [
     Hidden::form('question_id')
        ->value( $this->store('question_id') )  // <-- $this->store($key) 
     ...
   ];
}
</code></pre>

@tip(You may store any type of object or class in the store but it is recommended that you store only strings or integers so that the session does not grow too big in size.)

<p>
    The reason why each Form class has a <b>store</b> that leverages PHP's session is: since the same Form class is used both for displaying it and then handling it's submission, we need to persist some information on the server in between the two stages.
</p>

<!-- ------------------------------------------------------------- -->
<h3>Route parameters</h3>
<!-- ------------------------------------------------------------- -->

<p>
    If you need to use one of the routes parameters, you may retrieve them with the `parameter` method from anywhere in the Form. For example:
</p>

<pre><code class="language-php">//Route for displaying AddressForm
Route::get('questions/{question_id}/answer/{id?}', 'AnswerController@writeAnswer');

class AnswerForm extends Form
{
   protected $question;

   public function created()
   {
      $this->question = Question::find($this->parameter('question_id'));
   }

   ...
</code></pre>

@tip(The route parameters for displaying the Form are also usable during the submit phase, even though the route is a different one thanks to the session store.)


<!-- ------------------------------------------------------------- -->
<h3>Dependency injection</h3>
<!-- ------------------------------------------------------------- -->

<p>
   Another helpful pattern is using the `created` method as a sort of "constructor" where you instantiate important objects that are used throughout your Form. For example:
</p>

<pre><code class="language-php">new AddressForm(null, [
   'sector_id' => $sectorId,
   'subsector_id' => $subsectorId
])
</code></pre>

<p>And in the `created` method, you may instantiate your objects like so:</p>

<pre><code class="language-php">class AddressForm extends Form
{
   public function created()
   {
      $this->sector = Sector::find($this->store('sectorId'));
      $this->subsector = Subsector::find($this->store('subsectorId'));
   }

   ...
</code></pre>

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->
<h2>Types of Forms</h2>

<p>
    We can write three types of Forms depending on how much of the submission process we want to <b>handle ourselves</b> or <b>automate in Kompo</b>.
</p>

<table class="table table-sm text-sm">
  <thead>
    <tr class="color4">
      <th>Type of Form</th>
      <th>Description</th>
      <th>Submit phase</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="color2 font-bold lg:whitespace-no-wrap"><a href="#1-eloquent-form">1 Eloquent Form</a> <br><small class="color3">* Most automated</small></td>
      <td>We link an Eloquent Model to the Form Class (and optionally a model `id`).</td>
      <td>Kompo will save the Form inputs according to the Model's attributes and relationships.<br>We may add custom instructions in the Form's Eloquent lifecycle hooks.</td>
    </tr>
    <tr>
      <td class="color2 font-bold lg:whitespace-no-wrap"><a href="#2-self-handling-form">2 Self-handling Form</a></td>
      <td>We define a `handle()` inside our Form class.</td>
      <td>We handle DB saving ourselves in the `handle` method and return a response.</td>
    </tr>
    <tr>
      <td class="color2 font-bold lg:whitespace-no-wrap"><a href="#3-traditional-form">3 Traditional Form</a></td>
      <td>Submits to a Controller method that will handle the process outside of Kompo.</td>
      <td>We handle DB saving ourselves in our Controller and return a response.</td>
    </tr>
  </tbody>
</table>



<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->
<h2>1 Eloquent Form</h2>

<!-- ------------------------------------------------------------- -->
<h3>Form template</h3>

<p>
    To take advantage of Kompo's automated attributes and relationships saving, we assign the public `$model` property of the <b>Form</b> with the corresponding Eloquent Model class.
</p>

<pre><code class="language-php">&lt;?php

namespace App\Http\Komposers;

use Kompo\{Form, Input, Select, SubmitButton};

class EloquentForm extends Form
{
   /*
   * We link the Eloquent Model by specifying this property
   */
   public $model = App\Post::class;

   /*
   * Returns an array of Komponents.
   */
   public function komponents()
   { 
      return [
         Input::form('Title'), // saving the 'title' attribute

         Select::form('Tags') // saving the 'tags()' belongsToMany relationship
            ->optionsFrom('id', 'name'),

         SubmitButton::form('Save') //trigger submit
      ];
   }

   /*
   * Handles validation. See validation section for more info.
   */
   public function rules() { ... }

   /*
   * Handles submit authorization. See authorization section for more info.
   */
   public function authorize() { ... }
}
</code></pre>

<p>The first thing a Form will do after boot is to assign this property with the target Model. So if you wish to access the Model class at any time in your Form, you may use this property. For example:</p>

<pre><code class="language-php">public function authorize()
{
   return $this->model->user_id == \Auth::user()->id;
}</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>Lifecycle hooks</h3>

<p>
   On submit, an Eloquent Form goes through these important steps:
</p>

<ol>
    <li>Fill the attributes into the Model (and the belongsTo foreign key).</li>
    <li>Trigger the `beforeSave` hook.</li>
    <li>Save the model in the Database.</li>
    <li>Trigger the `afterSave` hook.</li>
    <li>Save the rest of the relationships.</li>
    <li>Trigger the `completed` hook.</li>
    <li>Trigger the `response` hook.</li>
</ol>

@tip(All these hooks are optional. Only use them if custom instructions are required.)

<pre><code class="language-php">&lt;?php

namespace App\Http\Komposers;

use Kompo\Form;

class EloquentForm extends Form
{
   /*
   * This method is fired at the very beginning of the cycle, 
   * both on display and submit.
   */
   public function created()
   {
      //perform some initializations
   }

   /*
   * Before saving the model with its attributes.
   */
   public function beforeSave()
   {
      $this->model->full_name = $this->model->first_name.' '.$this->model->last_name;
   }

   /*
   * After saving the model. It has an id now and we can save its relationships.
   */
   public function afterSave()
   {
      $this->model->assignRole('guest');
   }
   
   /*
   * Attributes and relationships have been saved.
   */
   public function completed()
   {
      event(new Registered($model));
   }
   
   /*
   * Return a custom response or redirect.
   */
   public function response()
   {
     return redirect()->back();
   }


   ...
}
</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>INSERT or UPDATE</h3>

<p>
    An Eloquent Form can be called with the new keyword and receives 2 <b>optional</b> parameters `$modelId` and `$store`. 
</p>

<pre><code class="language-php">new PostForm($modelId = null, $store = []);</code></pre>

<p>
  When no parameters are given, this will INSERT a new instance of the Model on submit.
</p>

<pre><code class="language-php">//This will INSERT a new Model
$myForm = new PostForm();</code></pre>

<p>
    If you wish to UPDATE the model, you may call the Form with the Model's `id` as the first parameter:
</p>

<pre><code class="language-php">//This will UPDATE the Model with id = 1.
$myForm = new PostForm(1);</code></pre>

<p>
    You may also use the same Form both for INSERT AND UPDATE (my prefered way). In this case, you may load an variable that can be null or equal to the id.
</p>

<pre><code class="language-php">//This will INSERT if $id is null
// and UPDATE if $id is not null.
Route::get('post-form/{id?}', ...);

//Form declaration
$myForm = new PostForm(request('id'));</code></pre>

<p>
    The second and last parameter of a Form is the store. This is useful for injecting parameters or dependencies into Forms. See "Session store" section for more info.
</p>

<pre><code class="language-php">//This will UPDATE the Model with id = 1,
//and inject a category_id parameter in the Form's store.
$myForm = new PostForm(1, [
   'category_id' => 42
 ]);</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>Eloquent helper methods</h3>

<p>
  When you need to retrieve your model or one of its' attributes in your form, you may use the helper methods `model` or `attribute`:
</p>

<pre><code class="language-php">class EloquentForm extends Form
{
   public static $model = Post::class;
   
   public function komponents()
   {
      return [
         Link::icon('reply')->post('slug', ['slug' => $this->model->slug]), //<-- the model's attribute value

         Rows::form(
            $this->model->comments->each(function($comment) { //You may access the Eloquent model like so
              return Html::form($comment->comment);
            })
         ),

         Button::form('Save')->submit()
      ];
   }
}</code></pre>


<!-- ------------------------------------------------------------- -->
<h3>Preconfigured redirect Route in the Form Class</h3>

<p>
  Alternatively, if you are using an Eloquent Form for example, you can assign the static `$redirectTo` property in your <b>Form</b> class. This will redirect to that route and display an optional redirect message.
</p>

<pre><code class="language-php">&lt;?php

namespace App\Forms;

use Kompo\Form;

class MyCustomForm extends Form 
{
  protected $redirectTo = 'home';

  //...
}
</code></pre>

  <h4>Redirect message</h4>
  <p>You can define a redirect message that will be displayed in the Form Status box using the static `$redirectText` property. Note that this string will be translated using the `__()` function if your app supports multiple languages.</p>

<pre><code class="language-php">&lt;?php

namespace App\Forms;

use Kompo\Form;

class MyCustomForm extends Form 
{
  protected $redirectText = 'Success! Redirecting...';

  //...
}
</code></pre>

  <p>In this example, the translated content will need to be configured in the `lang/{locale}.json` file with the key 'Success! Redirecting...'.</p>


<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->
<h2>2 Self-handling Form</h2>
<!-- ------------------------------------------------------------- -->

<!-- ------------------------------------------------------------- -->
<h3>Class template</h3>

<pre><code class="language-php">&lt;?php

namespace App\Http\Komposers;

use Kompo\Form;

class SelfHandlingForm extends Form
{
   /*
   * This method is fired at the very beginning of the cycle, 
   * both on display and submit.
   */
   public function created() { ... }

   //If you wish to handle the submit functionnality yourself. See submission section for more info.
   public function handle() { ... }

   /*
   * This is the only required method. It returns an array of the form's components.
   */
   public function komponents()
   { 
      return [];
   }

   //Handles validation. See validation section for more info.
   public function rules() { ... }

   //Handles submit authorization. See authorization section for more info.
   public function authorize() { ... }
}
</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>Submit & Redirect</h3>

  <p>If you wish to handle the submission yourself, you may do so inside the same Form class through the `handle` method. The <b>authorized and validated request</b> is available as a first parameter.</p>

<pre><code class="language-php">public function handle()
{
   //The $request is already "authorized" and "validated"
   //You may use it whichever way you wish
   dd(request()->all());
}
</code></pre>



<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->
<h2>3 Traditional Form</h2>


<!-- ------------------------------------------------------------- -->
<h3>Class template</h3>


<pre><code class="language-php">&lt;?php

namespace App\Http\Komposers;

use Kompo\Form;

class TraditionalForm extends Form
{
   /*
   * The submit Route or URI. If the route has no parameters.
   */
   protected $submitTo = 'my-route';

   /*
   * Optional - The submit request's Method if different than POST.
   */
   protected $submitMethod = 'POST';

   /*
   * If your Route has parameters, use a method instead of $submitTo.
   */
   public function submitUrl() 
   {
      return route('my-route', ['param' => 1]);
   }

   /*
   * This method is fired just after the Form is booted
   */
   public function created() { ... }

   /*
   * This is the only required method. It returns an array of the form's components.
   */
   public function komponents()
   { 
      return [];
   }
}
</code></pre>

<h3>Submit & Redirect</h3>
<!-- ------------------------------------------------------------- -->

<p>To submit a <b>Form</b> to a custom route and controller function, for example:</p> 

<pre><code class="language-php">Route::post('simple-route', 'ControllerA@methodA')->name('simple-route');
Route::post('parameter-route/{id}', 'ControllerB@methodB')->name('parameter-route');</code></pre>

<p>You may define the desired target route for submission in the `$submitTo` property or `submitUrl()` method of your <b>Form</b> class:</p>

<pre><code class="language-php">class MyForm extends Form 
{
  //For a Route with no parameters, you may use:
  protected $submitRoute = 'simple-route';
  
  //For a Route with parameters, you may use:
  public function submitUrl()
  {
     return route('parameter-route', ['id' => 'some-value']);
  }</code></pre>

<p>Then in your controller, you can retrieve the <a href="#validating-input">validated</a> and <a href="#authorizing-submission">authorized</a> `KompoFormRequest` (which is an extension of Laravel's native `Illuminate\Foundation\Http\FormRequest`).</p>

<pre><code class="language-php">use Kompo\Http\Requests\KompoFormRequest;

class MyController extends Controller
{
    public function myCustomForm(KompoFormRequest $request)
    {
        dd($request->all());
    }</code></pre>

<p>
  To redirect after a form submit is to use a Laravel's <a href="https://laravel.com/docs/master/responses#redirects" target="_blank">`RedirectResponse` instance</a>, meaning any one of these methods will work:
</p>

<pre><code class="language-php">use Kompo\Http\Requests\KompoFormRequest;

class MyController extends Controller
{
    public function myCustomForm(KompoFormRequest $request)
    {
        return redirect('home');
        // Any one of these would work too:
        //return redirect()->route('profile');
        //return back();
        //return url()->previous();
    }
}</code></pre>
@endsection
