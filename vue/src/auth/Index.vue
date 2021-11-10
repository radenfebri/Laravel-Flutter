<template>
    <div class="login">
        <div class="container" style="margin-top:100px">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div v-if="loginFailed" class="alert alert-danger">
                        Email atau Password Anda salah.
                    </div>
                    <div class="card">
                        <div class="card-body">
                            LOGIN
                            <hr>
                            <form @submit.prevent="login">

                                <div class="form-group">
                                    <label>EMAIL</label>
                                    <input type="email" class="form-control" v-model="user.email"
                                        placeholder="Masukkan Email">
                                    <div v-if="validation.email" class="mt-2 alert alert-danger">
                                        Masukkan Email
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>PASSWORD</label>
                                    <input type="password" class="form-control" v-model="user.password"
                                        placeholder="Masukkan Password">
                                    <div v-if="validation.password" class="mt-2 alert alert-danger">
                                        Masukkan Password
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">LOGIN</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: 'Login',

        data() {
            return {
                //state loggedIn with localStorage
                loggedIn: localStorage.getItem('loggedIn'),
                //state token
                token: localStorage.getItem('token'),
                //state user
                user: [],
                //state validation
                validation: [],
                //state login failed
                loginFailed: null
            }
        },
        methods: {

            login() {
                if (this.user.email && this.user.password) {
                    axios.get('http://localhost:8000/sanctum/csrf-cookie')
                        .then(response => {

                            //debug cookie
                            console.log(response)

                            axios.post('http://localhost:8000/api/login', {
                                email: this.user.email,
                                password: this.user.password
                            }).then(res => {

                                //debug user login
                                console.log(res)

                                if (res.data.success) {

                                    //set localStorage
                                    localStorage.setItem("loggedIn", "true")

                                    //set localStorage Token
                                    localStorage.setItem("token", res.data.token)

                                    //change state
                                    this.loggedIn = true

                                    //redirect dashboard
                                    return this.$router.push({ name: 'dashboard' })

                                } else {

                                    //set state login failed
                                    this.loginFailed = true

                                }

                            }).catch(error => {
                                console.log(error)
                            })

                        })
                }

                this.validation = []

                if (!this.user.email) {
                    this.validation.email = true
                }

                if (!this.user.password) {
                    this.validation.password = true
                }

            }
        },

        //check user already logged in
        mounted() {
            if (this.loggedIn) {
                return this.$router.push({ name: 'dashboard' })
            }
        }
    }
</script>
