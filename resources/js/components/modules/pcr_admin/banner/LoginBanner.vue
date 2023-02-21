<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <i class="el-icon-s-home"></i>
                <span> / Page Banners / Login Page</span>
            </div>            
            <div class="container-fluid pb-5">
                
                <div class="admin-conent my-5 mx-3 p-4">
                    <div class="bg-white p-4">
                        <h1>Login Page Banner Settings</h1>      
                        <div class="row">
                            <div class="col-sm-12 col-md-7">
                                <div class="ann-content">
                                    <p class="font-weight-bold mb-2">Banner Title Text</p>
                                    <el-input class="mb-4" placeholder="Enter Banner Heading Title Line 1" v-model="banner_title1"></el-input>
                                    <p class="font-weight-bold mb-2">Banner Title Text Color</p>
                                    <div class="row mb-4 color-picker">
                                        <div class="col-md-2">
                                            <el-color-picker v-model="banner_text_color"></el-color-picker>
                                            
                                        </div>
                                        <div class="col-md-10">
                                            <el-input :disabled="true" v-model="banner_text_color"></el-input>
                                        </div>
                                    </div>
                                    <!--<p class="font-weight-bold mb-2">Banner Title Line 2</p>
                                    <el-input class="mb-4" placeholder="Enter Banner Heading Title Line 2" v-model="banner_title2"></el-input>
                                    <p class="font-weight-bold mb-2">Banner Subheading</p>
                                    <el-input class="mb-4" placeholder="Enter Banner Subheading" v-model="banner_subheading"></el-input>
                                    <p class="font-weight-bold mb-2">Call to Action Button Text</p>
                                    <el-input class="mb-4" placeholder="Enter text for CTA button" v-model="banner_cta_text"></el-input>
                                    <p class="font-weight-bold mb-2">Call to Action Button URL</p>
                                    <el-input class="mb-4" placeholder="Enter URL for CTA button" v-model="banner_cta_url"></el-input>-->

                                    <el-card class="box-card mb-4" shadow="never">
                                        <div slot="header" class="clearfix">
                                            <span>Banner Image</span>
                                        </div>
                                    <el-upload
                                        class="avatar-uploader ann-featured-upload"
                                        action="/"
                                        accept="image/png, image/jpeg, image/jpg"
                                        :auto-upload="false"
                                        :show-file-list="false"
                                        :on-change="onImageChange"
                                    >
                                        <div v-if="banner_file_url">
                                            <div class="msg-img-thumb">
                                                <img
                                                    :src="banner_file_url"
                                                    class="avatar"
                                                />
                                            </div>
                                            <span class="msg-img-change">Click above to change banner image.
                                                <br>Recommended image dimensions: <b class="font-weight-bold">1920 x 500 pixels</b>
                                            </span>
                                        </div>
                                        <div v-else class="blank-featured-img">
                                            <i class="el-icon-plus avatar-uploader-icon"></i> Set Banner Image
                                        </div>
                                        
                                    </el-upload>                                    
                                    </el-card>


                                </div>
                            </div>
                            <div class="col-sm-12 col-md-5">
                                <div class="ann-sidebar">


                                </div>
                            </div>
                        </div>
                        <el-button v-if="newStatus" @click="saveBanner" class="mb-4" type="success">Save</el-button>
                        <el-button v-else @click="editBanner" class="mb-4" type="success">Update</el-button>                        
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
            image_types: ["image/png", "image/jpg", "image/jpeg"],
            banner_data: [],
            banner_title1: "",
            banner_text_color: "#000000",
            banner_page_type: "login",
            banner_title2: "",
            banner_subheading: "",
            banner_cta_text: "",
            banner_cta_url: "",
            banner_id: "",
			banner_file: null,
			banner_file_url: '',
			banner_file_change: false,
			banner_file_deleted: false,
            newStatus: true,   
            token: '',
            apiDomain: this.$store.state.apiUrl
        }
    },
    computed: {
      
    },      
    methods: {
        getBannerData() {
            axios.get(this.apiDomain + `/api/banner`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
                // console.log(res.data)
				if(res.data.status_code === 200) {
                    if (res.data.data.length > 0) {
                        this.assignValues(res.data.data)
                        // this.banner_title1 = res.data.data[0].title1
                        // this.banner_title2 = res.data.data[0].title2
                        // this.banner_subheading = res.data.data[0].subheading
                        // this.banner_cta_text = res.data.data[0].cta_button_text
                        // this.banner_cta_url = res.data.data[0].cta_button_url
                        // this.banner_id = res.data.data[0].id
                        // this.banner_file_url = this.apiDomain + '/' + res.data.data[0].image_file.replace("public", "storage")                        
                        // this.newStatus = false
                    } else {
                        // // console.log(res.data.data.length)
                        this.newStatus = true      
                    }
                }
			})
			.catch(err => {
				// // console.log(err);
			})
        },
        assignValues(data) {
            let banner = data.filter(el => el.page_type === "login")
            if (banner.length > 0) {
            this.banner_title1 = banner[0].title1
            this.banner_title2 = banner[0].title2
            this.banner_subheading = banner[0].subheading
            this.banner_cta_text = banner[0].cta_button_text
            this.banner_cta_url = banner[0].cta_button_url
            this.banner_id = banner[0].id
            this.banner_text_color = banner[0].text_color
            this.banner_file_url = this.apiDomain + '/' + banner[0].image_file.replace("public", "storage")                        
            this.newStatus = false
            } else {
                this.newStatus = true     
            }

        },
        saveBanner() {
            if (!this.banner_file) {
                this.$message.error('Please upload a banner image.')
            } else { 
                const bannerData = new FormData();
                bannerData.append("title1", this.banner_title1)
                bannerData.append("title2", this.banner_title2)
                bannerData.append("subheading", this.banner_subheading)
                bannerData.append("cta_button_text", this.banner_cta_text)
                bannerData.append("cta_button_url", this.banner_cta_url)
                bannerData.append("image_file", this.banner_file)
                bannerData.append("page_type", this.banner_page_type)
                bannerData.append("text_color", this.banner_text_color)
                            
                axios.post(this.apiDomain + `/api/banner`, bannerData,
                    {
                    headers: { Authorization: "Bearer " + this.token }
                })
                .then(response => {
                    // // console.log(response)
                    this.$message.success('Banner settings successfully created.')
                    this.getBannerData()
                })
                .catch(error => {
                    // // console.log(error.data)
                    this.$message.error('Error creating banner settings.')
                })
            }
        },
        editBanner() {
            axios.put(this.apiDomain + `/api/banner/update/${this.banner_id}`, {
                title1: this.banner_title1,
                title2: this.banner_title2,
                subheading: this.banner_subheading,
                cta_button_text: this.banner_cta_text,
                cta_button_url: this.banner_cta_url,
                page_type: this.banner_page_type,
                text_color: this.banner_text_color},
                {
                headers: { Authorization: "Bearer " + this.token }
            })            
            .then(response => {
                // // console.log(response)
                if (this.banner_file) {
                    const bannerData = new FormData();
                    bannerData.append("id", this.banner_id)
                    bannerData.append("image_file", this.banner_file)
                                
                    axios.post(this.apiDomain + `/api/banner/upload`, bannerData,
                        {
                        headers: { Authorization: "Bearer " + this.token }
                    })
                    .then(response => {
                        // // console.log(response)
                        this.$message.success('Banner settings successfully updated.')
                        this.getBannerData()
                    })
                    .catch(error => {
                        // // console.log(error.data)
                        this.$message.error('Error updating banner settings.')
                    })                    
                } else {
                    this.$message.success('Banner settings successfully updated.')
                }
            })
            .catch(error => {
                // // console.log(error.data)
                this.$message.error('Error updating banner settings.')
            })

        },        
		validateFile(file, type, callback) {

			let valid_types = this.image_types;
			if (!file) {
				callback(false);
			} else if (file.size > this.file_max) {
				this.$message.error("File is too big");
				callback(false);
			} else if (!valid_types.includes(file.type)) {
				this.$message.error("Invalid file type");
				callback(false);
			} else {
				callback(true);
			}
        },
		onImageChange(file) {
			this.validateFile(file.raw, 'photo', (result) => {
				if (result == true) {
					this.banner_file = file.raw;
					this.banner_file_url = URL.createObjectURL(file.raw);
					this.banner_file_change = true;
				}
			})
		},
        getTokenCookie() {
            var token = 'token'
            var match = document.cookie.match(new RegExp('(^| )' + token + '=([^;]+)'))
            if (match) {
                this.token = match[2].replace('%7C', '|')
                // // console.log(this.token)
            }
            else{
                // console.log('No token found');
            }
        },        
    },
	mounted() {
        this.getTokenCookie()
        this.getBannerData()
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
.color-picker .el-color-picker,
.color-picker .el-color-picker__trigger {
    width: 100%;
}
</style>
