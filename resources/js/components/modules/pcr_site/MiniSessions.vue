<template>
    <transition name="el-zoom-in-top">
        <div class="content-wrapper page-minisessions-login" v-loading="loading">
            <!-- Main content -->
            <section class="page-header">
              <div class="page-minisessions-header">
                <div class="pcr-page-title font-weight-bold">Sessions</div>
                <!--<div class="pcr-page-subtitle">Virtual Production Center</div>-->
              </div>
            </section>
            <section class="content content-80">
              <div class="d-flex justify-content-center">
                  <div class="session-tabs">
                        <div>
                          <div class="mini-sess-cont" v-for="session in allSess" :key="session.id">
                              <div class="row">
                                  <!--<div class="col-sm-12 col-md-3 mini-sess-btn-vid">
                                      <img class="minisess-spons-logo-btn" :src="getImageSrc(session.sponsor.logo_file)" />
                                  </div> -->
                                  <div class="col-sm-12 col-md-9 mini-sess-btn-body py-3">
                                      <h3>{{ session.topic }}</h3>
                                      <el-button class="mini-sess-room-btn mb-2" size="mini" type="warning" round>Event: {{ session.event_name }}</el-button>
                                      <!-- <p>{{ session.description }}</p> -->
                                      <div v-html="session.description"></div>
                                      <div class="mini-sess-date mt-4">
                                          <span>{{ session.start_time | moment }}</span>
                                      </div>
                                  </div>
                                  <div class="col-sm-12 col-md-3 mini-sess-btn-col">
                                      <!--<el-button type="info" round @click="regSess(session.id)">Sign Up</el-button>
                                      <el-button type="primary" round @click="gotoMiniSessVid(session.id)">More Info</el-button> -->
                                      <el-button type="primary" class="join-sess-btn" round @click="joinMiniSessVid(session.id, session.join_url)">Join</el-button>
                                  </div>
                              </div>
                          </div>
                        </div>
                        

                  </div>


              </div>
            </section>
            <!-- /.content -->
        </div>
    </transition>
</template>

