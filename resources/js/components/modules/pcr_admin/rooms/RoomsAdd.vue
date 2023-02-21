<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <i class="el-icon-s-home"></i>
                <span> / Rooms / Add Room</span>
            </div>            
            <div class="container-fluid pb-5">
                
                <div class="admin-conent my-5 mx-3 p-4">
                    <el-button size="small" class="mb-4" type="primary" @click.native="$router.push('/admin/rooms')">Back</el-button>
                    <div class="bg-white p-4">
                        <h1>Add New Room</h1>      
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="ann-content">
                                    <p class="mb-2 font-weight-bold">Room Name <span class="required-item">*</span></p>
                                    <el-input class="mb-4" placeholder="Room Name" v-model="room_name"></el-input>

                                    <p class="mb-2 font-weight-bold">Zoom Email Address <span class="required-item">*</span></p>
                                    <el-input class="mb-4" placeholder="Zoom Email Address" v-model="zoom_email"></el-input>

                                    <p class="mb-2 font-weight-bold">Zoom Key <span class="required-item">*</span></p>
                                    <el-input class="mb-4" placeholder="Zoom Key" v-model="zoom_key"></el-input>

                                    <p class="mb-2 font-weight-bold">Zoom Secret <span class="required-item">*</span></p>
                                    <el-input class="mb-4" placeholder="Zoom Secret" v-model="zoom_secret"></el-input>
                           

                                </div>

                                <el-button class="mt-5" type="primary" @click="saveRoom">Save Room</el-button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>


export default {
    data() {
        return {
            room_name: '',
            zoom_email: '',
            zoom_key: '',
            zoom_secret: '',
            token: '',
            apiDomain: this.$store.state.apiUrl
        }
    },
    methods: {
     
        saveRoom() {
            if (!this.room_name) {
                this.$message.error('Please provide the room name.')
            } else if (!this.zoom_email) {
                this.$message.error('Please provide the zoom email to use for this room.')
            } else if (!this.zoom_key) {
                this.$message.error('Please provide the zoom key to use for this room.')
            } else if (!this.zoom_secret) {
                this.$message.error('Please provide the zoom secret to use for this room.')
            } else {

                axios.post(this.apiDomain + `/api/room`, {
                    room_name: this.room_name,
                    zoom_email: this.zoom_email,
                    zoom_key: this.zoom_key,
                    zoom_secret: this.zoom_secret},
                    {
                    headers: { Authorization: "Bearer " + this.token }
                })
                .then(response => {
                    // console.log(response)
                    this.$message.success('Room successfully added.')
                    this.$router.push('/admin/rooms')
                })
                .catch(error => {
                    this.$message.error('Room could not be added. Please try again.')
                    // console.log(error)
                })

            }

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
