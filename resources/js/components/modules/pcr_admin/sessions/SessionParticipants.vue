<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <i class="el-icon-s-home"></i>
                <span> / Sessions / Participants</span>
            </div>            
            <div class="container-fluid pb-5">
                <el-button size="small" class="mt-5 mb-3 mx-3" type="primary" @click.native="$router.push('/admin/sessions')">Back to Sessions</el-button>
                <div class="admin-conent bg-white mb-5 mx-3 p-4">
                    <span class="table-title">{{ session_topic }}</span>
                    <el-button @click="allUsersDialog = true" type="primary" size="mini" class="float-right sponsors-add-btn ml-3" icon="el-icon-plus">Add Participant</el-button>
                    <div class="mt-2">
                        <el-input
                            class="search-box"
                            placeholder="Search"
                            v-model="searchTermSessUsers"
                            @keyup.enter.native="getSearchResultsSessUsers">
                            <i slot="suffix" class="el-input__icon el-icon-search"></i>
                        </el-input>                            
                        <el-button class="ml-2" :loading="tableLoadingSessUsers" @click="getSearchResultsSessUsers">Search</el-button>                        
                    </div>
                    <div v-if="showSearchSessUsers" class="col-sm-12 col-md-12 text-center">
                        <div class="show-search-res">
                            <span class="show-search-res-txt">Showing Search Results...</span>
                            <el-button class="ml-2" size="mini" type="info" :loading="tableLoadingSessUsers" @click="clearSearchResultsSessUsers">Clear</el-button>
                        </div>
                    </div>
                        <el-table
                            stripe
                            :data="participApproved"
                            class="enrolled-sponsors-table mt-2"
                            :default-sort = "{prop: 'test', order: 'ascending'}"
                            style="width: 100%">
                            <el-table-column
                                prop="email"
                                label="User Email">
                            </el-table-column>
                            <!--<el-table-column
                                prop="is_approved"
                                label="Approved?">
                            </el-table-column>-->
                            <el-table-column
                                prop="last_name"
                                label="Name">
                                <template slot-scope="scope">
                                    <span v-if="users_all">{{ scope.row.user.last_name + ', ' + scope.row.user.first_name }}</span>
                                </template>                                
                            </el-table-column>                              
                            <el-table-column
                                prop="tag"
                                label="Tag">
                                <template slot-scope="scope">
                                    <span v-if="users_all">{{ getUserTag(scope.row.user.tag_id) }}</span>
                                </template>                                 
                            </el-table-column>
                            <el-table-column
                                prop="actions"
                                label="Actions"
                                align="center"
                                class-name="">
                                <template slot-scope="scope">
                                    <el-button size="mini" @click="showDeleteDialog(scope.row.id)" type="danger" icon="el-icon-delete">Delete</el-button>
                                </template>
                            </el-table-column>                            
                        </el-table>

                        <div class="block text-center mt-5" v-if="!showSearchSessUsers">
                            <ul id="member-pages" class="pcr-pagination">
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedSessParticipants(participants.first_page_url)" size="mini" plain type="primary">
                                        First
                                    </el-button>                                    
                                </li>
                                <li class="mx-1" v-for="page in participants.links" :key="page.label">
                                    <el-button class="px-2" @click="getPagedSessParticipants(page.url)" size="mini" :plain="page.active ? false : true" type="primary">
                                        {{ paginLabel(page.label) }}
                                    </el-button>
                                </li>
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedSessParticipants(participants.last_page_url)" size="mini" plain type="primary">
                                        Last
                                    </el-button>                                    
                                </li>                                
                            </ul>
                        </div>
                </div>

            </div>
        </div>
      

            <el-dialog
            title="Add Participants to this Session"
            class="add-particip-dialog"
            :visible.sync="allUsersDialog"
            width="70%"
            @close="closeEdit">      

                <div class="col-sm-12 col-md-12">
                    <div class="ann-content">

                    <el-tabs v-model="activeTab" type="border-card">
                        <el-tab-pane label="Add Individually" name="indiv">
                            <span slot="label"><i class="dripicons dripicons-user"></i> <span class="font-weight-bold">Add Individually</span></span>
                            <div>
                                <el-input
                                    class="search-box"
                                    placeholder="Search"
                                    v-model="searchTermAllUsers"
                                    @keyup.enter.native="getSearchResultsAllUsers">
                                    <i slot="suffix" class="el-input__icon el-icon-search"></i>
                                </el-input>                            
                                <el-button class="ml-2" :loading="tableLoadingAllUsers" @click="getSearchResultsAllUsers">Search</el-button>                        
                            </div>
                            <div v-if="showSearchAllUsers" class="col-sm-12 col-md-12 text-center">
                                <div class="show-search-res">
                                    <span class="show-search-res-txt">Showing Search Results...</span>
                                    <el-button class="ml-2" size="mini" type="info" :loading="tableLoadingAllUsers" @click="clearSearchResultsAllUsers">Clear</el-button>
                                </div>
                            </div>
                            <el-table
                                stripe
                                empty-text="No available users to add as participants"
                                :data="users"
                                class="enrolled-sponsors-table mt-1"
                                :default-sort = "{prop: 'test', order: 'ascending'}"
                                style="width: 100%">
                                <el-table-column
                                    prop="email"
                                    label="Email">
                                </el-table-column>                                
                                <el-table-column
                                    prop="name"
                                    label="Name">
                                    <template slot-scope="scope">
                                        {{ scope.row.last_name + ', ' + scope.row.first_name  }}
                                    </template>                                    
                                </el-table-column>                               
                                <el-table-column
                                    prop="tag_name"
                                    label="Tag">
                                    <template slot-scope="scope">
                                        <span v-if="scope.row.user">{{ getUserTag(scope.row.user.tag_id) }}</span>
                                    </template>
                                </el-table-column>                       
                                <el-table-column
                                    prop="actions"
                                    label="Actions"
                                    align="center"
                                    class-name="">
                                    <template slot-scope="scope">
                                        <div v-if="checkAssignment(scope.row.email)">
                                            Already added
                                        </div>
                                        <div v-else>
                                            <el-button :loading="particip_loading" size="mini" @click="addParticipant(scope.row.email)" type="primary" icon="el-icon-circle-plus-outline">Add as Participant</el-button>
                                            
                                        </div>
                                    </template>
                                </el-table-column>                            
                            </el-table>

                            <div class="block text-center mt-5" v-if="!showSearchAllUsers">
                                <ul id="member-pages" class="pcr-pagination">
                                    <li class="mx-1">
                                        <el-button class="px-1" @click="getPagedUsers(users.first_page_url)" size="mini" plain type="primary">
                                            First
                                        </el-button>                                    
                                    </li>
                                    <li class="mx-1" v-for="page in users_pagination" :key="page.label">
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

                        </el-tab-pane>
                        <el-tab-pane name="group">
                            <span slot="label"><i class="dripicons dripicons-tags"></i> <span class="font-weight-bold">Add by Tag</span></span>

                            <el-table
                                stripe
                                :data="filterTagList"
                                class="enrolled-sponsors-table mt-2"
                                :default-sort = "{prop: 'room_name', order: 'ascending'}"
                                style="width: 100%">
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
                                        <el-button size="mini" :loading="particip_loading" :disabled="scope.row.id === 1" @click="addUsersByTag(scope.row.id)" type="primary" icon="el-icon-circle-plus-outline">Add Users with this Tag</el-button>
                                    </template>
                                </el-table-column>                            
                            </el-table>

                        </el-tab-pane>
                    </el-tabs>




                    </div>
                    <div class="mt-4 text-right">
                        <el-button @click="allUsersDialog = false">Close</el-button>
                    </div>
                </div>
            </el-dialog>

        <el-dialog
        :visible.sync="deleteUserDialog"
        width="50%"
        class="text-center">
        <div>Are you sure you want to delete this User?</div>
        <el-button class="mt-3" type="danger" @click="deleteUser">Delete</el-button>
        <el-button class="mt-3" type="Info" @click="deleteUserDialog = false">Cancel</el-button>
        </el-dialog>           

    </div>
    
