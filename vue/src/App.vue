
<template>
  <div id="app">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <router-link :to="{name: 'home'}" class="navbar-brand">SANTRIKODING</router-link>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
        aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <router-link :to="{name: 'home'}" class="nav-link">HOME</router-link>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">TENTANG</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="#">BERITA</a>
          </li>
        </ul>
        <div class="form-inline my-2 my-lg-0">
          <router-link :to="{name: 'login'}" v-if="!loggedIn" class="btn btn-primary my-2 my-sm-0">LOGIN</router-link>
        </div>
      </div>
    </nav>
    <router-view></router-view>
  </div>
</template>

<script>
  export default {
    name: 'App',

    data() {
      return {
        loggedIn: null
      }
    },

    methods: {
      getLoggedIn() {
        this.loggedIn = localStorage.getItem("loggedIn")
      }
    },

    watch: {
      $route: {
        immediate: true,
        handler() {
          this.getLoggedIn()
        }
      }
    },

  }
</script>
