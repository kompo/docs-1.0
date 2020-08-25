@extends('app-docs',[
	'DocsSidebarL' => menu('DocsGeneralSidebar'),
	'DocsSidebarR' => menu('DocsSummarySidebar')
])

@section('doc-title', 'Custom Komponents')
@section('seo-title', 'Create a beautiful komposition of chained Komponents...')

@section('doc-content')


<!-- ------------------------------------------------------------- -->
<div class="wave"></div>
<!-- ------------------------------------------------------------- -->

<h2>Custom komponents</h2>

@tip(If you build a cool Komponent, please let us know about it. We may add it to the package and allow everyone in the community to use it!)

<p>
  You may extend the komponents your app can use for different reasons: avoid code repetition, creating combos of komponents that enhance their behavior, or simply building a totally new unexisting Field...
</p>

<!-- ------------------------------------------------------------- -->
  <h3>Creating the PHP class</h3>

  <p>First, you need to extend the relevant class. If you remember, there are 4 types of Komponents</p>

<ul>
  <li>A <b>Field</b> has user input and does AJAX - extends `Kompo\Komponents\Field`.</li>
  <li>A <b>Layout</b> has child Komponents - extends `Kompo\Komponents\Layout`.</li>
  <li>A <b>Trigger</b> can perform AJAX requests - extends `Kompo\Komponents\Trigger`.</li>
  <li>A <b>Block</b> is purely decorative - extends `Kompo\Komponents\Block`.</li>
</ul>

<p>
    We first need to link the PHP class to the Vue component in the <b>$vueComponent</b> prpperty. Kompo will look for a `Vl{ComponentName}.vue` and will load the PHP class public properties into it. 
</p>

  <h4>Data attribute</h4>

<p>
  A convenient method to pass information and configurations to the Front-End component is the `->data()` method which accepts an associative array as argument. Let us take a look at extending a Field for example. 
</p>

<pre><code class="language-php">use Kompo\Komponents\Field;

class CustomComponent extends Field
{ 
   //Kompo will look for a VlCustomKomponent.vue
   public $vueComponent = 'CustomKomponent';

   //Pass some information and configurations
   public function vlInitialize($label)
   {
      parent::vlInitialize($label);

      $this->data([
         'defaultWidth' => '60px'
      ]);
   }

   /**
    * Fields have many useful methods to handle the transformation of user inputs.
    */
   public function getValueFromModel($model, $name)
   {
      //how to retrieve the value from the DB.
   }

   public function prepareForFront($komposer)
   {
      //transform the value before it is displayed in the Form
   }

   public function setAttributeFromRequest($requestName, $name, $model, $key = null)
   {
      //Processes and returns the request value when the field is an attribute.
   }

   public function setRelationFromRequest($requestName, $name, $model, $key = null)
   {
      //Processes and returns the request value when the field is a relation.
   }
}
</code></pre>

@tip(Take a look at the Komponents in the src/Usable folder to better understand how each were built. This will help you a lot while building your own custom komponent.)

<!-- ------------------------------------------------------------- -->
  <h3>Creating the Vue component</h3>

<p>
   You should create a `VlCustomComponent.vue` file with the code below. You may add attributes and event bindings by overriding the `$_attributes` and `$_events` methods from the mixin. Let us continue with our example of a Field
 </p> 

<pre><code class="lang-html">&lt;template>
    &lt;vl-form-field v-bind="$_wrapperAttributes">
        &lt;input
            v-model="component.value"
            class="vlFormControl"
            v-bind="$_attributes"
            v-on="$_events"
        />
    &lt;/vl-form-field>
&lt;/template>

&lt;script>
import Field from 'vue-kompo/js/form/mixins/Field'
export default {
   mixins: [Field],
   computed: {
      $_attributes() {
        return {
          ...this.$_defaultFieldAttributes,
          width: this.$_data('defaultWidth') //accessing info stored in data
        }
      },
      $_events() {
        return {
          ...this.$_defaultFieldEvents,
          mouseup: this.someMethod
        }
      }
   },
   methods: {
      someMethod(){
        console.log('mouse up')
      }
   }
}
&lt;/script>
</code></pre>

@tip(A Field may have a &lt;vl-form-field wrapper that provides a label, errors, comment and help text.)

<h4>Layout Vue component</h4>

<p>
    A layout is easier to write in Vue. There is no wrapper and you may directly start placing its' child komponents with the `komponents` data attribute. For example:
</p>

<pre><code class="lang-html">&lt;template>
    &lt;div>
        &lt;template v-for="(child,index) in komponents">
            &lt;component 
                :width="$_data('defaultWidth')" 
                :is="child.vueComponent"
                />
        &lt;/template>
    &lt;/div>
&lt;/template>

&lt;script>
import Layout from 'vue-kompo/js/form/mixins/Layout'

export default {
    mixins: [Layout],
    computed:{
      //do your thing
    },
    methods:{
      //do your thing
    }
}
&lt;/script></code></pre>

@endsection