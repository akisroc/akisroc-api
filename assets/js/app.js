require('../css/app.scss')

import Vue from 'vue'
import VueMarkdown from 'vue-markdown/src/VueMarkdown';

const app = new Vue({
  el: '#app',
  components: {
    VueMarkdown
  }
})
