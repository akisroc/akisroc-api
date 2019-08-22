require('../css/app.scss')

require('bulma')
require('bulma-tooltip')


import Vue from 'vue'
import PostExcerptCard from './components/PostExcerptCard'

const app = new Vue({
  el: '#app',
  components: {PostExcerptCard}
})
