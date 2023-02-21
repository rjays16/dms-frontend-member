<template>
    <transition name="el-zoom-in-top">
          <div class="content-wrapper page-site-home">
            <!-- Main content -->
            <section class="page-header">
              <div class="page-site-home-header" :style="'background-image:url(' + banner_main_background + ');'">
				<div class="home-video">
					<div style='padding:56.25% 0 0 0;position:relative;'><iframe src='https://www.youtube.com/embed/PfKTPjcmBws' allowfullscreen frameborder='0' style='position:absolute;top:0;left:0;width:100%;height:100%;'></iframe></div>
				</div>
				<div class="page-site-home-header-text" :style="'color:' + banner_main_color + ';' " v-html="banner_main_text"></div>
              </div>
            </section>

            <section class="content width-80">
				<div class="intro-text-box mt-5">
					
					<h5 class="mb-3 text-center">Welcome Message</h5>
					<p>Greetings!</p>
					<p>
						The Metropolitan Davao Medical Society, Incorporated (MDMSI) a new Securities and Exchange Commission (SEC)
						registered and duly recognized component society of the Philippine Medical Association, is an organization of
						physicians residing and/or working in Davao City and nearby Davao provinces. Since this is a new association,
						we encourage you to register at the MDMSI Membership Portal posted at our official website:
						<a href="https://mdms.porth365.com/">https://mdms.porth365.com/</a>
					</p>
					<p>
						A Php 1,500.00 registration fee (Php 1,000 annual dues + Php 500.00 one time payment computerization fee) shall
						be collected starting  September 15, 2021. An instructional video on how to register, FAQs about the website and
						membership portal will be posted, as well.
					</p>
					<p>
						Virtual launching ceremonies of the MDMSI and induction of the officers and newly registered members will be at
						9:00 AM on September 25, 2021 with no less than the Executive Committee Officers and Board of Governors of the
						Philippine Medical Association headed by the PMA President, Dr. Benito P. Atienza in attendance. A short post
						graduate course on Covid 19 cases will follow shortly and will  be discussed by panelists from different specialties.
						Let us register and start anew together in our new home, The Metropolitan Davao Medical Society, Inc.
					</p>
					<p class="font-weight-bold">Welcome everyone!</p>
					<p class="font-weight-bold">MDMS Officers 2021</p>
				</div>
            </section>


            <section class="content width-80 mt-5 d-none" v-if="loggedIn">

					<div class="row home-ann p-4 mt-4" v-for="ann in announcementsSorted" :key="ann.id">

							<div class="col-sm-12 col-md-3">
								<img v-if="ann.file_path !== 'null'" class="card-img-top" :src="showImage(ann.file_path)">
								<img v-else src="/images/layout/blank_post.png" class="card-img-top" />
							</div>
							<div class="col-sm-12 col-md-9">
								
								<h5 class="card-title">{{ ann.title }}</h5>
								<p class="card-date">{{ ann.updated_at | moment }} | By PCR</p>
								<p class="card-text">{{ ann.body_text | strippedContent }}</p>
								<router-link :to="'/announcement/post?id=' + ann.id" class="font-weight-bold">Read More</router-link>
								<div class="card-category">
									<span v-if="ann.category === '1'">Announcements</span>
									<span v-if="ann.category === '2'">International Meetings</span>
									<span v-if="ann.category === '3'">Research Committee</span>
									<span v-if="ann.category === '4'">Local Meetings</span>
								</div>						
							</div>

					</div>

				<div class="text-center my-5">
					<el-button type="primary" round>View More Announcements</el-button>
				</div>

            </section>			

			<section class="content convention-cta d-none">
				<div class="row">
					<div class="col-xs-12 col-sm 12 col-md-2 mb-5">
						<div class="home-side-banner">
							<!-- <span>Convention Banner</span> -->
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-8 mb-5">
						<a href="https://73rdpcrannualconvention.com/registration">
								<img src="/images/layout/convention_cta.png" />
						</a>						
					</div>
					<div class="col-xs-12 col-sm 12 col-md-2 mb-5">
						<div class="home-side-banner">
							<!-- <span>Convention Banner</span> -->
						</div>
					</div>
				</div>

			</section>

            <el-dialog
            :visible.sync="dialogCookies" center>
            <div class="cookie-pop text-center">
                <h4 class="mb-3">This website uses cookies.</h4>
                <p>We use cookies to improve user experience, and analyze website traffic. For these reasons, we may share your site usage data with our analytics partners. By clicking “Accept”, you consent to store on your device all the technologies described in our Cookie Policy.</p>
                <el-button type="success" @click="cookieAccept">Accept</el-button>
            </div>
            </el-dialog>			

            <!-- /.content -->
          </div>
    </transition>
</template>

