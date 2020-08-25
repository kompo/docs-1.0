@extends('app-docs')


@section('doc-title', 'Advanced usage in Vue')
@section('seo-title', 'Become a real Komposer by mastering the usage in Vue 🧐')

@section('doc-content')

<h2>Using in Vue</h2>

<p>
   For more complex interactions, the komposers can be used internally in Vue too. They emit useful events which can caught and handled in your custom methods.
</p>

<!-- ------------------------------------------------------------- -->
<h3>Form example</h3>
<p>
    To display a form inside one of your Vue components, you may pass it as a prop and use it in Vue.
</p>

<pre><code class="language-html" v-pre>&lt;!-- In your blade template -->
&lt;my-vue-component :form="&#123;{ new App\Http\Komposers\MyForm() }}">&lt;/my-vue-component></code></pre>

<pre><code class="language-html" v-pre>&lt;!-- MyVueComponent.vue -->
&lt;template>
   &lt;vl-form :vkompo="form" @success="success">&lt;/vl-form>
&lt;/template>

&lt;script>
export default {
   props: ['form'],
   methods: {
     success(response){
       console.log(response)
     }
   }
}
&lt;/script>
</code></pre>

<!-- ------------------------------------------------------------- -->
<h3>Query example</h3>

<h1>TODO: CREATE AN ACTIVATE TABLE ROW EXAMPLE</h1>

<p>
    To display the query or table inside one of your Vue components, you may pass it as a prop and use it in Vue.
</p>

<pre><code class="language-html" v-pre>&lt;!-- In your blade template -->
&lt;my-vue-component :query="&#123;{ new App\Http\Komposers\MyCatalog() }}">&lt;/my-vue-component></code></pre>

<pre><code class="language-html" v-pre>&lt;!-- MyVueComponent.vue -->
&lt;template>
   &lt;vl-query :vkompo="query" @event="handleEvent">&lt;/vl-query>
&lt;/template>

&lt;script>
export default {
   props: ['query'],
   methods: {
     handleEvent(payLoad){
       `console`.log(payLoad)
     }
   }
}
&lt;/script>
</code></pre>

<!-- ------------------------------------------------------------- -->
<h2>Emitting events</h2>

<h3>On Submit event</h3>

<p>
  You might not want the form to submit to the backend at all, but rather to simply emit an event to it's parent vue component for example. To do so, you can set the property `emitFormData` in your <b>Form</b> class:
</p>

<pre><code class="language-php">public $emitFormData = true;</code></pre>

  <p>The emitted response can be captured with the event handler `@submit` or `v-on:submit` in the parent vue component:</p>

<pre><code class="language-html">&lt;template>
    &lt;vl-form :vkompo="vkompo" @submit="performAction">&lt;/vl-form>
&lt;/template>

&lt;script>
export default {
    props: ['vkompo'],
    methods: {
        performAction(formData) {
            console.log(formData)
        }
    }
}
&lt;/script>
</code></pre>


<!-- ------------------------------------------------------------- -->
<h3>On Success / on Error events</h3>

<p>If a successful response is received from the server, the form will emit a `success` event that could be listened to from it's parent component. The event will receive the server's response object as the parameter: </p>

<pre><code class="language-html">&lt;template>
    &lt;vl-form :vkompo="vkompo" @success="performAction">&lt;/vl-form>
&lt;/template>

&lt;script>
export default {
    props: ['vkompo'],
    methods: {
        performAction(response) {
            console.log(response)
        }
    }
}
&lt;/script>
</code></pre>




@endsection