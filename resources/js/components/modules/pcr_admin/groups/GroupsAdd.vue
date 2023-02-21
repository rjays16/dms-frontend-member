<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <i class="el-icon-s-home"></i>
                <span> / Tags / Add Tag</span>
            </div>            
            <div class="container-fluid pb-5">
                
                <div class="admin-conent my-5 mx-3 p-4">
                    <el-button size="small" class="mb-4" type="primary" @click.native="$router.push('/admin/tags')">Back</el-button>
                    <div class="bg-white p-4">
                        <h1>Add New Tag</h1>      
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="ann-content">
                                    <p class="mb-2 font-weight-bold">Tag Name <span class="required-item">*</span></p>
                                    <el-input class="mb-4" placeholder="Enter tag name" v-model="tag_name"></el-input>

                               
                                </div>

                                <el-button class="mt-5" type="primary" @click="saveUser">Save</el-button>
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
            tag_name: '',
            token: '',
            apiDomain: this.$store.state.apiUrl
        }
    },
    methods: {
      
        saveUser() {

            if (!this.tag_name) {
                this.$message.error('Please provide a name for the tag.')
            } else {            

                axios.post(this.apiDomain + `/api/tags`, {
                    tag_name: this.tag_name},
                    {
                    headers: { Authorization: "Bearer " + this.token }
                })
                .then(response => {
                    // // console.log(response)
                    this.$message.success('Tag successfully added.')
                    this.$router.push('/admin/tags')
                })
                .catch(error => {
                    this.$message.error('Tag could not be added. Please try again.')
                    // // console.log(error)
                })

            }

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
