<template lang="pug">
  nav.navbar.is-dark(role="navigation", aria-label="main navigation")
    a.burger.navbar-burger(@click="toggle", role="button",
      aria-label="menu", aria-expanded="false",
      data-target="responsive-navbar")
      span(aria-hidden="true")
      span(aria-hidden="true")
      span(aria-hidden="true")
    div#responsive-navbar.navbar-menu(@click="toggle")
      div.navbar-start
        a.navbar-item(href="/") Page d'accueil
        a.navbar-item(href="#")
          span.badge.has-badge-rounded.has-badge-light.has-badge-small(data-badge="1")
            | Messages privés
        div.navbar-item.has-dropdown.is-hoverable
          a.navbar-link Gestion
          div.navbar-dropdown
            a.navbar-item(href="#") Troupes
            a.navbar-item(href="#") Ressources
      div.navbar-end
        div.navbar-item
          div.buttons
            a.button.is-light(v-if="user !== null", href="#")
               strong {{ user.username }}
            a.button.is-dark(v-if="user !== null", href="/logout") Se déconnecter
            a.button.is-dark(v-if="user === null", href="/login") Se connecter
</template>

<script>
  export default {
    props: {
      user: {
        type: Object,
        default: null
      }
    },
    data () {
      return {
        $burgers: [],
        $burgersTarget: null
      }
    },
    mounted () {
      this.$burgers = Array.prototype.slice.call(
        document.querySelectorAll('.navbar-burger'), 0
      )
      this.$burgersTarget = document.getElementById('responsive-navbar')
    },
    methods: {
      toggle () {
        this.$burgers.forEach(el => {
          el.classList.toggle('is-active')
        })
        this.$burgersTarget.classList.toggle('is-active')
      }
    }
  }
</script>