<script>
import moment from 'moment'

  export default {
    name: 'MiniSessions',
    data() {
        return {
          token: this.$cookies.get('token'),
          reg_id: this.$cookies.get('reg_id'),
          user_id: this.$cookies.get('user_id'),
          loading: false,
          schedtab: null,
          day1: [],
          day2: [],
          day3: [],
          day4: [],
          day5: [],
          allSess: [],
          mySess: [],
          assignedSess: [],
          currentURL: window.location.origin + '/',

          dateSetting:  this.$route.query.date,
          allowAnyDate: false,
          serverDateTime: null,
          apiDomain: this.$store.state.apiUrl
        }
    },
    filters: {
        moment: function (date) {
            return moment(date).format('MMM DD YYYY, h:mm a')
        }
    },
    computed: {
        assigned_sess() {
            return this.allSess.sort((a, b) => new Date(a.start_time) - new Date(b.start_time)).filter((el) => { 
                return this.assignedSess.map((sel) => { return sel.id }).includes(el.id)
            })
        },
    },
    methods: {
      getDay1() {
        axios.get(this.apiDomain + `/api/minisession/rooms/schedule?date=2021-02-22`, {
          headers: { Authorization: "Bearer " + this.token }
        })
		    .then(res => {
				  if(res.data) {
            this.day1 = res.data
            // console.log(res.data)
          }
        })
        .catch(err => {
          // console.log(err);
        })
      },
      getDay2() {
        axios.get(this.apiDomain + `/api/minisession/rooms/schedule?date=2021-02-23`, {
          headers: { Authorization: "Bearer " + this.token }
        })
		    .then(res => {
				  if(res.data) {
            this.day2 = res.data
            // console.log(res.data)
          }
        })
        .catch(err => {
          // console.log(err);
        })
      },
      getDay3() {
        axios.get(this.apiDomain + `/api/minisession/rooms/schedule?date=2021-02-24`, {
          headers: { Authorization: "Bearer " + this.token }
        })
		    .then(res => {
				  if(res.data) {
            this.day3 = res.data
            // console.log(res.data)
          }
        })
        .catch(err => {
          // console.log(err);
        })
      },
      getDay4() {
        axios.get(this.apiDomain + `/api/minisession/rooms/schedule?date=2021-02-25`, {
          headers: { Authorization: "Bearer " + this.token }
        })
		    .then(res => {
				  if(res.data) {
            this.day4 = res.data
            // console.log(res.data)
          }
        })
        .catch(err => {
          // console.log(err);
        })
      },
      getDay5() {
        axios.get(this.apiDomain + `/api/minisession/rooms/schedule?date=2021-02-26`, {
          headers: { Authorization: "Bearer " + this.token }
        })
		    .then(res => {
				  if(res.data) {
            this.day5 = res.data
            // console.log(res.data)
          }
        })
        .catch(err => {
          // console.log(err);
        })
      },

      getAllSessions() {
          axios.get(this.apiDomain + `/api/session`, {
          headers: { Authorization: "Bearer " + this.token }
          })
              .then(res => {
                  if(res.data) {
                      this.allSess = res.data.data
                      // console.log(res.data)
                      //this.getAllUserSessions()
                  }
          })
          .catch(err => {
          // console.log(err);
          })
      },

      getAllUserSessions() {
          axios.get(this.apiDomain + `/api/session/user/${this.user_id}`, {
          headers: { Authorization: "Bearer " + this.token }
          })
              .then(res => {
                  if(res.data) {
                      this.mySess = res.data.data
                      this.mySess.forEach(element => {
                          this.assignedSess.push({ id: element.session_id })
                      })
                  }
          })
          .catch(err => {
          // console.log(err);
          })
      },      

      getImageSrc(img) {
          if (img) {
              var image = img.replace('public', 'storage')
              var image_src = this.currentURL + image
              return image_src
          }
      },
      regSess(id) {
        // console.log(id)
        // console.log(this.reg_id)

        axios.post(this.apiDomain + `/api/minisession/attendee/register`, {
            session_id: id,
            reg_id: this.reg_id},
            {
            headers: { Authorization: "Bearer " + this.token }
        })
        .then(response => {
          this.$message.success('You have successfully registered for this mini-session.')
          // console.log(response)
        })
        .catch(error => {
            // console.log(error.response)
            if (error.response.data.message === 'user already registered for mini session') {
              this.$message({
                  message: 'You are already registered for this mini-session.',
                  type: 'error',
                  duration: 10000
              })
            } else if (error.response.data.message === 'mini session already full') {
              this.$message({
                  message: 'There are no more available slots for this mini-session.',
                  type: 'error',
                  duration: 10000
              })
            }
        });
      },
      gotoMiniSessVid(id) {
        this.$router.push(this.apiDomain + '/mini-sessions-video?id=' + id)
        // axios.get(`/api/minisession/register/exists/${id}/${this.reg_id}`, {
        //     headers: { Authorization: "Bearer " + this.token }
        // })
        // .then(response => {
        //   // console.log(response)
        //     if (response.data.message === 'user not yet registered') {
        //       this.$message({
        //           message: 'You must register first before you can enter the Mini-Session Page.',
        //           type: 'error',
        //           duration: 10000
        //       })
        //     }
        // })
        // .catch(error => {
        //     if (error.response.data.message === 'user already registered') {
        //       this.$router.push('/mini-sessions-video?id=' + id)
        //     }
        // });
      },

      joinMiniSessVid(id, url) {

        var target = '_blank'
        window.open(url, target)        

        // axios.get(`/api/minisession/register/exists/${id}/${this.reg_id}`, {
        //     headers: { Authorization: "Bearer " + this.token }
        // })
        // .then(response => {
        //   // console.log(response)
        //     if (response.data.message === 'user not yet registered') {
        //       this.$message({
        //           message: 'You must register first before you can join this Mini-Session.',
        //           type: 'error',
        //           duration: 10000
        //       })
        //     }
        // })
        // .catch(error => {
        //     if (error.response.data.message === 'user already registered') {
        //       var target = '_blank'
        //       window.open(url, target)
        //     }
        // });
      },

      getDateSetting() {
          if (this.dateSetting === '1') {
              this.allowAnyDate = true
          } else {
              this.allowAnyDate = false
          }
      },

      setTabDates() {
        axios.get(this.apiDomain + `/api/getdatetime`)
        .then(response => {
            // console.log(response)
            var date = response.data.datetime
            var formatDate = moment(date, 'YYYY-MM-DDTHH:mm:ss').toDate()
            this.serverDateTime = formatDate
            var day1 = new Date('2021-02-22T00:00:00')
            var day2 = new Date('2021-02-23T00:00:00')
            var day3 = new Date('2021-02-24T00:00:00')
            var day4 = new Date('2021-02-25T00:00:00')
            var day5 = new Date('2021-02-26T00:00:00')
            var day6 = new Date('2021-02-27T00:00:00')

            // console.log('the original date from server is: ' + response.data.datetime)
            // console.log('the converted date from server is: ' + this.serverDateTime)

            if ((this.serverDateTime > day1) && (this.serverDateTime < day2)) {
              this.mSesstab = 'day1'
              this.schedtab = 'day1'
            } else if ((this.serverDateTime > day2) && (this.serverDateTime < day3)) {
              this.mSesstab = 'day2'
              this.schedtab = 'day2'
            } else if ((this.serverDateTime > day3) && (this.serverDateTime < day4)) {
              this.mSesstab = 'day3'
              this.schedtab = 'day3'
            } else if ((this.serverDateTime > day4) && (this.serverDateTime < day5)) {
              this.mSesstab = 'day4'
              this.schedtab = 'day4'
            } else if ((this.serverDateTime > day5) && (this.serverDateTime < day6)) {
              this.mSesstab = 'day5'
              this.schedtab = 'day5'
            } else {
              this.mSesstab = 'day1'
              this.schedtab = 'day1'
            }
        })
        .catch(error => {
            // console.log(error)
        });
      },
      checkMemberLoginStatus() {
        if (this.token) {
          // console.log('logged in')
        } else {
          location.href='/'
        }
      }
    },
    mounted() {
      this.setTabDates()
      this.getAllSessions()
      this.getDateSetting()
    },
    created() {
      this.checkMemberLoginStatus()
    }
  }
