<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <i class="el-icon-s-home"></i>
                <span> / Tags</span>
            </div>            
            <div class="container-fluid pb-5">
                <div class="admin-conent bg-white my-5 mx-3 p-4">
                    <div class="mb-2">
                    <span class="table-title">Tags</span>
                    <el-button @click="$router.push('/admin/tags/add')" type="primary" size="mini" class="float-right sponsors-add-btn ml-3" icon="el-icon-plus">Add Tag</el-button>
                    </div>
                 
                        <el-table
                            stripe
                            v-loading="tableLoading"
                            :data="filterTagList"
                            class="enrolled-sponsors-table mt-2"
                            :default-sort = "{prop: 'room_name', order: 'ascending'}"
                            style="width: 100%">
                            <el-table-column
                                prop="id"
                                :min-width="10"
                                align="center"
                                label="ID">
                                <template slot-scope="scope">
                                    <el-button size="mini" style="min-width:50px;background-color:#4a4a4a;border:1px solid #4a4a4a;" type="info"><span class="font-weight-bold">{{ scope.row.id }}</span></el-button>
                                </template>                                   
                            </el-table-column>
                            <el-table-column
                                prop="tag_name"
                                :min-width="60"
                                label="Tag Name">
                            </el-table-column> 
                            <el-table-column
                                prop="actions"
                                label="Actions"
                                align="center"
                                :min-width="30"
                                class-name="">
                                <template slot-scope="scope">
                                    <el-button size="mini" :disabled="isTagSys(scope.row.id)" @click="editUser(scope.row.id)" type="primary" icon="el-icon-edit">Update</el-button>
                                    <el-button size="mini" :disabled="isTagSys(scope.row.id)" @click="showDeleteDialog(scope.row.id)" type="danger" icon="el-icon-delete">Delete</el-button>
                                </template>
                            </el-table-column>                            
                        </el-table>


                </div>

            </div>
        </div>
      

            <!--<el-dialog
            title="Edit Room Details"
            :visible.sync="editUserDialog"
            width="50%"
            @close="closeEdit">      
            <div class="mt-3">
                <div class="col-sm-12 col-md-12">
                    <div class="ann-content">
                        <p class="mb-2 font-weight-bold">Room Name <span class="required-item">*</span></p>
                        <el-input class="mb-4" placeholder="Room Name" v-model="forEdit_room_name"></el-input>

                        <p class="mb-2 font-weight-bold">Zoom Email Address <span class="required-item">*</span></p>
                        <el-input class="mb-4" placeholder="Zoom Email Address" v-model="forEdit_zoom_email"></el-input>

                        <p class="mb-2 font-weight-bold">Zoom Key <span class="required-item">*</span></p>
                        <el-input class="mb-4" placeholder="Zoom Key" v-model="forEdit_zoom_key"></el-input>

                        <p class="mb-2 font-weight-bold">Zoom Secret <span class="required-item">*</span></p>
                        <el-input class="mb-4" placeholder="Zoom Secret" v-model="forEdit_zoom_secret"></el-input>

                        <p class="mb-2 font-weight-bold">Select Sponsor<span class="required-item">*</span></p>
                        
                        <el-select v-model="forEdit_sponsor_id" placeholder="Select">
                            <el-option
                                v-for="spons in sponsorOpt"
                                :key="spons.spons_id"
                                :label="spons.label"
                                :value="spons.spons_id">
                            </el-option>
                        </el-select>                                 

                    </div>

                    <el-button class="mt-5" type="primary" @click="saveRoom">Save</el-button>
                    <el-button @click="closeEdit">Cancel</el-button>
                </div>
            </div>
        </el-dialog>-->

        <el-dialog
        :visible.sync="deleteTagDialog"
        width="50%"
        class="text-center">
        <div>Are you sure you want to delete this Tag?</div>
        <el-button class="mt-3" type="danger" @click="deleteTag">Delete</el-button>
        <el-button class="mt-3" type="Info" @click="deleteTagDialog = false">Cancel</el-button>
        </el-dialog>           

    </div>
    
</template>

<script>
import moment from 'moment'

export default {
    data() {
        return {
            showSearchRes: false,
            tableLoading: false,
            searchTerm: '',            
            tags: [],
            sponsors: [],
            sponsorOpt: [],            
            deleteTagDialog: false,
            forDeleteID: '',


            forEdit_password: '',

            token: '',
            apiDomain: this.$store.state.apiUrl
        }
    },
    validations: {

    },
    computed: {
        filterTagList() {
            return this.tags.filter(data => data.id !== 1)   
        }
    },       
    filters: {
        moment: function (date) {
            return moment(date).format('MMM DD YYYY');
        }
    },      
    methods: {
        showDeleteDialog(id) {
            this.forDeleteID = id
            this.deleteTagDialog = true
        },
        deleteTag() {
            axios.delete(this.apiDomain + `/api/tags/delete/${this.forDeleteID}`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				this.deleteTagDialog = false
                this.$message.success('Tag deleted.')
                this.getAllTags()
                this.forDeleteID === ''
			})
			.catch(err => {
				// // console.log(err);
                this.deleteTagDialog = false
                this.$message.error('Tag could not be deleted.')
                this.getAllTags()
                this.forDeleteID === ''
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
        getAllTags() {
            axios.get(this.apiDomain + `/api/all/tags`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    this.tags = res.data.data;
                    // // console.log(res.data)
                }
			})
			.catch(err => {
				// // console.log(err);
			})
        },

        editUser(id) {
            this.$router.push('/admin/tags/update?id=' + id)
        },

        deleteConfirm(id) {
            this.deleteConfirmationTab = true
            this.forDeleteID = id
        },
        cancelDelete() {
            this.deleteConfirmationTab = false
            this.forDeleteID = ''
        },
        isTagSys(id) {  //Function for checking if tag is Admin or Default
            if (id === 1) {
                return true
            } else if (id === 2) {
                return true
            } else {
                false
            }
        }
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

.add-content-box {
     height: 70px;
     width: 100%;
     border: 3px dashed #ccc;
}

.rounded-add-btn {
    position: relative;
    top: 50%;
    transform: translateY(-50%);
    text-align: center;
    height: 50px;
    width: 50px;
    border-radius: 50%;
    border: 3px dashed #ccc;
}

.add-btn-default {
    color: #ccc;
    transition: all 0.20s;
}

.add-btn-hovered {
    background-color: #ccc;
    color: #fff;
    transition: all 0.20s;
}

.fa-icon-custom {
    position: relative;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.5em;
}
.paper-container {
	box-shadow: 0 10px 10px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
	padding: 20px 25px 35px 25px;
	background-color: #fff;
	margin-top: 30px;
}
.admin-conent .table-title {
    color:#174A84;
    font-size:1rem;
    font-weight:bold;
    margin-bottom:20px;
}
.enrolled-sponsors-table th .cell {
    color:#131313;
}
.sponsors-add-btn {
    background:#0c015d!important;
    border-color:#0c015d!important;
}
.edit-sponsor-dialog .el-dialog {
    margin-top:5vh!important;
}
.edit-sponsor-dialog .el-dialog__body {
    padding-top:10px;
}
.edit-sponsor-dialog .el-dialog__title {
    font-weight:bold;
    color:#174A84;
}
</style>
