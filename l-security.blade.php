@extends('app-docs',[
	'DocsSidebarL' => menu('DocsGeneralSidebar'),
	'DocsSidebarR' => menu('DocsSummarySidebar')
])

@section('doc-title', 'Authorization & Validation')
@section('seo-title', 'Guard your Database at the class and method level...')

@section('doc-content')

<!-- ------------------------------------------------------------- -->
<h2>Authorization</h2>

<p>Komposers have different gates for authorizing rendering or handling incoming requests:</p>

<ol>
  <li>Main Authorization</li>
  <li>Method-level authorizations.</li>
  <li>Route Middlewares.</li>
  <li>Boot Authorization.</li>
</ol>

<h3>Main Authorization</h3>

<p>
    The main authorization gate handles incoming requests to the main action of a Komposer:
</p>

<ul>
  <li>For `Forms`, this gate will protect <b>form submissions</b> (Eloquent or Self-handling Forms).</li>
  <li>For `Querys`, this gate will protect <b>browsing, filtering, sorting</b>.</li>
</ul>

<p>
 To authorize passing through the main gate, you may define an `authorize()` method, where you decide the security check for this action. For example, if you only want admins and the model's author to be able to perform the main action:
</p>

<pre><code class="language-php">public function authorize()
{
  return \Auth::user()->isAdmin() || $this->model()->user_id == \Auth::user()->id;
}
</code></pre>

@danger(By default, a Form has no `authorize()` method so it is <b>unprotected</b> (like controller actions). So make sure you do, if your specs require it!)

<h3>Method-level Authorizations</h3>

<p>Methods that handle incoming requests to Komposers perform Dependency injection. So to protect them, you simply need to add an extended `Illuminate\Foundation\Http\FormRequest` as a parameter. You may use:
</p>

<ul>
  <li>The general purpose `KompoFormRequest`.</li>
  <li>Or create your own custom `FormRequest` as we do in controller actions.</li>
</ul>

<h4>KompoFormRequest</h4>

<p>
  Kompo has a general use `Kompo\Http\Requests\KompoFormRequest` that extends Laravel's FormRequest and uses the Komposer's main `authorize()` method and the main validation `rules()` method.
</p>


<pre><code class="language-php">use Kompo\Http\Requests\KompoFormRequest;

class MyForm extends Form
{
   //... 

   //we protect this method by dependency injection of KompoFormRequest
   public function loadKomponents(KompoFormRequest $request)
   {
      return [
        _Textarea('Comment')
      ];
   }

   public function authorize() //KompoFormRequest will use this
   {
     //keeps the logic in the Komposer
   }

   public function rules() //KompoFormRequest will use this
   {
     //keeps the logic in the Komposer
   }
}</code></pre>

@tip(I prefer to use KompoFormRequest because it allows me to keep authorization and validation inside the Komposer class and not have to create a separate class.)

<h4>Your custom FormRequest</h4>
<p>You may also create and use a custom FormRequest as you usually do in Controller actions.</p>

<pre><code class="language-php">use App\Http\Requests\CustomFormRequest;

class MyForm extends Form
{
   //... 

   public function loadKomponents(CustomFormRequest $request)
   {
      return [
         _Textarea('Comment')
      ];
   }
}</code></pre>

<p>And you control authorization and validation from you new class `App\Http\Requests\CustomFormRequest`:</p>

<code-tabs name1="CustomFormRequest.php" php1="use Illuminate\Foundation\Http\FormRequest;

class CustomFormRequest extends FormRequest
{
   public function authorize()
   {
      return false;
   }

   public function rules()
   {
      return [];
   }
}"></code-tabs>

<h4>Failed authorization message</h4>

<p>By default, if the authorization fails - i.e. returns `false` - Laravel throws an `AuthorizationException` with a default message of 'This action is unauthorized'. If you wish to override this message, you may assign a value to the `$failedAuthorizationMessage` property in your Form:</p>

<pre><code class="language-php">public $failedAuthorizationMessage = 'A custom 403 message';</code></pre>

<p>Or alternatively, if you want to customize the message with a variable, you may add a `failedAuthorization()` method to your Form:</p>

<pre><code class="language-php">public function failedAuthorization()
{
   return 'Sorry, you are not allowed to modify '.$this->model->name;
}
</code></pre>

<h3>Route middlewares</h3>

<p>
    To protect Komposers rendered by direct Route call, the best way is to protect them with middlewares:
</p>

<code-tabs name1="routes/web.php" php1="//Only authenticated users can access these routes
Route::group(['middleware' => ['auth']], function(){

   Route::kompo('question/{id?}', QuestionForm::class); 

   Route::kompo('questions/{question_id}/answer/{id?}', AnswerForm::class); 

});"></code-tabs>

<h3>Boot Authorization</h3>

<p>
  The boot authorization is a gate at the very beginning of a Komposer's lifecycle. This gate has to return `true` to allow the code to continue processing, whether you are:
</p>

<ul>
  <li>Trying to display a Komposer,</li>
  <li>Or handling any incoming request to it.</li>
</ul>

<pre><code class="language-php">public function authorizeBoot()
{
   return $some_condition ? true : false;
}
</code></pre>

@danger(By default, the boot authorization is true! Because most of the time, we boot Komposers through routes or render them in Blade, and both are protected by middlewares. )

<p>Boot authorization is useful for displaying Komponents asynchronously. For example, you might create a Vue Component that displays the Komposer only when the user scrolls into view.</p>


<!-- ------------------------------------------------------------- -->
<h2>Validation</h2>

<h3>Main Validation</h3>

<p>
    Validating the input is very easy and uses <a href="https://laravel.com/docs/master/validation#available-validation-rules" target="_blank">Laravel's request validation rules</a>.<br> You just have to add the validation array to the `rules` method:

</p>

<pre><code class="language-php">public function rules()
{
   return [
      'first_name' => 'min:2|max:100',
      'last_name' => 'min:2|max:100',
      'nick_name' => 'required_without_all:last_name,first_name',
      'avatar|*' => 'sometimes|mimes:jpeg,jpg,png,gif|size:10000'
   ];
}
</code></pre>

<p>
    After an invalid form submit, an error response (coded 422) will be sent and the error messages will be displayed under the relevant components.
</p>

<p>The array syntax also works:</p>

<pre><code class="language-php">public function rules()
{
   return [
      'first_name' => ['min:2','max:100'],
      //...
   ];
}
</code></pre>


<h3>Method-level Validation</h3>

<p>Same as Method-level authorization, refer to that section for info.</p>

<h3>Komponent rules</h3>

<p>You may also assign validation rules directly to the Komponent.</p>

<pre><code class="language-php">Input::form('Title')->rules('required|min:3')

//or array syntax
Input::form('Title')->rules(['required','min:3'])
</code></pre>

<p>I only use this method for custom Komponents that I know will always require a certin set of rule. But for form submissions, I personnally prefer to keep all the rules grouped together in the Komposer's rule method.</p>

<h3>Recursive rules</h3>

<p>When your Komposer has other Komposers inside of it, the validation rules of child Komposers are transfered up to the parent when handling an incoming request.</p>

@endsection