</script>

<style>
  .page-minisessions-login .page-header {
    padding-top:80px;
    margin-top:0;
  }
  .page-header .page-minisessions-header {
    background-image:url(/images/layout/page-head-bg-1.png);
    background-repeat:no-repeat;
    background-position: center;
    background-size: cover;
    min-height:160px;
  }
  .pcr-page-title,
  .pcr-page-subtitle {
    width:100%;
    text-align: center;
    color:#fff;
  }
  .pcr-page-title {
    font-size:24px;
    padding-top:50px;
  }
  .pcr-page-subtitle {
    font-size:18px;
  }
	.pcr-login-error .el-notification__content {
		text-align:left!important;
	}
  @media only screen and (max-width: 1220px)  {
    .page-minisessions-header .pcr-page-title,
    .page-minisessions-header .pcr-page-subtitle {
      padding-left:20px;
      padding-right:20px;
    }
    .col-sm-6 {
      width:50%!important;
    }
  }
	@media only screen and (max-width: 768px)  {
    .page-minisessions-header .pcr-page-title {
      padding-top:30px;
    }
	}
    .sess-sched-tabs .sched-day,
    .sess-sched-tabs .sched-date {
        display:block;
        font-size:0.8rem;
        line-height:0.7rem;
        color:#0174cc;
    }
    .sess-sched-tabs .sched-date {
        margin-top:5px;
        background:none;
    }
    .sess-sched-tabs .el-tabs__item.is-active,
    .sess-sched-tabs .el-tabs__item:hover {
        background-color:#0174cc;
    }
    .sess-sched-tabs .el-tabs__item.is-active span,
    .sess-sched-tabs .el-tabs__item:hover span {
        color:#fff;
    }
    .sess-sched-tabs .el-tabs__item {
        padding:0;
        border:1px solid #0174cc;
        border-left:none;
    }
    .sess-sched-tabs #tab-day1 {
        border-left:1px solid #0174cc;
    }
    .sess-sched-tab-label {
        padding:5px 20px 10px;
    }
    .sess-sched-tabs .el-tabs__active-bar {
        display: none;
    }
    .session-tabs,
    .session-tabs .el-tabs__nav.is-top {
        width:100%;
    }
    .session-tabs .el-tabs__nav-scroll {
        text-align: center;
    }
    .session-tabs .el-tabs__nav-wrap::after {
        display:none;
    }
    .session-tabs .el-tabs__content {
        padding-top:50px;
    }
    .mini-sess-cont {
        padding:20px 0;
        border-bottom:20px solid #f2f2f2;
    }
    .mini-sess-btn-col button {
        margin:20px 0 0!important;
        width:100%;
    }
    .mini-sess-btn-vid img {
        width:100%;
    }

        .mini-sess-btn-body h3 {
            color:#0174cc;
            font-size:0.9rem;
            font-weight:bold;
        }
        .mini-sess-room-btn {
            background:#f75202!important;
            color:#fff;
            padding:8px 20px;
            display:inline-block;
            border-radius: 50px;
        }
        .mini-sess-spons-logo {
            max-height:30px;
        }
    .join-sess-btn {
        background-color:#00005c!important;
        color:#fff!important;
        border:1px solid #00005c!important;
    }
</style>