</template>

<script>
import moment from 'moment'

export default {
    data() {
        return {
            activeTab: 'indiv',
            showSearchAllUsers: false,
            tableLoadingAllUsers: false,
            searchTermAllUsers: '',                
            showSearchSessUsers: false,
            tableLoadingSessUsers: false,
            searchTermSessUsers: '',              
            particip_loading: false,
            users: [],
            users_pagination: [],
            users_all: [],
            tags: [],
            sponsors: [],
            sponsorOpt: [],
            participants: [],
            participApproved: [],
            participants_all: [],
            assigned_emails: [],
            allUsersDialog: false,
            deleteUserDialog: false,
            forDeleteID: '',
            session_id: this.$route.query.id,
            session_topic: 'Session',
            forEdit_password: '',

            token: '',
            apiDomain: this.$store.state.apiUrl
        }
    },
    validations: {

    },
    computed: {
        assigned_users() {
            return this.users.filter((el) => { 
                return !this.assigned_emails.map((sel) => { return sel.email }).includes(el.email) 
            })
        },
        filterTagList() {
            return this.tags.filter(data => data.id !== 1)   
        }
    },    
    methods: {
        addUsersByTag(id) {
            this.particip_loading = true
            axios.post(this.apiDomain + `/api/session/group/attend`, {
                tag_id: id,
                session_id: this.session_id},
                {
                headers: { Authorization: "Bearer " + this.token }
            })
            .then(response => {
                //// console.log(response)
                this.$message.success('Users successfully added.')
                this.getSessParticipants()
                this.particip_loading = false
            })
            .catch(error => {
                this.$message.error('Users could not be added. Please try again.')
                // console.log(error)
                this.particip_loading = false
            })
        },
        showDeleteDialog(id) {
            this.forDeleteID = id
            this.deleteUserDialog = true
        },
        deleteUser() {
            axios.delete(this.apiDomain + `/api/session/attendee/${this.forDeleteID}`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
                //// console.log(res.data)
				this.deleteUserDialog = false
                this.$message.success('User deleted.')
                this.assigned_emails = []
                this.getSessParticipants()
                this.getAllUsers()
                this.forDeleteID === ''
			})
			.catch(err => {
				// console.log(err);
                this.deleteUserDialog = false
                this.assigned_emails = []
                this.getSessParticipants()
                this.getAllUsers()
                this.forDeleteID === ''
			})
        },        
        getTokenCookie() {
            var token = 'token'
            var match = document.cookie.match(new RegExp('(^| )' + token + '=([^;]+)'))
            if (match) {
                this.token = match[2].replace('%7C', '|')
                //// console.log(this.token)
            }
            else{
                // console.log('No token found');
            }
        },
        getSess() {
            axios.get(this.apiDomain + `/api/session/${this.session_id}`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    //// console.log(res.data)
                    //// console.log(res.data.data.start_time.slice(11))
                    this.session_topic = 'Session: ' + res.data.data.topic
                }
			})
			.catch(err => {
				// console.log(err);
			})
        },
        createComparisonArray() {
            // API that returns non-paginated version of getAllSessionAttendees
            axios.get(this.apiDomain + `/api/session/attendees/all/${this.session_id}`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    //// console.log(res.data)
                    this.participants_all = res.data.data
                    this.participants_all.forEach(element => {
                        //this.assigned_emails.push({ email: element.email })
                        this.assigned_emails.push(element.email)
                    })
                    //// console.log(this.assigned_emails)
                }
			})
			.catch(err => {
				// console.log(err);
			})
        },
        getSessParticipants() {
            axios.get(this.apiDomain + `/api/session/attendees/${this.session_id}`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    // console.log(res.data.data.data)
                    var raw = res.data.data.data
                    var filtered = raw.filter(data => data.is_approved === 1)
                    this.participants = res.data.data
                    this.participApproved = filtered
                    //// console.log(res.data)
                    this.createComparisonArray()
                }
			})
			.catch(err => {
				// console.log(err);
			})
        },
        getPagedSessParticipants(url) {
            if (!url) {

            } else {
                axios.get(url, {
                    headers: { Authorization: "Bearer " + this.token }
                })
                .then(res => {
                    if(res.data) {
                        var raw = res.data.data.data
                        var filtered = raw.filter(data => data.is_approved === 1)
                        this.participants = res.data.data
                        this.participApproved = filtered
                        //// console.log(res.data)
                    }
                })
                .catch(err => {
                    // console.log(err);
                })
            }            
        },         
        checkAssignment(email) {
            if (this.assigned_emails.includes(email)) {
                return true
            } else {
                return false
            }
        },
        getEntireUsers() { // For getting all users unpaginated for use in comparison tables
            axios.get(this.apiDomain + `/api/attendees/get`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    this.users_all = res.data;
                    //// console.log(this.users_all)
                }
			})
			.catch(err => {
				// console.log(err);
			})
        },        
        getAllUsers() { // For gettting all users for individual assignment
            axios.get(this.apiDomain + `/api/admin/members/for-processing/get`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    this.users = res.data.data;
                    this.users_pagination = res.data.links
                    //// console.log(res.data)
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
                        this.users_pagination = res.data.links
                        //// console.log(res.data)
                    }
                })
                .catch(err => {
                    // console.log(err);
                })
            }            
        }, 
        addParticipant(user_email) {
            this.particip_loading = true
            axios.post(this.apiDomain + `/api/session/attend`, {
                email: user_email,
                session_id: this.session_id},
                {
                headers: { Authorization: "Bearer " + this.token }
            })
            .then(response => {
                //// console.log(response)
                this.$message.success('Participant successfully added.')
                this.getSessParticipants()
                this.particip_loading = false
            })
            .catch(error => {
                this.$message.error('Participant could not be added. Please try again.')
                // console.log(error)
                this.particip_loading = false
            })
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
                //// console.log(res.data)
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
        getSearchResultsAllUsers() {
            this.tableLoadingAllUsers = true
            axios.get(this.apiDomain + `/api/pcr/search?keyword=${this.searchTermAllUsers}`, {
                headers: { "Authorization": "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    this.users = res.data;
                    this.tableLoadingAllUsers = false
                    this.showSearchAllUsers = true
                }
			})
			.catch(err => {
                // console.log(err)
                this.tableLoadingAllUsers = false
			});		
        },
        clearSearchResultsAllUsers () {
            this.searchTermAllUsers = ''
            this.showSearchAllUsers = false
            this.getAllUsers()
        },
        getSearchResultsSessUsers() {
            this.tableLoadingSessUsers = true
            axios.get(this.apiDomain + `/api/session/attendees/search/${this.session_id}/${this.searchTermSessUsers}`, {
                headers: { "Authorization": "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    // console.log(res.data.data)
                    var raw = res.data.data
                    var filtered = raw.filter(data => data.is_approved === 1)
                    this.participApproved = filtered
                    // this.participants = res.data.data
                    this.tableLoadingSessUsers = false
                    this.showSearchSessUsers = true
                }
			})
			.catch(err => {
                // console.log(err)
                this.tableLoadingSessUsers = false
			});		
        },
        clearSearchResultsSessUsers () {
            this.searchTermSessUsers = ''
            this.showSearchSessUsers = false
            this.getSessParticipants()
        },
        getAllTags() {
            axios.get(this.apiDomain + `/api/all/tags`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    this.tags = res.data.data;
                    //// console.log(res.data)
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
                return 'None Selected'
            } else {
                //// console.log(tempTag[0].tag_name)
                return tempTag[0].tag_name
            }  
        },
        getUserName(email) {
            let tempName = this.users_all.users
            
            tempName = tempName.filter((item) => {
            return (item.email === email)
            })
            
            if (tempName.length === 0) {
                return 'None Selected'
            } else {
                return tempName[0].last_name + ', ' + tempName[0].first_name
            }  
        }  
    },
	mounted() {
        this.getTokenCookie()
        this.getAllTags()
        this.getSess()
        this.getSessParticipants()
		this.getAllUsers()
        //this.getEntireUsers()
        
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
.add-particip-dialog .el-dialog__title {
    font-weight:bold;
    color:#174A84;
}

</style>
