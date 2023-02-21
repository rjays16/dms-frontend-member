<template>
    <transition name="el-zoom-in-top">
        <div class="content-wrapper page-login">
            <!-- Main content -->
            <section class="page-header">
				<div class="page-site-login-header" :style="'background-image:url(' + banner_main_background + ');'">
					<div class="page-site-login-header-text" :style="'color:' + banner_main_color + ';' " v-html="banner_main_text"></div>
				</div>
            </section>
            <section class="content content-80">
              <div class="d-flex justify-content-center">
                <el-card class="box-card login-card mb-5">
                  <div slot="header" class="clearfix">
                    <h1>Login</h1>
                  </div>
                  <form>
                    <div class="sec-login-info">
                      <el-input class="mb-3" placeholder="Username" v-model="email"></el-input>
                      <el-input class="mb-3" placeholder="Password" v-model="password" show-password @keyup.enter.native="submit"></el-input>
                      <el-checkbox v-model="keep_signed">Keep me signed in</el-checkbox>
                    </div>
                    <div class="sec-submit mt-3">
                      <el-button  class="login-submit-btn" type="primary" round @click="submit" :loading="signing_in">SUBMIT</el-button>
                      <div class="text-center mt-2"><el-button type="text" @click="forgotPassword()" class="font-weight-bold">Forgot Password?</el-button></div>
                    </div>
                  </form>
                </el-card>
              </div>
            </section>
            <!-- /.content -->
        </div>
    </transition>
</template>

<script>
  export default {
    name: 'Login',
    data() {
        return {
			banner_main_background: "/images/layout/pcr-site-reg-bg.png",
			banner_main_text: "Login",
			banner_main_color: "#FFFFFF",	
			signing_in: false,
			username: '',
			password: '',
			email: '',
			keep_signed: false,
			test: this.$cookies.get('token'),
			apiDomain: this.$store.state.apiUrl
        }
	},
	methods: {
		forgotPassword () {
			location.href='/forgot-password'
		},
		submit() {
			this.signing_in = true
			this.$cookies.remove('token')
			this.$cookies.remove('member_id')
			this.$cookies.remove('user_role')
			this.$cookies.remove('reg_id')
			let data = {
				email: this.email,
				password: this.password
			}
			axios.post(this.apiDomain + '/api/auth/login', data)
			.then(response => {
				// console.log(response.data)
				let d = new Date()
				d.setTime(d.getTime() + 1 * 24 * 60 * 60 * 1000)
				let expires = "expires=" + d.toUTCString()
				this.$cookies.set('token', response.data.token, expires, '/')
				this.$cookies.set('user_role', response.data.role, expires, '/')
				// this.$router.push('/registration/profile')
				location.href='/profile'
			}).catch(error => {
				// console.log(error.response.data)
				this.$notify({
					message: error.response.data.message,
					type: 'error',
					customClass: 'pcr-login-error',
					position: 'bottom-right'
				})
				this.signing_in = false
			})
		},
		getBannerData() {
			axios.get(this.apiDomain + `/api/banner`)
			.then(res => {
				// console.log(res.data)
				if(res.data.status_code === 200) {
					if (res.data.data.length > 0) {
						this.assignBannerValues(res.data.data)


					} else {
						//this.$message.error('There is currently no defined banner settings for this page.')
					}
				}
			})
			.catch(err => {
				// console.log(err);
			})
		},
        assignBannerValues(data) {
			this.banner_main_text = "Login"
            let banner = data.filter(el => el.page_type === "login")
            if (banner.length > 0) {
				// this.banner_main_text = banner[0].title1
				// this.banner_main_color = banner[0].text_color
				// this.banner_main_background = this.apiDomain + '/' + banner[0].image_file.replace("public", "storage")
            } else {
				this.$message.error('There is currently no defined banner settings for this page.')
            }

        }			
	},
	mounted() {
		this.getBannerData()
  	}
  }
</script>

<style>
	.page-site-reg .page-header {
		padding-top:80px;
		margin-top:0;
		margin-bottom:0;
	}
	.page-header .page-site-login-header {
		background-repeat:no-repeat;
		background-size: 100% 100%;
		width:100%;
		height:0;
		padding-top:26.0417%;
		position:relative;
	}
	.page-site-login-header-text {
		position:absolute;
		width:100%;
		bottom:0;
		top:42%;
		text-align:center;
		font-size:2.2rem;
		font-weight:bold;
	}
	.pcr-page-title {
		font-size:24px;
		padding-top:50px;
	}
	.pcr-page-subtitle {
		font-size:18px;
	}
	.login-card,
	.contact-card {
		min-height:360px;
	}
	.login-card h1,
	.contact-card h1 {
		font-size:24px;
		text-align: center;
	}
	.login-submit-btn {
		width:100%;
	}
	.pcr-login-error .el-notification__content {
		text-align:left!important;
	}
  @media only screen and (max-width: 1220px)  {
		.page-header .page-site-login-header {
			background-image:url(/images/layout/pcr-site-reg-bg.png);
			background-repeat:no-repeat;
			background-size: cover;
			height:150px;
			padding-top:26.0417%;
			position:relative;
		}
		.page-site-login-header-text {
			top:30%
		}
  }
	@media only screen and (max-width: 768px)  {
		.page-site-login-header-text {
			top:40%;
			font-size:1.8rem;
			color:#fff;
			font-weight:bold;
		}
	}
  @media only screen and (min-width: 960px)  {
    .login-card {
      width:35%;
    }
  }

</style>
