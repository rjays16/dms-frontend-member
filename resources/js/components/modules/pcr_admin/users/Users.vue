<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <i class="el-icon-s-home"></i>
                <span> / Users</span>
            </div>            
            <div class="container-fluid pb-5">
                <div class="admin-conent bg-white my-5 mx-3 p-4">
                    <div class="mb-2">
                    <span class="table-title">Users</span>
                    <el-button @click="importDialog = true" type="primary" size="mini" class="float-right import-btn ml-3" icon="el-icon-upload2">Import users by CSV</el-button>
                    <el-button @click="$router.push('/admin/users/add')" type="primary" size="mini" class="float-right sponsors-add-btn ml-3" icon="el-icon-plus">Add User</el-button>
                    </div>
                    <div>
                        <el-input
                            class="search-box"
                            placeholder="Search"
                            v-model="searchTerm"
                            @keyup.enter.native="getSearchResults">
                            <i slot="suffix" class="el-input__icon el-icon-search"></i>
                        </el-input>                            
                        <el-button class="ml-2" :loading="tableLoading" @click="getSearchResults">Search</el-button>                        
                    </div>
                    <div v-if="showSearchRes" class="col-sm-12 col-md-12 text-center">
                        <div class="show-search-res">
                            <span class="show-search-res-txt">Showing Search Results...</span>
                            <el-button class="ml-2" size="mini" type="info" :loading="tableLoading" @click="clearSearchResults">Clear</el-button>
                        </div>
                    </div>                    
                        <el-table
                            stripe
                            v-loading="tableLoading"
                            :data="users.data"
                            class="enrolled-sponsors-table mt-2"
                            :default-sort = "{prop: 'room_name', order: 'ascending'}"
                            style="width: 100%">
                            <el-table-column
                                prop="email"
                                label="Email">
                            </el-table-column>                            
                            <el-table-column
                                prop="last_name"
                                label="Name">
                                <template slot-scope="scope">
                                    {{ scope.row.last_name + ', ' + scope.row.first_name }}
                                </template>                                
                            </el-table-column>                            
                            <el-table-column
                                prop="group"
                                label="Tag">
                                <template slot-scope="scope">
                                    {{ getUserTag(scope.row.tag_id) }}
                                </template>                                
                            </el-table-column>                        
                            <el-table-column
                                prop="actions"
                                label="Actions"
                                align="center"
                                class-name="">
                                <template slot-scope="scope">
                                    <el-button size="mini" @click="editUser(scope.row.id)" type="primary" icon="el-icon-edit">Update</el-button>
                                    <el-button size="mini" @click="showDeleteDialog(scope.row.id)" type="danger" icon="el-icon-delete">Delete</el-button>
                                </template>
                            </el-table-column>                            
                        </el-table>

                        <div class="block text-center mt-5" v-if="!showSearchRes">
                            <ul id="member-pages" class="pcr-pagination">
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedUsers(users.first_page_url)" size="mini" plain type="primary">
                                        First
                                    </el-button>                                    
                                </li>
                                <li class="mx-1" v-for="page in users.links" :key="page.label">
                                    <el-button class="px-2" @click="getPagedUsers(page.url)" size="mini" :plain="page.active ? false : true" type="primary">
                                        {{ paginLabel(page.label) }}
                                    </el-button>
                                </li>
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedUsers(users.last_page_url)" size="mini" plain type="primary">
                                        Last
                                    </el-button>                                    
                                </li>                                
                            </ul>
                        </div>

                </div>

            </div>
        </div>
      
        <el-dialog
        :visible.sync="deleteSessDialog"
        width="50%"
        class="text-center">
        <div>Are you sure you want to delete this User?</div>
        <el-button class="mt-3" type="danger" @click="deleteSess">Delete</el-button>
        <el-button class="mt-3" type="Info" @click="deleteSessDialog = false">Cancel</el-button>
        </el-dialog>

        <el-dialog
        :visible.sync="importDialog"
        title="Import users via CSV file"
        width="50%">
            <div class="import-file-box p-4 mb-4 text-left">
                <el-upload
                    action="/"
                    accept=".csv"
                    :auto-upload="false"
                    :show-file-list="false"
                    :on-change="onImageChange">

                    <el-button size="small" class="import-btn">Click to select CSV file</el-button>

                </el-upload>
                <div v-if="import_file_name" class="mt-4">
                    <span>Selected file: <i class="el-icon-document"></i> <em class="font-weight-bold">{{ import_file_name }}</em></span>
                </div>
                <div v-else class="mt-4">
                    <span>Selected file: <span class="font-weight-bold">none</span></span>
                </div>               
            </div>
            <div class="text-right">     
                <el-button class="mt-3" :disabled="!import_file_name" type="primary" @click="importUsers">Import</el-button>
                <el-button class="mt-3" type="Info" @click="importDialog = false">Cancel</el-button>
            </div>
        </el-dialog>                 

    </div>
    
