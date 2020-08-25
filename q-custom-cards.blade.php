@extends('app-docs',[
	'DocsSidebarL' => menu('DocsGeneralSidebar'),
	'DocsSidebarR' => menu('DocsSummarySidebar')
])

@section('doc-title', 'Custom Cards')
@section('seo-title', 'Extend query cards and visuals...')

@section('doc-content')

<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<!-- ------------------------------------------------------------- -->
<h2>Vue component</h2>

<p>
  For even more power and flexibility, you may also create your own Vue card component.
</p>

<p>
    Let's take a look at a custom card example. The vue component name need to start with 'Vl', so let's call ours `VlSection.vue`.
</p>

<pre><code class="language-html">&lt;template>
    &lt;h3 :id="`$_prop`('slug')" v-html="`$_prop`('title')"/>
    &lt;p v-html="`$_prop`('description')" />

    &lt;div v-for="preview in `$_prop`('previews')">
        &lt;vl-form v-if="preview.enabled" :vkompo="preview" />
    &lt;/div>
&lt;/template>

&lt;script>
import Card from 'vue-kompo/js/query/mixins/Card'
export default {
   mixins: [Card], //Required - Import the Card mixin 

   //Then go wild... do whatever you want
   methods:{
      
   },
   computed:{

   }
}
&lt;/script>
</code></pre>

@tip(The mixin offers some helpful methods such as the `$_prop` method that lets you retrieve the data passed from the back-end. But it also has essential props and data that are required.)

<p>
  Once the Vue component is created, we reference it in our PHP with the `Kompo\Card` class and assign it the front-end component we built with the `component` method:
</p>

<pre><code class="language-php">use Kompo\Query;
use Kompo\Card; //This is the general Card komponent

class CustomCatalog extends Query
{
    public function card(Post $post)
    {
       return Card::form([
          //... 
       ])->component('Section'); //notice how we tell which Front-end component to use
    }</code></pre>


<p>Then we may feed it the relevant information from the back-end. This works exactly as you would with predefined components.</p>

<pre><code class="language-php">public function card(Post $post)
{
   return Card::form([
      'title' => $post->title,
      'slug' => Str::slug($post->title),
      'description' => $post->description,
      'previews' => $post->codes->map(function($code){
          return $code->preview();
      })
   ])->component('Section');
}</code></pre>

@endsection