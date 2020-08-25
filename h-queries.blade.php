@extends('app-docs')

@section('doc-title', 'Kompo Queries docs')
@section('seo-title', 'Tables & Catalogs 🤔 Automated browsing, filtering, sorting & pagination')

@section('doc-content')
<!-- ------------------------------------------------------------- -->
<h2>Komposing a query</h2>
<!-- ------------------------------------------------------------- -->

<!-- ------------------------------------------------------------- -->
<h3>Query template</h3>
<!-- ------------------------------------------------------------- -->

<p>
    The Query class has three important sections.
</p>

<ul>
  <li>The <b>query</b> method where we prepare the query.</li>
  <li>The <b>card($item)</b> method where the different variables that a card needs are declared.</li>
  <li>The <b>filters</b> methods: `top()`, `right()`, `bottom()`, `left()` and `headers()` for Table queries.</li>
</ul>

<pre><code class="language-php">&lt;?php

namespace App\Http\Komposers;

use Kompo\Query;

class MyQuery extends Query
{
   /******* The properties **********/
   public $layout = 'Masonry';
   ...

   /******* The query section *******/
   public function query() { ... }

   /******* The card section ********/
   public function card($item) { ... }

   /******* The filters section *****/
   public function top() { ... }
   public function right() { ... }
   public function bottom() { ... }
   public function left() { ... }

   public function headers() { ... } //for Tables (a subtype of Query)
}
</code></pre>


<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

    <h2>Query properties</h2>

<p>
    Here we define the Query's high-level properties or settings like the layout or pagination options. There are also other modifiable settings that are covered in the sorting, ordering and filtering sections.
</p>

<!-- ------------------------------------------------------------- -->
<h3>The layout section</h3>

<p>
    Currently, there are 4 Query layouts available:
</p>

<ul>
  <li>`Table`: display the items in table rows.</li>
  <li>`Horizontal` (the default layout): cards are just stacked in rows.</li>
  <li>`Grid`: this leverages Bootstrap's infamous grid system.</li>
  <li>`Masonry`: to display different height cards beautifully and responsively.</li>
</ul>

<p>
   To set the layout, you declare the public property `$layout` at the beginning of your Query class:
</p>

