<template>
	<footer>
		<div class="container-fluid contact-footer py-3">
			<div class="container">
				<h6 class="mt-3">Contact Details</h6>
			<div class="row py-2">
				
				<div class="col-sm-12 col-md-6">
					<div class="contact-details">
						<p>
							<span><span class="material-icons">email</span> Email:</span>
							<a href="mailto:metropolitandavaomedsoc@gmail.com">metropolitandavaomedsoc@gmail.com</a>
						</p>
						<!--<p>
							<span><span class="material-icons">call</span> Landline:</span>
							<a href="tel:0822228000">(082) 222-8000 local 8158</a>
						</p>-->
						<p>
							<span><span class="material-icons">share</span> Social Media:</span>
							<a class="social-icons" href="https://www.facebook.com/groups/2938178176470227/">
								<img src="/images/layout/social_fb.png" />
							</a>
						</p>
					</div>
				</div>
				<div class="col-sm-12 col-md-6">
					<div class="contact-details">

						<p>
							<span><span class="material-icons">phone_iphone</span> Mobile: </span>
							<a href="tel:09163243985">(0916) 3243985</a> / <a href="tel:09238286874">(0923) 8286874</a>
						</p>
						<p>
							<span><span class="material-icons">location_on</span> Address: </span>
							Fabie General Hospital, Pelayo Street, Davao City, 8000
						</p>
					</div>					
				</div>

				

			</div>
			</div>
		</div>		
		<div class="container-fluid bottom-footer py-3">
			<div class="container">
				<div class="pull-right social-icons text-right">
					<a href="/" class="soc-fb">
					<img :src="footer_logo_img" />
					</a>

				</div>
				<span v-html="footer_rights_text"></span>
				<router-link class="foot-page-link" to="/terms-and-conditions">Terms &amp; Conditions</router-link>
				<router-link class="foot-page-link" to="/privacy-policy">Privacy Policy</router-link>    
			</div>
		</div>
	</footer>
</template>

<script>
  export default {
    name: 'SiteFooter',
    data() {
        return {
			footer_rights_text: "2021 All Rights Reserved &copy; Virtual Media Events",
			footer_logo_img: "/images/layout/vme-logo.png",
			email_newsletter: "",
			apiDomain: this.$store.state.apiUrl
        }
    },
    methods: {
      getfooterData() {
        axios.get(this.apiDomain + `/api/footer`)
        .then(res => {
          // console.log(res.data)
          if(res.data.status_code === 200) {
            if (res.data.data.length > 0) {
              this.footer_rights_text = res.data.data[0].footer_rights_text
              this.footer_logo_img = this.apiDomain + '/' + res.data.data[0].footer_logo_img.replace("public", "storage")
            }
          }
        })
        .catch(err => {
          // console.log(err);
        })
      }
    },
    mounted: function () {
      this.getfooterData()
    }    
  }
</script>

<style lang="scss" scoped>

	footer a {
		color:#212529!important;
	}
	.contact-footer {
		background: #f1f1f1;
		border-top: 1px solid #dadada;
		border-bottom: 1px solid #dadada;
		h6 {
			font-weight: bold;
			font-size: 1rem;
			border-bottom: 1px solid #dadada;
			padding-bottom: 15px;
			margin-bottom: 15px;
		}
		.el-input-group__append button.el-button {
			background: #db7700;
			color: #fff;
			border-top-left-radius: 0;
			border-bottom-left-radius: 0;
		}
		.social-follow {
			a {
				img {
					max-width: 30px;
					margin-right: 8px;
				}
			}
		}
		.contact-details {
			p {
				font-size: 0.9rem;
				span {
					font-weight: bold;
					margin-right: 5px;
					.material-icons {
						color: #37c175;
						margin-right: 3px;
						font-size: 1rem;
					}
				}
			}
		
		}
	}
	.foot-page-link {
		border-left:1px solid #8d8d8d!important;
		margin-left:10px;
		padding-lefT:10px;
	}
	.social-icons a {
		height:25px;
		width:25px;
		display:inline-block;
	}
	.social-icons img {
		height:20px;
	}
	.contact-row {
		background: #dddddd;
	}
</style>

