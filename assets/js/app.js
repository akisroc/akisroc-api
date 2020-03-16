require('../css/app.scss')

import Vue from 'vue'
import VueMarkdown from 'vue-markdown/src/VueMarkdown';
import Navigation from './components/Navigation'
import PostExcerptCard from './components/PostExcerptCard'

const app = new Vue({
  el: '#app',
  components: {
    VueMarkdown,
    Navigation, PostExcerptCard
  }
})