</template>

<script>
import moment from 'moment'

export default {
    data() {
        return {
            importDialog: false,
            showSearchRes: false,
            tableLoading: false,
            searchTerm: '',
            tags: [],
            users: [],
            sponsors: [],
            sponsorOpt: [],            
            deleteSessDialog: false,
            forDeleteID: '',
            forEdit_password: '',
            token: '',

            image_types: [".csv"],
			import_file: null,
            import_file_name: null,
			import_file_url: '',
			import_file_change: false,
			import_file_deleted: false,
            apiDomain: this.$store.state.apiUrl
        }
    },
    validations: {

    },
    filters: {
        moment: function (date) {
            return moment(date).format('MMM DD YYYY');
        }
    },      
    methods: {
        showDeleteDialog(id) {
            this.forDeleteID = id
            this.deleteSessDialog = true
        },
        importUsers() {
            const importData = new FormData();
            importData.append("file", this.import_file)
                        
            axios.post(this.apiDomain + `/api/admin/import`, importData,
                {
                headers: { Authorization: "Bearer " + this.token }
            })
            .then(response => {
                // console.log(response)
                this.$message.success('Users successfully imported.')
                this.getAllUsers()
                this.importDialog = false
            })
            .catch(error => {
                // console.log(error.data)
                this.$message.error('Error importing users.')
            })
        },
        deleteSess() {
            axios.delete(this.apiDomain + `/api/attendees/delete/${this.forDeleteID}`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				this.deleteSessDialog = false
                this.$message.success('User deleted.')
                this.getAllUsers()
                this.forDeleteID === ''
			})
			.catch(err => {
				// console.log(err);
                this.deleteSessDialog = false
                this.$message.error('User could not be deleted.')
                this.getAllUsers()
                this.forDeleteID === ''
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
        getAllUsers() {
            axios.get(this.apiDomain + `/api/attendees`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    this.users = res.data.data;
                    // console.log(res.data)
                }
			})
			.catch(err => {
				// console.log(err);
			})
        },
        getPagedUsers(url) {
            if (!url) {

            } else {
                axios.get(url, {
                    headers: { Authorization: "Bearer " + this.token }
                })
                .then(res => {
                    if(res.data) {
                        this.users = res.data.data;
                        // console.log(res.data)
                    }
                })
                .catch(err => {
                    // console.log(err);
                })
            }            
        },        
        editUser(user) {
            this.forEdit_room_id = room.id
            this.forEdit_room_name = room.room_name
            this.forEdit_zoom_email = room.zoom_email
            this.forEdit_zoom_key = room.zoom_key
            this.forEdit_zoom_secret = room.zoom_secret
            this.forEdit_sponsor_id = room.sponsor_id

            this.editUserDialog = true
        },

        closeEdit() {
            this.forEdit_room_name === '',
            this.forEdit_zoom_email === '',
            this.forEdit_zoom_key === '',
            this.forEdit_zoom_secret === '',
            this.forEdit_sponsor_id === '',
            this.forEdit_room_id === '',
            this.editUserDialog = false
        },

        editUser(id) {
            this.$router.push('/admin/users/update?id=' + id)
        },

        saveRoom() {
            if (!this.forEdit_room_name) {
                this.$message.error('Please provide the room name.')
            } else if (!this.forEdit_zoom_email) {
                this.$message.error('Please provide the zoom email to use for this room.')
            } else if (!this.forEdit_zoom_key) {
                this.$message.error('Please provide the zoom key to use for this room.')
            } else if (!this.forEdit_zoom_secret) {
                this.$message.error('Please provide the zoom secret to use for this room.')
            } else if (!this.forEdit_sponsor_id) {
                this.$message.error('Please assign a sponsor for this room.')
            } else {

                axios.put(this.apiDomain + `/api/minisession/room/edit/${this.forEdit_room_id}`, {
                    room_name: this.forEdit_room_name,
                    zoom_email: this.forEdit_zoom_email,
                    zoom_key: this.forEdit_zoom_key,
                    zoom_secret: this.forEdit_zoom_secret,
                    sponsor_id: this.forEdit_sponsor_id},
                    {
                    headers: { Authorization: "Bearer " + this.token }
                })
                .then(response => {
                    // console.log(response)
                    this.$message.success('Room details successfully updated.')
                    this.closeEdit()
                    this.getAllUsers()
                })
                .catch(error => {
                    this.$message.error('Room details could not be updated. Please try again.')
                    // console.log(error)
                    this.closeEdit()
                    this.getAllUsers()
                })

            }
        },

        deleteConfirm(id) {
            this.deleteConfirmationTab = true
            this.forDeleteID = id
        },
        cancelDelete() {
            this.deleteConfirmationTab = false
            this.forDeleteID = ''
        },        
        deleteAnn() {
            this.is_processing = true
            var id = this.forDeleteID
            axios.delete(this.apiDomain + `/api/pcr/announcement/delete/${id}/`, { 
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
                // console.log(res.data)
                this.forDeleteID = ''
                this.deleteConfirmationTab = false
                this.getAllAnnouncements()
                this.$message.success('Announcement successfully deleted.')
                this.is_processing = false
			})
			.catch(err => {
                // console.log(err)
                this.forDeleteID = ''
                this.deleteConfirmationTab = false
                this.getAllAnnouncements()
                this.$message.error('Error deleting announcement.')
                this.is_processing = false
			})
        },
        paginLabel(label) {
            var currentLabel = String(label)
            if (currentLabel.includes('Previous')) {
                return '<'
            } else if (currentLabel.includes('Next')) {
                return '>'
            } else {
                return currentLabel
            }
        },
        getSearchResults() {
            this.tableLoading = true
            axios.get(this.apiDomain + `/api/attendees/search/${this.searchTerm}`, {
                headers: { "Authorization": "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    // console.log(res.data)
                    this.users = res.data;
                    this.tableLoading = false
                    this.showSearchRes = true
                }
			})
			.catch(err => {
                // console.log(err)
                this.tableLoading = false
			});		
        },
        clearSearchResults () {
            this.searchTerm = ''
            this.showSearchRes = false
            this.getAllUsers()
        },
		validateFile(file, type, callback) {

			let valid_types = this.image_types;
			if (!file) {
				callback(false);
			} else if (file.size > this.file_max) {
				this.$message.error("File is too big");
				callback(false);
			} else {
				callback(true);
			}
        },        
		onImageChange(file) {
			this.validateFile(file.raw, 'photo', (result) => {
				if (result == true) {
					this.import_file = file.raw;
                    this.import_file_name = file.raw.name;
					this.import_file_url = URL.createObjectURL(file.raw);
					this.import_file_change = true;
				}
			})
		},
        getAllTags() {
            axios.get(this.apiDomain + `/api/all/tags`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    this.tags = res.data.data;
                    // console.log(res.data)
                }
			})
			.catch(err => {
				// console.log(err);
			})
        },
        getUserTag(id) {
            let tempTag = this.tags
            
            tempTag = tempTag.filter((item) => {
            return (item.id === id)
            })
            
            if (tempTag.length === 0) {
                // console.log('empty')
                return 'None Selected'
            } else {
                // console.log('not empty')
                return tempTag[0].tag_name
            }  
        }        
    },
	mounted() {
        this.getTokenCookie()
		this.getAllUsers()
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
.import-btn,
.import-btn:hover,
.import-btn:active,
.import-btn:visited,
.import-btn:link,
.import-btn:not(:hover) {
    background:#217342!important;
    border-color:#217342!important;
    color:#fff;
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
.import-file-box {
    background:#f5f5f5;
    width:100%;
    border-radius:4px;
}
</style>
