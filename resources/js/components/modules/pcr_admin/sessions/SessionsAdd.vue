<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <i class="el-icon-s-home"></i>
                <span> / Sessions / Add Session</span>
            </div>
            <div class="container-fluid pb-5">

                <div class="admin-conent my-5 mx-3 p-4">
                    <el-button size="small" class="mb-4" type="primary" @click.native="$router.push('/admin/sessions')">Back</el-button>
                    <div class="bg-white p-4">
                        <h1>Add New Session</h1>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <p class="mb-2 font-weight-bold">Session Title <span class="required-item">*</span></p>
                                <el-input class="mb-4" placeholder="Title" v-model="topic"></el-input>
                            </div>

                            <div class="col-sm-12 col-md-12">
                                <p class="mb-2 font-weight-bold">Description <span class="required-item">*</span></p>
                                <!-- <el-input
                                    class="w-100 mb-4"
                                    type="textarea"
                                    :rows="3"
                                    placeholder="Enter description"
                                    v-model="description">
                                </el-input> -->
                                 <el-tiptap
                                    v-model="description"
                                    :charCounterCount="false"
                                    :extensions="extensions"
                                    placeholder="Description"
                                    height="500"
                                    />
                                    <div class="ann-char-count text-right mb-4">
                                        Characters: {{ description.length }}
                                    </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <p class="mb-2 font-weight-bold">Event Name <span class="required-item">*</span></p>
                                <el-input class="mb-4" placeholder="Enter Event Name" v-model="event_name"></el-input>
                            </div>


                            <div class="col-sm-12 col-md-6">
                                <p class="mb-2 font-weight-bold">Select Room <span class="required-item">*</span></p>
                                <el-select v-model="room_id" @change="roomChange()" placeholder="Select" class="w-100 mb-4">
                                    <el-option
                                        v-for="room in roomsOpt"
                                        :key="room.room_id"
                                        :label="room.label"
                                        :value="room.room_id">
                                    </el-option>
                                </el-select>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <p class="mb-2 font-weight-bold">Select Start Date <span class="required-item">*</span></p>
                                <el-date-picker
                                    class="w-100 mb-4"
                                    v-model="start_date"
                                    type="date"
                                    @change="setDate"
                                    value-format="yyyy-MM-dd"
                                    placeholder="Select Date">
                                </el-date-picker>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <p class="mb-2 font-weight-bold">Select Start Time <span class="required-item">*</span></p>
                                <el-time-picker
                                    class="w-100"
                                    :default-value="default_time"
                                    v-model="start_time"
                                    placeholder="Select Time"
                                    format="HH:mm A"
                                    value-format="HH:mm:ss"
                                    @change="setTime">
                                </el-time-picker>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <p class="mb-2 font-weight-bold">Select Session Duration <span class="required-item">*</span></p>
                                <el-select v-model="duration" placeholder="Select" class="w-100">
                                    <el-option
                                        v-for="duration in durationOpt"
                                        :key="duration.value"
                                        :label="duration.label"
                                        :value="duration.value">
                                    </el-option>
                                </el-select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <p class="mb-2 font-weight-bold">Zoom Email Address <span class="required-item">*</span></p>
                                <el-input class="mb-4" placeholder="Zoom Email Address" v-model="zoom_email" disabled></el-input>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <p class="mb-2 font-weight-bold">Zoom Key <span class="required-item">*</span></p>
                                <el-input class="mb-4" placeholder="Zoom Key" v-model="zoom_key" disabled></el-input>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <p class="mb-2 font-weight-bold">Zoom Secret <span class="required-item">*</span></p>
                                <el-input class="mb-4" placeholder="Zoom Secret" v-model="zoom_secret" disabled></el-input>
                            </div>

                            <!--<div class="col-sm-12 col-md-4">
                                <p class="mb-2 font-weight-bold">Maximum Number of Participants</p>
                                <el-input-number class="mb-3" v-model="max_user" :min="1"></el-input-number>
                            </div>-->
                        </div>
                        <el-button class="mt-5" type="primary" @click="saveSesion1" :loading="savingStatus">Save</el-button>
                    </div>
                </div>

            </div>

            <el-dialog
            :visible.sync="emptyRoomsDialog"
            width="50%"
            class="text-center">
            <div style="word-break:normal;">The are currently no existing Rooms. Please create a valid Room first.</div>
            <div style="word-break:normal;">Click the button below to go the Rooms page then click on the 'Add Room' button.</div>
            <el-button class="mt-3" type="primary" @click.native="$router.push('/admin/rooms')">Go to Rooms</el-button>
            </el-dialog> 
                    
        </div>
    </div>
</template>

<script>

import {
  // necessary extensions
  Doc,
  Text,
  Paragraph,
  Heading,
  Bold,
  Underline,
  Italic,
  Strike,
  ListItem,
  BulletList,
  OrderedList,
} from 'element-tiptap';