<script>
import moment from 'moment'

  export default {
    name: 'Home',
    data() {
        return {
			test_url: "/",
			dialogCookies: false,
			loggedIn: false,
			banner_main_background: "/images/layout/page-head-bg-3.png",
			banner_main_text: "Welcome",
			banner_main_color: "#FFFFFF",
			token: this.$cookies.get('token'),
			apiDomain: this.$store.state.apiUrl,
			input1: 'search',
			announcements: []	
        }
	},
    filters: {
        strippedContent: function(string) {
            return string.replace(/<\/?[^>]+>/ig, " "); 
        },
        moment: function (date) {
            return moment(date).format('MMMM DD YYYY');
        }        
    },
    computed: {
        announcementsSorted() {
            return this.announcements.slice(0, 2).sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at))
        },
        latestposts() {
            return this.announcements.slice(0, 4).sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at))
        }     
    },	
    methods: {
		cookieAccept() {
			var cook_acc = 'true'
			let d = new Date()
			d.setTime(d.getTime() + 365 * 48 * 60 * 60 * 1000)
			let expires = "expires=" + d.toUTCString()
			this.$cookies.set('cook_acc', cook_acc, expires, '/')   
			this.dialogCookies = false
		},
		checkIfCookieAcc() {
			var cookie_status = this.$cookies.get('cook_acc')
			if (cookie_status === 'true') {
				this.dialogCookies = false
			} else {
				this.dialogCookies = true
			}
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
            let banner = data.filter(el => el.page_type === "home")
            if (banner.length > 0) {
				this.banner_main_text = banner[0].title1
				this.banner_main_color = banner[0].text_color
				this.banner_main_background = this.apiDomain + '/' + banner[0].image_file.replace("public", "storage")
            } else {
				this.$message.error('There is currently no defined banner settings for this page.')
            }

        },		
		getAllAnnouncements() {
			axios.get(this.apiDomain + `/api/pcr/announcement/all`, {
				headers: { Authorization: "Bearer " + this.token }
			})
			.then(res => {
				if(res.data) {
					this.announcements = res.data;
					// console.log(res.data)
				}
			})
			.catch(err => {
				// console.log(err);
			})
		},
		showImage (image) {
			var fileurl = this.apiDomain + "/" + image.replace("public", "/storage")
			return fileurl
		},
		goToAnn() {
			this.$router.push('/announcement');
		},
        checkMemberLoginStatus() {
            if (this.token) {
                this.loggedIn = true
				this.getAllAnnouncements()
            } else {
                this.loggedIn = false
            }
        }		
    },
  	mounted() {
		this.checkMemberLoginStatus()
		this.checkIfCookieAcc()
		this.getBannerData()
  	}	
  }
</script>

<style>
	.page-site-home .page-header {
		padding-top:80px;
		margin-top:0;
		margin-bottom:0!important
	}
	.page-site-home h2 {
		font-size:1.5rem;
		font-weight:bold;
		color:#f75202;
		text-align: center;
	}
	.page-header .page-site-home-header {
        background-repeat:no-repeat;
        /*background-size: contain;*/
        background-size: 100% 100%;
        width:100%;
        height:0;
        padding-top:26.0417%;
        position:relative;
	}
	.page-site-home-header-text {
		position:absolute;
		width:100%;
		bottom:0;
		top:42%;
		text-align:center;
	}

	.page-site-home-header-text {
		font-size:2.2rem;
		color:#fff;
		font-weight:bold;
	}
	.convention-cta {
		padding-left:0!important;
		padding-right:0!important;
	}
	.convention-cta img {
		width:100%;
	}
	.home-side-banner {
		text-align:center;
		background:#dddddd;
		height:100%;
	}
	.home-side-banner span {
		padding-top:25%;
		display:block;
	}
	.home-ann {
		border: 1px solid rgba(0, 0, 0, 0.125);
		border-radius: 4px;
	}
	.intro-text-box h5 {
		font-weight: 700;
		font-size: 1.2rem;
	}
	.intro-text-box p {
		font-size: 1.1rem;
		line-height: 2.1rem;
		font-weight: 400;
	}
	.intro-text-box .sign-sec {
		margin-top: 50px;
	}
	.intro-text-box .sign-sec p {
		line-height: 1.1rem;
		margin-bottom:1rem;
	}
	.intro-text-box .sign-sec img {
		max-width: 120px;
		float: left;
		margin-right: 20px;
	}
	.home-video {
		top:25px;
		position: absolute;
		width: 40%;
		left:50%;
		z-index: 99;
	}
	@media only screen and (max-width: 1220px)  {
		.page-header .page-site-home-header {
			background-image:url(/images/layout/pcr-site-home-bg.png);
			background-repeat:no-repeat;
			background-size: cover;
			height:250px;
			padding-top:26.0417%;
			position:relative;
		}
		.page-site-home-header-text {
			top:30%
		}
		
	}
	@media only screen and (max-width: 768px)  {
		.page-site-home-header-text {
			top:25%;
			font-size:1.8rem;
			color:#fff;
			font-weight:bold;
		}

	}
	@media only screen and (max-width: 500px)  {
		.home-video {
			padding-top:10px;
			top:0;
			position: absolute;
			width: 100%;
			left:0;
		}		
	}	

    .banner-reg-btn {
        background-color:#00005c!important;
        color:#fff!important;
    }	
.cookie-pop {
    word-break: initial;
}
</style>