<pre><code class="language-php">class MyQuery extends Query
{
   public $layout = 'Horizontal'; //The layout style where cards will be displayed.

   ...
</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>Pagination</h3>

<p>
   All queries are paginated out of the box (see the query section for more info). You also define the pagination settings as properties.
</p>

<pre><code class="language-php">
class MyQuery extends Query
{
   public $perPage = 50; //The amount of items per page.
   public $noItemsFound = 'No items found'; //The message to display when no items are found.

   public $hasPagination = true; //Whether to display pagination links or not
   public $topPagination = true; //Whether to display pagination links above the cards
   public $bottomPagination = false; //Whether to display pagination links below the cards
   public $leftPagination = false; //Whether to align pagination links to the left or to the right

   public $paginationStyle = 'Links'; //The pagination component. Other option is 'Showing'

   ...
</code></pre>

<p>
  You may chose between different pagination styles. Currently, the choices are the following but more will be added to the library:
</p>

<?php 
$pagination = json_encode([
  'current_page'=> 1,
  'data'=> [],
  'from'=> 1,
  'last_page'=> 4,
  'per_page'=> 6,
  'prev_page_url'=> null,
  'to'=> 6,
  'total'=> 21
]);
?>

<div class="row">
  <div class="col-6">
    <p>`Links`</p>
    <vl-pagination-links :pagination="{{ $pagination }}"></vl-pagination-links>
  </div>
  <div class="col-6">
    <p>`Showing`</p>
    <vl-pagination-showing :pagination="{{ $pagination }}"></vl-pagination-showing>
  </div>
</div>



<!-- ------------------------------------------------------------- -->
<h3>Query parameters</h3>

<p>
    To instantiate a <b>Query</b> class, you may simply declare the class with the new keyword. And if you need to inject parameters or external dependencies, you may use the first argument to <b>add data to the Query's store</b>.
</p>

<pre><code class="language-php">$myQuery = new MyQuery();

//Or with parameters
$myQuery = new MyQuery([
   'some-param' => 'some-value'
]);</code></pre>

<p>If it is used inside another Komposer and you would like to chain some methods to it, the `::form()` method also works:</p>

<pre><code class="language-php">public function komponents()
{
   return [
      MyQuery::form()->class('p-4 bg-gray-100'),

      //or with parameters
      MyQuery::form([
         'some-param' => 'some-value'
      ])->class('p-4 bg-gray-100')
   ];
}</code></pre>

<!-- ------------------------------------------------------------- -->
<h4>Query data store example</h4>

<p>
    Let's say we have a <b>QuestionForm</b> and we want to <u>display its answers</u>. To do so, we create an <b>AnswersQuery</b> where <u>the parent Question's id is injected</u>:
</p>

<pre><code class="language-php">class QuestionForm extends Form
{
   public $model = Question::class;

   public function komponents()
   {
      return [
         Input::form('Title'),
         Textarea::form('Content'),
         //We pass the Question's id here
         AnswersQuery::form(['question_id' => $this->model->id])
      ];
   }</code></pre>

@tip(The store data is accessible at all stages of the Query, i.e. on initial display AND all subsequent browsing, filtering or sorting calls.)

<p>
    Then in your <b>AnswersQuery</b> class, you can retrieve the question id thanks to the `store()` method. 
</p>

<pre><code class="language-php">class AnswersQuery extends Query
{
    public function query()
    {
       //We retrieve the question_id here
       return Answer::where('question_id', $this->store('question_id'));
    }

...</code></pre>



<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<!-- ------------------------------------------------------------- -->
<h2>Fetching the results</h2>

<p>
   The `query()` method is where you specify the Builder statement that will fetch the displayed items. This statement will also be used on subsequent calls to filter, sort and paginate the results. It is preferable <b>NOT to execute</b> the query in this step with `->get()` or `->paginate()`.
</p>

@danger(Notice how we return a <b>Builder</b> instance and <u>NOT an executed query</u>.<br>Kompo will execute it for you behind the scenes. )

<pre><code class="language-php">
class MyQuery extends Query
{
   ...

   public function query()
   {
      return Post::with('tags')->orderBy('published_at');
   }

   ...
</code></pre>

<h3>The return value of query</h3>

<p>
    <i class="far fa-thumbs-up color3"></i> It is recommended that a <b>query</b> method return:
</p>

<ul>
  <li>either an `Illuminate\Database\Eloquent\Builder` instance,</li>
  <li>or an `Illuminate\Database\Query\Builder` instance.</li>
</ul>

<p>
   While it is possible to also return:
</p>
<ul>
  <li>an `Illuminate\Database\Eloquent\Collection` instance,</li>
  <li>an `Illuminate\Support\Collection` instance,</li>
  <li>or even a simple `Array`</li>
</ul>

<p>it comes at a performance cost and poorer filtering capabilities. Use Arrays or Collections only if the result set is not too large.</p>

<h4>Eloquent ORM methods</h4>

<p>
     You may use <b>any Eloquent ORM method</b> such as:
</p>

<ul>
  <li>where, whereHas, whereIn, having, ... to prefilter the records.</li>
  <li>with, withCount, ... to eager load relationships.</li>
  <li>orderBy, orderByRaw, ... to order your records.</li>
  <li>groupBy, skip, take, ... to group or limiting your records.</li>
</ul>

<pre><code class="language-php">public function query()
{
  return Post::whereHas(...)->withCount(...)->orderByRaw(...);
}</code></pre>

<p>
  Or to query all the records, you may simply write:
</p>

<pre><code class="language-php">public function query()
{
  return new Post(); //<-- This will paginate through ALL the posts in the DB
}</code></pre>

<p>
  For filtering items after initial load, You may then filter the records but that will be handled out of the box by the filters in the filter section.
</p>

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<!-- ------------------------------------------------------------- -->
<h2>Query Cards</h2>

<p>
   The information needed to display each card is given in the `card` method. This method is always declared with a single parameter `$item` which comes from the paginated query results. There are 3 ways of defining a card depending on the level of customization you want to have.
</p>

<ol>
  <li><b>Komposing cards</b> with Komponents.</li>
  <li>Use one of Kompo's <b>prebuilt cards</b> (included in the base installation).</li>
  <li>Advanced: Create a <b>custom card</b> Vue component.</li>
</ol>

<!-- ------------------------------------------------------------- -->
<h3>1. Komposing Cards</h3>

<p>To kompose a card, you may use Layouts, Blocks, Triggers or any other Komponent of your choice to assemble cleverly a card that suits your needs.</p>

<pre><code class="language-php leading-loose">public function card(Post $post)
{
   return FlexBetween::form(
      Title::form($post->title),
      FlexEnd::form(
        _Link('💚')->post('post.like', ['id' => $post->id]),
        _Link('💬')->get('post.comment', ['id' => $post->id])
      )
   )->class('p-2 md:p-4 text-gray-500');
}</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>2. Prebuilt Cards</h3>

<p>
   Kompo offers prebuilt card components that you may just reference and fill the relevant info in the corresponding card properties. For example, here we are using the `ImageOverlay` card and adding the article's image, title, grid and styles class and finally a toolbar of buttons to like or share the article.
</p>

<pre><code class="language-php">public function card($item)
{
   return ImageOverlay::form([
     'image' => asset($item->image),
     'title' => $item->title,
     'col' => 'col-4',
     'class' => 'shadow mb-6',
     'buttons' => FlexEnd::form(
        Link::icon('icon-heart')->post('article.like', ['id' => $item->id]),
        Link::icon('icon-share')->post('article.share', ['id' => $item->id])
     )
   ]);
}</code></pre>

@tip(To see all the predefined cards and their required properties, check out the <a href="{{ route('card-api', ['card' => 'TableRow', 'layout' => 'Table']) }}" target="_blank">API for Query cards</a>.)

<!-- ------------------------------------------------------------- -->
<h3>3. Custom card</h3>

<p>
  This is an advanced feature and offers the advantage of <b>total freedom and flexibility</b>, since you will be building your own Vue component. Check out the {!! docsRoute('Custom Cards', 'docs.cc') !!} section for all the information on how to build one.</p>

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<!-- ------------------------------------------------------------- -->
<h2>Filter, Sort & Order</h2>

<!-- ------------------------------------------------------------- -->
<h3>Filters placement</h3>

<p>
   You may include additional html, filters, and other components all around your Query's cards. There are 4 methods `top`, `right`, `left` and `bottom` that can be used for positionning these components and they have to return one or an array of komponents the same way as you would in a Form or Card:
</p>

<div class="mansala color2 font-bold text-sm p-2 border border-gray-200 mb-6">
  <div class="flex justify-between items-stretch h-64">
    <div class="bg-gray-200 vlFlexCenter vlFlexCol p-2 text-center mr-4">
      <span class="hidden md:inline">Components<br>in </span> left()
    </div>
    <div class="flex-1">
      <div class="h-10 bg-gray-200 vlFlexCenter p-2 text-center mb-4">
        <div><span class="hidden md:inline">Components in </span> top()</div>
      </div>
      <div class="bg-gray-200">
        <div class="h-8 text-gray-600 vlFlexCenter">Layout</div>
        <div class="row">
          <div class="col-4 mb-2"><div class="bg-gray-400 vlFlexCenter h-12">Card</div></div>
          <div class="col-4 mb-2"><div class="bg-gray-400 vlFlexCenter h-12">Card</div></div>
          <div class="col-4 mb-2"><div class="bg-gray-400 vlFlexCenter h-12">Card</div></div>
          <div class="col-4 mb-2"><div class="bg-gray-400 vlFlexCenter h-12">Card</div></div>
          <div class="col-4 mb-2"><div class="bg-gray-400 vlFlexCenter h-12">Card</div></div>
          <div class="col-4 mb-2"><div class="bg-gray-400 vlFlexCenter h-12">Card</div></div>
        </div>
      </div>
      <div class="h-10 bg-gray-200 vlFlexCenter p-2 text-center mt-4">
        <div><span class="hidden md:inline">Components in </span> bottom()</div>
      </div>
    </div>
    <div class="bg-gray-200 vlFlexCenter vlFlexCol p-2 text-center ml-4">
      <span class="hidden md:inline">Components<br>in </span> right()
    </div>
  </div>
</div>

<p>
  Below is an example where we add a decorative <b>title</b>, a <b>link</b> to a Form, and <b>filters</b> to interact with our query results.
</p>

<pre><code class="language-php">public function top()
{
  return [
     //Adding a title and a link to add a new item
     FlexBetween::form(
        Title::form('Examples'),
        Link::form('Add an example')->href('admin.example')
     ),
     //Adding filters (see next section how to activate them)
     Columns::form(
        Select::form('Category'),
        Input::form('Title')
     )
  ];
}</code></pre>


<!-- ------------------------------------------------------------- -->
<h3>Filtering</h3>

<p>
   It is very straightforward to filter your Query's cards. To do so, you may add one or more <b>Field component</b> in one of the filters sections and then chain one of the filtering actions.
</p>

@tip(Only Field components can filter Queries. If you wish to filter using a Button or Link, you may use the <b>SelectButtons</b> or <b>SelectLinks</b> components which are Fields.)

<p>
   The following operators may be used to filter:
</p>

{!! apiTable('Kompo\\Komponents\\Field', ['filter']) !!}


<h4>Attributes & relationships</h4>

<p>
   The <b>Field name</b> drives the filtering behavior. It can be:
</p>

<ul>
  <li>A simple string `'attribute'` : to refer to one of the model's attributes.</li>
  <li>A dot-separated string `'relationship.attribute'` or `'relationship.relationship.attribute'` : to filter by nested relationships. Kompo currently supports two levels deep nesting.</li>
  <li>If no attribute is specified after a relationship, for example: `'relationship'` or `'relationship.relationship'`: it will use the last relationship's PRIMARY KEY.</li>
</ul>

<pre><code class="language-php">public function top()
{
   return [
      Select::form('Category')
         ->name('category_id') //Filtering by attribute
         ->filter(), //Fields filter onChange by default.
      Input::form('Title')
         ->name('category.name') //Filtering the relationship's name
         ->filter() //Input Komponents filter onInput by default and debounce by 500ms
   ];
}</code></pre>

<h4>Filtering operators</h4>

<p>
   You may assign one of these operators in the `filter` method:  `=`, `>`, `<`, `>=`, `<=`, `LIKE`, `STARTSWITH`, `ENDSWITH`, `BETWEEN`, `IN`. If you don't specifically assign an operator in the `filter` method (.i.e. null argument), Kompo will use these default conditions for each Field:
</p>

<ul>
  <li>If the Field can support <b>multiple values</b> (MultiSelect for example), it will use a whereIn.</li>
  <li>If the Field is a <b>text Input</b>, it will use a where($column, 'LIKE', '%'.$value.'%').</li>
  <li>Otherwise, it will perform a simple where.</li>
</ul> 

<p>
   If you have multiple filters, Kompo will perform an <b>AND where</b>.
</p>

<h4>Prefilter a Query</h4>

<p>
  Of course, if you want to prefilter your Query, you may define that in the `query` method. This filter is permanent and will always be preserved on subsequent browse requests.
</p>

<pre><code class="language-php">public function query()
{
   //Permanent Query filter
   return Article::whereNotNull('published_at');
}</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>Sorting</h3>

<p>You may also define sorting capabilities in your Query as easily as filters. The komponents that are capable of sorting are: </p>

<ul>
  <li>`Fields`: in this case their values will determine the sort order.</li>
  <li>`Buttons` and `Links`: you have to instruct the sort order with one of the sorting actions.</li>
  <li>`Th` (table headers): you also have to instruct the sort column and direction in one of the sorting actions.</li>
</ul>

<h4>Sorting action</h4>

<p>
   The following action may be used to sort:
</p>
<table class="api-table table table-sm table-borderless">
  <tbody>
{!! apiMethod('Kompo\\Komponents\\Field', 'sort') !!}
  </tbody>
</table>

<p>
   The `$sortOrders` parameter accepts a pipe-delimited string of one or more `column:direction` pairs. You may also sort on nested relationships (one level deep only) by using a dot-delimited string in the column part:
</p>

<ul>
  <li>Sorting by an attribute `'attribute'` : this will sort by the model's attribute and the direction is ASC by default.</li>
  <li>Sorting by one attribute with a direction `'attribute:DESC'`: this will sort by the model's attribute in the descending order.</li>
  <li>Sorting by multiple attributes and directions `'attribute1:DESC|attribute2|attribute3:DESC'` : this will sort by 3 different attributes with attribute1 and attribute3 in the descending order and attribute2 in the ascending order.</li>
  <li>Sorting with relationships and attributes `'relationship.attribute1:DESC|attribute2'` : this will sort by the model's attribute2 in the ascending order and the relationship's attribute1 in the descending order.</li>
</ul>

<h4>Presort a Query</h4>

<p>
  Of course, if you want a presorted Query, you may define that in the `query` method. This sort will always be preserved on subsequent browse requests.
</p>

<pre><code class="language-php">public function query()
{
   //Permanent Query sort
   return Article::orderBy('published_at', 'DESC');
}</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>Draggable ordering</h3>

<p>
  Kompo also offers the ability to drag and drop cards and reorder the records on the back-end. Let's say your model has an `order_column` column that will be used to display the items in the desired order.
</p> 
<p>
  You may set the `$orderable` property to activate this functionality:
</p>

<pre><code class="language-php">class MyQuery extends Query
{
   public $orderable = 'order_column';</code></pre>

<p>
  Now the Query cards will be draggable and the user may change the cards' orders from the Front-end and the Back-end will get automatically updated.
</p>

@tip(The orderable column should be defined as an INT datatype or equivalent in your database.)


@endsection