export default {
    data() {
        return {
            extensions: [
                new Doc(),
                new Text(),
                new Paragraph(),
                new Heading({ level: 5 }),
                new Bold({ bubble: false }),
                new Underline({ bubble: false, menubar: false }),
                new Italic(),
                new Strike(),
                new ListItem(),
                new BulletList(),
                new OrderedList(),
            ],
            dateSetting:  this.$route.query.date,
            allowAnyDate: false,
            savingStatus: false,
            emptyRoomsDialog: false,
            rooms: [],
            roomsOpt: [],

            room_id: '',
            topic: '',
            description: '',
            zoom_email: '',
            zoom_key: '',
            zoom_secret: '',
            max_user: 300,
            session: [],
            default_time: new Date(0, 0, 0, 8, 0),
            start_date: '',
            start_time: '',
            event_name: '',
            duration: '',
            dateOpt: [{
                    value: '2021-02-22',
                    label: 'Februrary 22, 2021 (Monday)'
                    }, {
                    value: '2021-02-23',
                    label: 'Februrary 23, 2021 (Tuesday)'
                    }, {
                    value: '2021-02-24',
                    label: 'Februrary 24, 2021 (Wednesday)'
                    }, {
                    value: '2021-02-25',
                    label: 'Februrary 25, 2021 (Thursday)'
                    }, {
                    value: '2021-02-26',
                    label: 'Februrary 26, 2021 (Friday)'
                    }],
            durationOpt: [{
                    value: '60',
                    label: '1 Hour'
                    }, {
                    value: '120',
                    label: '2 Hours'
                    }, {
                    value: '180',
                    label: '3 Hours'
                    }, {
                    value: '240',
                    label: '4 Hours'
                    }, {
                    value: '300',
                    label: '5 Hours'
                    }],   
            token: '',
            apiDomain: this.$store.state.apiUrl
        }
    },
    methods: {
        getDateSetting() {
            if (this.dateSetting === '1') {
                this.allowAnyDate = true
            } else {
                this.allowAnyDate = false
            }
        },
        getAllRooms() {
            axios.get(this.apiDomain + `/api/room`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    if (res.data.data.length === 0) {
                        this.emptyRoomsDialog = true
                    } else {
                        this.rooms = res.data.data;
                        this.rooms.forEach(element => {
                            this.roomsOpt.push({ label: element.room_name, room_id: element.id })
                        })
                    }
                }
			})
			.catch(err => {
				// console.log(err);
			})
        },        
        setTime() {
            // console.log(this.start_date + 'T' + this.start_time)
        },
        setDate() {
            // console.log(this.start_date)
        },
        saveSesion1() {

            if (!this.topic) {
                this.$message.error('Please provide the Session Title.')
            } else if (!this.description) {
                this.$message.error('Please provide a description for this Session.')
            } else if (!this.event_name) {
                this.$message.error('Please provide an Event Name for this Session.')
            } else if (!this.room_id) {
                this.$message.error('Please select a room for this Session.')
            } else if (!this.start_date) {
                this.$message.error('Please select a start date for this Session.')
            } else if (!this.start_time) {
                this.$message.error('Please select a start time for this Session.')
            } else if (!this.duration) {
                this.$message.error('Please select a duration for this Session.')
            } else {
                this.savingStatus = true
                var randomPW = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15)
                var finpw = String(randomPW).slice(0, 10)

                axios.post(this.apiDomain + `/api/session`, {
                    room_id: this.room_id,
                    topic: this.topic,
                    type: 2,
                    start_time: this.start_date + 'T' + this.start_time,
                    duration: this.duration,
                    timezone: 'Asia/Singapore',
                    event_name: this.event_name,
                    password: finpw,
                    description: this.description,
                    settings: {
                        host_video: true,
                        participant_video: true,
                        mute_upon_entry: true,
                        waiting_room: true
                    }
                    },
                    {
                    headers: { Authorization: "Bearer " + this.token }
                })
                .then(response => {
                    // console.log(response)
                    this.$message.success('Session successfully added.')
                    this.$router.push('/admin/sessions')
                })
                .catch(error => {
                    if (error.response.data.status_code === 401) {
                        this.$message({
                            message: 'Session could not be added. Please make sure you have selected a valid Room with correct Zoom Email, Key, and Secret.',
                            type: 'error',
                            duration: 10000
                        })
                    } else {
                        this.$message({
                            message: 'You cannot book another session on the same day/time with the same zoom account and zoom email.',
                            type: 'error',
                            duration: 10000
                        })
                    }
                    // console.log(error.response.data.status_code)
                    this.savingStatus = false
                })

            }

        },

        roomChange() {
            axios.get(this.apiDomain + `/api/room/${this.room_id}`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    // console.log(res.data)
                    this.zoom_email = res.data.data.zoom_email
                    this.zoom_key = res.data.data.zoom_key
                    this.zoom_secret = res.data.data.zoom_secret
                }
			})
			.catch(err => {
				// console.log(err);
			})
        },
        getTokenCookie() {
            var token = 'token'
            var match = document.cookie.match(new RegExp('(^| )' + token + '=([^;]+)'))
            if (match) {
                this.token = match[2].replace('%7C', '|')
                // console.log(this.token)
            }
            else{
                // console.log('No token found');
            }
        },
    },
	mounted() {
        this.getTokenCookie()
        this.getAllRooms()
        this.getDateSetting()
	}
}
</script>

<style>

.content-wrapper {
    padding-top: 65px;
}

.ann-sidebar button {
    width:100%;
}
.ann-sidebar .el-card__header {
    font-weight:bold;
    color:#174A84;
}
.ann-featured-upload,
.ann-featured-upload .el-upload,
.ann-featured-upload img {
    width:100%;
}
.blank-featured-img {
    border:1px solid #DCDFE6;
    border-radius:4px;
    width:100%;
    padding:8px 10px;
}
.msg-img-thumb {
    border:1px solid #DCDFE6;
    margin-bottom:10px;
    padding:10px;
}
.msg-img-change {
    color:#131313;
    font-style: italic;
}
.el-tiptap-editor__content strong {
    font-weight: bold;
}
.admin-conent .el-tiptap-editor__content h1 {
    font-size:2.5rem!important;
    color:#000;
    margin-top:20px!important;
    margin-bottom:20px!important;
    font-weight: normal;
}
.ann-char-count {
    padding:10px;
    border:1px solid #ebeef5;
}
</style>
