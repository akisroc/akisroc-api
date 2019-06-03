require('../css/app.scss')

require('bulma')
require('bulma-tooltip')


import Vue from 'vue'
import Salut from './components/Salut'

const app = new Vue({
  el: '#app',
  components: {Salut}
})
