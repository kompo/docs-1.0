@extends('app-docs')


@section('doc-title', 'Testing Komposers and Komponents')
@section('seo-title', 'Keep your code under control, otherwise it will control you 🧐')

@section('doc-content')

<p>
  	It is very easy to test Komposers using Laravel's already existing extensive testing tools. Here, we will give guidelines and examples on how to test your components every step of the way.
</p>

<!-- ------------------------------------------------------------- -->
<h2>Browser tests</h2>

<h3>Laravel Dusk</h3>

<p>
    Laravel Dusk provides a long list of methods for checking the existence of elements, manipulating input values and interacting with links and buttons - and they all work with <b>Kompo Forms, Queries and Menus</b>. We will take a look at an example for a Form.
</p>

<p>
    First, start by installing <a href="https://laravel.com/docs/master/dusk" target="_blank">Laravel Dusk</a> and generate a first test template.
</p>

<h3>The Dusk selector</h3>

<p>
    Kompo provides the `dusk` method that you can chain to components to add a dusk selector.
</p>

<pre><code class="language-php">Button::form('Save')->dusk('my-form-btn')</code></pre>

<p>
    This will add a `dusk` attribute to the button element:
</p>
<pre><code class="language-html">&lt;button dusk="my-form-btn">Save&lt;button></code></pre>

<p>
    Then in your tests, you may easily point to this elements with the `@my-form-btn` selector.
</p>

<pre><code class="language-php">public function testExample()
{
    $this->browse(function (Browser $browser) use($data) {
        $browser->visit('/') 
                ->click('@my-form-btn');
    });
}</code></pre>

<h3>Content assertions</h3>

<p>
    Let's say we want to test that the form is displaying correctly on the home page. Our form has a title, a button and two required attributes: 'title' and 'published_at':
</p>

<pre><code class="language-php">public function komponents()
{
    return [
        Title::form('Edit post'),
        Input::form('Title')->required(),
        Date::form('Published At')->required(),
        Button::form('Save')
            ->dusk('my-form-btn')
    ];
}</code></pre>

<p>
    We may then create a Test that asserts seeing our Form's title. This test will pass and assures us that (part of) our Form is correctly displaying on the browser.
</p>

<pre><code class="language-php">namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomeSimpleFormTest extends DuskTestCase
{
    public function testFormTitleDisplayed()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    //optional, pause for longer if necessary
                    //->pause(1000)
                    ->assertSee('Edit post');
        });
    }
}</code></pre>

<h3>Input validations</h3>

<p>
    Now let's say we want to test that the form we are displaying on the home page does the proper input validations.
</p>

<p>
    Check out <a href="https://laravel.com/docs/master/dusk#using-forms" target="_blank">Laravel's input filling methods</a> to see all the methods you may use for interacting with Form elements. 
</p>

<pre><code class="language-php">namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomeSimpleFormTest extends DuskTestCase
{
    public function testPublishedAtValidation()
    {
        $data = [
            'title' => 'Edit post'
        ];

        $this->browse(function (Browser $browser) use($data) {
            $browser->visit('/')
                    ->type('title', $data['title'])
                    ->click('@simple-form-btn')
                    ->assertSee('The published at field is required'); //Validation error
        });
    }
}</code></pre>

<p>
    This test proves that an empty `published_at` field will display an error message with the proper information.
</p>

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<!-- ------------------------------------------------------------- -->
<h2>HTTP tests</h2>

<p>
    To test form submissions, query browsing and filtering, and other HTTP requests made directly to Komposers, we need to add a helper Trait to our Tests. </p>

<h3>KompoTestRequestsTrait</h3>

<p>
    Every request made to a Komposer contains important encrypted headers that uniquely identify the class targeted by the request as well as store and route paramters information used in the initial call.
</p>

<p>
    In the <b>prepare phase</b> of the test, we need to call the Komposer - in this case a Form. Then in the <b>assert phase</b>, we may perform one of the requests from the Trait.
</p>

<pre><code class="language-php">use Kompo\Tests\KompoTestRequestsTrait;

class MyFormTest extends EnvironmentBoot
{
   use KompoTestRequestsTrait; //we include the Kompo test helpers

   /** @test */
   public function receive_valid_input_from_request()
   {
      //test content
   }</code></pre>

<p>We may use any of the below methods to perform a well-defined Kompo action:
</p>

<pre><code class="language-php">protected function getKomponents($komposer, $method)

protected function submit($komposer, $data = [])

protected function browse($komposer, $data = [], $sort = null, $page = null)

protected function searchOptions($komposer, $search, $method)

protected function selfGet($komposer, $method, $data = [])

protected function selfPost($komposer, $method, $data = [])

protected function selfPut($komposer, $method, $data = [])

protected function selfDelete($komposer, $method, $data = [])

protected function submitToRoute($form, $data = [])

protected function kompoAction($komposer, $action, $data, $headers = [], $method = 'POST')</code></pre>


<!-- ------------------------------------------------------------- -->
<h3>Form submit example</h3>

<p>
    Let's say we want to test that the validation is working correctly for a simple POST submit request from a Form with an Input component. In the prepare phase, we call our Form and instantiate a variable with the value that needs to be tested. Then we can make assertions on the response's content.
</p>

<pre><code class="language-php">use Kompo\Tests\KompoTestRequestsTrait;

class MyFormTest extends EnvironmentBoot
{
   use KompoTestRequestsTrait; //This trait has the ->submit() method

   /** @test */
   public function receive_valid_input_from_request()
   {
      //Prepare phase
      $testInput = 'valid-input';
      $form = new MyTestForm();

      //Assert phase
      $this->submit($form, [
         'input' => $testInput
      ])
      ->assertStatus(200) // or status 201, if the model is newly created
      ->assertJson([
         'input' => $testInput
      ]);
   }</code></pre>

<p>
    Now let us write a test to make sure the submit is rejected when the input is too long.
</p>

<pre><code class="language-php">/** @test */
public function receive_invalid_input_from_request()
{
    //Prepare phase
    $form = new MyTestedForm();
    $testInput = 'invalid-long-sentence-input';

    //Assert phase
    $this->submit($form, [
           'input' => $testInput
        ])
        ->assertStatus(422) //we assert that we have a validation error response
        ->assertJson([ 'errors' => ['input' => []] ]) //and that the errors contains the input key;
}</code></pre>

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<!-- ------------------------------------------------------------- -->
<h2>Database tests</h2>

<p>
    To perform database tests, you may use all the tools that Laravel offers by default for <a href="https://laravel.com/docs/master/database-testing" target="_blank">database testing and assertions</a>. The same tools may be used for testing the results of Komponents on the database.
</p>

<p>
    For information purposes, we will add some practical examples shortly.
</p>

<!-- ------------------------------------------------------------- -->
<h3>Practical examples</h3>

@tip(Coming soon...)
@endsection