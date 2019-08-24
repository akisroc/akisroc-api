require('../css/app.scss')

require('bulma')
require('bulma-tooltip')


import Vue from 'vue'
import Navigation from './components/Navigation'
import PostExcerptCard from './components/PostExcerptCard'

const app = new Vue({
  el: '#app',
  components: {Navigation, PostExcerptCard}
})
