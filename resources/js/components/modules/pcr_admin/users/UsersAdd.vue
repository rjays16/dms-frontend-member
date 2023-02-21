<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <i class="el-icon-s-home"></i>
                <span> / Users / Add User</span>
            </div>            
            <div class="container-fluid pb-5">
                
                <div class="admin-conent my-5 mx-3 p-4">
                    <el-button size="small" class="mb-4" type="primary" @click.native="$router.push('/admin/users')">Back</el-button>
                    <div class="bg-white p-4">
                        <h1>Add New User</h1>      
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="ann-content">
                                    <p class="mb-2 font-weight-bold">First Name <span class="required-item">*</span></p>
                                    <el-input class="mb-4" placeholder="Enter first name" v-model="first_name"></el-input>

                                    <p class="mb-2 font-weight-bold">Last Name <span class="required-item">*</span></p>
                                    <el-input class="mb-4" placeholder="Enter last name" v-model="last_name"></el-input>

                                    <p class="mb-2 font-weight-bold">Email <span class="required-item">*</span></p>
                                    <el-input class="mb-4" placeholder="Enter email address" v-model="email"></el-input>

                                    <p class="mb-2 font-weight-bold">Password <span class="required-item">*</span></p>
                                    <el-input class="mb-4" placeholder="Enter password" v-model="password" disabled></el-input>

                                    <p class="mb-2 font-weight-bold">Tag <span class="required-item">*</span></p>
                                    <el-select v-model="tag_id" placeholder="Select" class="w-100 mb-4">
                                        <el-option
                                            v-for="tag in tagsOpt"
                                            :key="tag.tag_id"
                                            :label="tag.label"
                                            :value="tag.tag_id">
                                        </el-option>
                                    </el-select>
                                
                                </div>

                                <el-button class="mt-5" type="primary" @click="saveUser">Save User</el-button>
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
            tags: [],
            tagsOpt: [],
            tag_id: '',            
            first_name: '',
            last_name: '',
            email: '',
            password: 'password123',
            token: '',
            apiDomain: this.$store.state.apiUrl
        }
    },
    methods: {
      
        saveUser() {

            if (!this.first_name) {
                this.$message.error('Please enter a first name.')  
            } else if (!this.last_name) {
                this.$message.error('Please enter a last name.')
            } else if (!this.email) {
                this.$message.error('Please provide a valid email address.')           
            } else if (!this.password) {
                this.$message.error('Please provide a password.')
            } else if (!this.tag_id) {
                this.$message.error('Please select a tag for this user.')                  
            } else {            

                axios.post(this.apiDomain + `/api/createroomuser`, {
                    first_name: this.first_name,
                    last_name: this.last_name,
                    email: this.email,
                    password: this.password,
                    tag_id: this.tag_id},
                    {
                    headers: { Authorization: "Bearer " + this.token }
                })
                .then(response => {
                    // console.log(response)
                    this.$message.success('User successfully added.')
                    this.$router.push('/admin/users')
                })
                .catch(error => {
                    this.$message.error('User could not be added. Please try again.')
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
        getAllTags() {
            axios.get(this.apiDomain + `/api/all/tags`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    this.tags = res.data.data;
                    this.tags.forEach(element => {
                        this.tagsOpt.push({ label: element.tag_name, tag_id: element.id })
                    })
                }
			})
			.catch(err => {
				// console.log(err);
			})
        },       
    },
	mounted() {
        this.getTokenCookie()
        this.getAllTags()
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
